<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Projects\Project;
use App\Services\ArticleService;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $projectService;
    public function __construct(ProjectService $service)
    {
        $this->projectService = $service;

    }
    public function index()
    {
        $projects = Project::with('category','images')->get();
        if ($projects->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No projects found',
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'projects' => ProjectResource::collection($projects),
        ],200);
    }

    public function show(Project $project)
    {
        try {

            $project->load('images','category');

            $recommended=Project::where('id','!=',$project->id)->where('category_id','=',$project->category_id)->select('id','name','location','mainImage')->inRandomOrder()->limit(5)->get();

            return response()->json([
                'status' => 'success',
                'project' => new ProjectResource($project),
                'recommended' => $recommended,
            ], 200);




        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'date'=> 'required',
            'contractor' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'details' => 'required|array',

            'exterior_images' => 'nullable|array',
            'exterior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',

            'interior_images' => 'nullable|array',
            'interior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $project = $this->projectService->storeProject($request);

            // جلب الصور مع المشروع
            $project->load('images');

            $groupedImages = collect($project->images)->groupBy('type')->map(function ($images) {
                return $images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                    ];
                })->values(); // مهم: لتضمن يرجع Array مرتّب بدون key فاضي
            });

            $response = [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'location' => $project->location,
                'date' => $project->date,
                'contractor' => $project->contractor,
                'category_id' => $project->category_id,
                'details' => $project->details,
                'images' => [
                    'exterior' => $groupedImages->get('exterior', collect())->toArray(),
                    'interior' => $groupedImages->get('interior', collect())->toArray(),
                ],
            ];


            return response()->json(['data' => $response], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    public function update(Request $request)
    { $request->validate([
        'project_id' => 'required|integer|exists:projects,id',
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'location' => 'nullable|string',
        'date'=> 'nullable',
        'contractor' => 'nullable|string',
        'category_id' => 'nullable|integer|exists:categories,id',
        'details' => 'nullable|array',

        'exterior_images' => 'nullable|array',
        'exterior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',

        'interior_images' => 'nullable|array',
        'interior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',

        'deleted_images' => 'nullable|array',
        'deleted_images.*' => 'integer|exists:images,id',

    ]);
        try {

            $project = $this->projectService->updateProject($request);
            $project->load('images');
            $groupedImages = collect($project->images)->groupBy('type')->map(function ($images) {
                return $images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                    ];
                })->values();
            });

            $response = [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'location' => $project->location,
                'date' => $project->date,
                'contractor' => $project->contractor,
                'category_id' => $project->category_id,
                'details' => $project->details,
                'images' => [
                    'exterior' => $groupedImages->get('exterior', collect())->toArray(),
                    'interior' => $groupedImages->get('interior', collect())->toArray(),
                ],
            ];


            return response()->json(['data' => $response], 200);


        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:projects,id',
        ]);
        try {
            $this->projectService->deleteProject($request);
return response()->json([
    'success' => true,
    'message' => 'Project deleted successfully',
],200);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
