<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Blog\Article;
use App\Services\ArticleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index()
    {
        try {
            $articles = Article::with('paragraphs.images')->get();
            return response()->json([
                'articles' => $articles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:articles,id'
        ]);
        try {
            $article = Article::where('id', $request->id)->with('paragraphs.images')->get();
            return response()->json([
                'article' => $article

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(ArticleRequest $request)
    {
        $article = $this->articleService->createArticle($request);
        return response()->json([
            'message' => 'Article created',
            'article' => $article->load('paragraphs.images')

        ]);
    }

    public function updateArticle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:articles,id',
            'title' => 'required|string',
            'description' => 'string',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);
        try {
            $article = $this->articleService->updateArticle($request);
            return response()->json([
                'message' => 'Article updated',
                'article' => $article
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function createOrUpdateParagraph(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',

            'paragraph_id' => 'nullable|exists:paragraphs,id',
            'title' => $request->has('paragraph_id') ? 'sometimes|string' : 'required|string',
            'body' => $request->has('paragraph_id') ? 'sometimes|string' : 'required|string',
            'order' => $request->has('paragraph_id') ? 'sometimes|integer' : 'required|integer',
            'image' => $request->has('paragraph_id') ? 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
                : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);
        try {
            if ($request->has('paragraph_id')) {
                $paragraph = $this->articleService->updateParagraph($request);
                return response()->json([
                    'message' => 'Paragraph updated',
                    'article' => $paragraph->load('article.paragraphs')
                ]);
            } else {
                $paragraph = $this->articleService->createParagraph($request);
                return response()->json([
                    'message' => 'Paragraph updated',
                    'article' => $paragraph->load('article.paragraphs')
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }

    public function deleteArticle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:articles,id'
        ]);


        try {
            $this->articleService->deleteArticle($request);
            return response()->json(['success' => true, 'message' => 'تم الحذف']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'المقال غير موجود'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }

    public function deleteParagraph(Request $request){
        $request->validate([
            'id' => 'required|exists:paragraphs,id',
            'article_id' => 'required|exists:paragraphs,id'
        ]);
        try {
            $this->articleService->deleteParagraph($request);

            return response()->json(['message' => 'paragraph deleted']);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }


}
