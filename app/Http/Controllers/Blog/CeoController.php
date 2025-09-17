<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\CeoResource;
use App\Models\Blog\Ceo;
use App\Services\CeoService;
use Illuminate\Http\Request;

class CeoController extends Controller
{
    Protected $CeoService;
public function __construct(CeoService $CeoService)
{
    $this->CeoService = $CeoService;
}
public function index()
    {
        try {
            $ceo = Ceo::with('images')->latest()->first();
            return response()->json([
                'status'=>'success',
                'data' => new CeoResource($ceo),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:ceos,id'
        ]);
        try {
            $ceo = Ceo::findOrFail($request->id);
            return response()->json([
                'message' => 'success',
                'data' => $ceo
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request) {
    $request->validate([
        'name' => 'required|string',
        'paragraph_1'=> 'required|string',
        'paragraph_2'=> 'required|string',
        'paragraph_3'=> 'required|string',
        'images'=> 'required|array|max:3|min:3',
        'images.*'=>'required|image|mimes:jpeg,png,jpg,gif'
    ]);
        try {
$ceo = $this->CeoService->create($request);
return response()->json([
    'message' => 'success',
    'ceo' => $ceo
]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:ceos,id',
            'name' => 'nullable|string',
            'paragraph_1'=> 'nullable|string',
            'paragraph_2'=> 'nullable|string',
            'paragraph_3'=> 'nullable|string',
            'images' => 'array|nullable|max:3',
            'images.*.id' => 'required_with:images|integer|exists:images,id',
            'images.*.image' => 'image|mimes:jpeg,jpg,png'
        ]);
        try {
            $ceo = $this->CeoService->update($request);
            return response()->json([
                'message' => 'success',
                'ceo' => $ceo
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }
    public function addImage(Request $request){
        $request->validate([
            'id'=>'required|integer|exists:ceos,id',
            'image'=>'required|image|mimes:jpeg,jpg,png'
        ]);
        try {
            $about = $this->CeoService->addImage($request);
            return response()->json([
                'message' => "image added successfully!",
                'about' => $about->load('images')

            ]);
        }
        catch (\Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id'=>'required|integer|exists:ceos,id'
        ]);
        try {
            $this->CeoService->delete($request);
            return response()->json([
                'message' => 'success'
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
