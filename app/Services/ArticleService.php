<?php

namespace App\Services;


use App\Models\Blog\Article;
use App\Models\Paragraph;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    public function createArticle($request)
    {
        try {
            $article = DB::transaction(function () use ($request) {
                $article = Article::create([
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
                $path = $this->storeImage($request->image, $article);
                $article->update([
                    'image' => $path,
                ]);
                foreach ($request->paragraphs as $para) {
                    $paragraph = Paragraph::create([
                        'article_id' => $article->id,
                        'title' => $para['title'],
                        'body' => $para['body'],
                        'order' => $para['order'],
                    ]);

                    $paragraph->images()->create([
                        'path' => $this->paragraphStoreImage($para['image'], $paragraph, $paragraph->id),
                    ]);
//                     foreach ($para['images'] ?? [] as  $index => $image) {
//                             $imageNumber = $index + 1;
//                             $paragraph->images()->create([
//                                 'path' => $this->paragraphStoreImage($image,$paragraph ,$imageNumber)
//                             ]);
//                     }
                }


                return $article;
            });
            return $article;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteArticle($request)
    {
        try {
            $article = Article::with('paragraphs.images')->find($request->id);

            if (!$article) {
                throw new \Exception('Article not found');
            }

            DB::transaction(function () use ($article) {
                foreach ($article->paragraphs as $paragraph) {
                    $paragraph->images()->delete();
                    $paragraph->delete();
                }
                Storage::disk('public')->deleteDirectory('articles/' . $article->id);
                $article->delete();
            });

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateArticle($request)
    {
        try {
            $article = DB::transaction(function () use ($request) {

                $article = Article::find($request->id);

                if (!$article) {
                    throw new \Exception('المقال غير موجود');
                }

                $article->update([
                    'title' => $request->title ?? $article->title,
                    'description' => $request->description ?? $article->description,
                ]);

                if ($request->hasFile('image')) {
                    if (!empty($article->image) && Storage::disk('public')->exists($article->image)) {
                        Storage::disk('public')->delete($article->image);
                    }


                    $path = $this->storeImage($request->image, $article);
                    $article->update([
                        'image' => $path
                    ]);
                }

                return $article;
            });

            return $article;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateParagraph($request)
    {
        try {
            $paragraph = DB::transaction(function () use ($request) {

                $paragraph = Paragraph::where('id', $request->paragraph_id)->where('article_id', $request->article_id)->first();
                if (!$paragraph) {
                    throw new \Exception('الفقرة غير موجودة');
                }

                $article = $paragraph->article;
                if (!$article) {
                    throw new \Exception('المقال غير موجود');
                }

// حذف الصورة اول شي منشان احذف المجلد تبع الصورة يلي اسمو على اسم العنوان القديم
                if ($request->hasFile('image')) {

                    $folderPath = "articles/{$paragraph->article->id}/{$paragraph->id}";
                    if (Storage::disk('public')->exists($folderPath)) {
                        Storage::disk('public')->deleteDirectory($folderPath);
                    }

                    foreach ($paragraph->images as $image) {
                        $image->delete();
                    }
                }


                $paragraph->update([
                    'title' => $request->title ?? $paragraph->title,
                    'body' => $request->body ?? $paragraph->body,
                ]);

                // swap between two image by order
                if ($request->has('order') && $request->order != $paragraph->order) {
                    $target = $article->paragraphs()
                        ->where('order', $request->order)
                        ->where('id', '!=', $paragraph->id)
                        ->first();

                    if ($target) {
                        // بدل بين الترتيبين
                        $oldOrder = $paragraph->order;
                        $paragraph->order = $target->order;
                        $target->order = $oldOrder;

                        $target->save();
                    } else {
                        // إذا ما في فقرة بالترتيب الجديد، بس غير ترتيب الحالية
                        $paragraph->order = $request->order;
                    }

                    $paragraph->save();
                }


                $path = $this->paragraphStoreImage($request->image, $paragraph, $paragraph->id);
                $paragraph->images()->create([
                    'path' => $path,
                ]);


                return $paragraph;
            });

            return $paragraph;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createParagraph($request) {
        try {
            $paragraph = DB::transaction(function () use ($request) {

                // تزحزح الترتيب: كل الفقرات يلي ترتيبها >= الجديد مننزيدها +1
                Paragraph::where('article_id', $request->article_id)
                    ->where('order', '>=', $request->order)
                    ->orderBy('order', 'desc') // ضروري نبلش من الأكبر مشان ما نلخبط الترتيب
                    ->get()
                    ->each(function ($p) {
                        $p->order += 1;
                        $p->save();
                    });

                // إنشاء الفقرة الجديدة
                $paragraph = Paragraph::create([
                    'article_id' => $request->article_id,
                    'title'      => $request->title,
                    'body'       => $request->body,
                    'order'      => $request->order,
                ]);

                // تخزين الصورة
                $paragraph->images()->create([
                    'path' => $this->paragraphStoreImage($request->image, $paragraph, $paragraph->id),
                ]);

                return $paragraph;
            });

            return $paragraph;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    public function deleteParagraph($request) {
        try {
            DB::transaction(function () use ($request) {
                $paragraph = Paragraph::where('id',$request->id)->where('article_id',$request->article_id)->first();

                if (!$paragraph) {
                    throw new \Exception('الفقرة غير موجودة');
                }

                $articleId = $paragraph->article_id;
                $paragraphOrder = $paragraph->order;

                // حذف الصور من التخزين
                $folderPath = "articles/{$articleId}/{$paragraph->id}";
                if (Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->deleteDirectory($folderPath);
                }

                // حذف الصور من قاعدة البيانات
                $paragraph->images()->delete();

                // حذف الفقرة
                $paragraph->delete();

                // تعديل ترتيب باقي الفقرات يلي كانت بعد الفقرة المحذوفة
                Paragraph::where('article_id', $articleId)
                    ->where('order', '>', $paragraphOrder)
                    ->orderBy('order', 'asc')
                    ->get()
                    ->each(function ($p) {
                        $p->order -= 1;
                        $p->save();
                    });
            });

            return true;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



    private function storeImage($image, $article)
    {
        $extension = $image->getClientOriginalExtension();

        $filename = str_replace(' ', '_', $article->title) . '.' . $extension;
        $folder = "articles" . '/' . $article->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }

    private function paragraphStoreImage($image, $paragraph, $imageNumber)
    {
        $extension = $image->getClientOriginalExtension();

        $filename = $imageNumber . '.' . $extension;
        $folder = "articles" . '/' . $paragraph->article->id . '/' . $paragraph->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }

}
