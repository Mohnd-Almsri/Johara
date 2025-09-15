<?php

namespace App\Services;

use App\Models\Projects\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectService
{
    // دالة تخزين الصور ترجع مصفوفة المسارات
    protected function storeImages(array $images, string $folder, Project $project, string $type)
    {
        foreach ($images as $image) {
            $path = $image->store($folder, 'public');
            // تخزين المسار مع النوع في جدول الصور عبر العلاقة المورف
            $project->images()->create([
                'path' => $path,
                'type' => $type,
            ]);
        }
    }

    public function storeProject($request)
    {
        try {
            $project = DB::transaction(function () use ($request) {
                $project = Project::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'location' => $request->location,
                    'date' => $request->date,
                    'contractor' => $request->contractor,
                    'category_id' => $request->category_id,
                    'details' => $request['details'],
                ]);

                if ($request->hasFile('exterior_images')) {
                    $this->storeImages($request->file('exterior_images'), 'projects/'.$project->id.'/exterior_images', $project, 'exterior');
                }

                if ($request->hasFile('interior_images')) {
                    $this->storeImages($request->file('interior_images'), 'projects/'.$project->id.'/interior_images', $project, 'interior');
                }

                return $project;
            });

            return $project;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateProject($request){
        try {
            $project = DB::transaction(function () use ($request) {
                $project = Project::where('id', $request->project_id)->with('images')->first();

                $project->update([
                    'name'        => $request->name ?? $project->name,
                    'description' => $request->description ?? $project->description,
                    'location'    => $request->location ?? $project->location,
                    'date'        => $request->date ?? $project->date,
                    'contractor'  => $request->contractor ?? $project->contractor,
                    'category_id' => $request->category_id ?? $project->category_id,
                    'details'     => $request->details ?? $project->details,
                ]);


                // حذف الصور المحددة
                if ($request->has('deleted_images')) {
                    foreach ($request['deleted_images'] as $id) {
                        $image = $project->images->firstWhere('id', $id); // استخدام الكولكشن يلي محمل مسبقاً لتجنب استعلام زائد
                        if ($image) {
                            Storage::disk('public')->delete($image->path);
                            $image->delete();
                        }
                    }
                }

                // رفع الصور الخارجية
                if ($request->hasFile('exterior_images')) {
                    $this->storeImages(
                        $request->file('exterior_images'),
                        'projects/' . $project->id . '/exterior_images',
                        $project,
                        'exterior'
                    );
                }

                // رفع الصور الداخلية
                if ($request->hasFile('interior_images')) {
                    $this->storeImages(
                        $request->file('interior_images'),
                        'projects/' . $project->id . '/interior_images',
                        $project,
                        'interior'
                    );
                }
                return $project;
            });

            return $project;

        }catch (\Exception $e) {
            throw $e;
        }
    }
    public function deleteProject($request)
    {
        try {
            DB::transaction(function () use ($request) {
                $project = Project::where('id', $request->id)->with('images')->first();

                if (!$project) {
                    throw new \Exception("Project not found");
                }

                $project->images()->each(function ($image) {
                    $image->delete();
                });

                Storage::disk('public')->deleteDirectory('projects/' . $project->id);

                $project->delete();
            });

            return true;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
