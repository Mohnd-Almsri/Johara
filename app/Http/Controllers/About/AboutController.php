<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use App\Models\About\About;
use App\Models\About\Service;
use App\Models\About\Team;
use App\Services\AboutService;
use App\Services\ServiceService;
use App\Services\TeamService;
use Illuminate\Http\Request;
use App\Trait\ImagePathTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    use ImagePathTrait;

    protected $AboutService, $TeamService,$ServiceService;

    public function __construct(AboutService $AboutService, TeamService $TeamService, ServiceService $ServiceService)
    {
        $this->AboutService = $AboutService;
        $this->TeamService = $TeamService;
        $this->ServiceService = $ServiceService;
    }
public function index(){
    try {
         $about = About::get()->first();
        if($about){
            $about->images->each(function($image){
            unset($image->imageable_type);
            unset($image->imageable_id);
        });}

         $team = Team::all();
         $services = Service::all();
         return response()->json([
            'message'=>'all data',
             'about' => $about,
             'team' => $team,
             'services' => $services,
         ]);

    }catch (\Exception $e){
            return response()->json([
                "status" => "failed",
                "message" => $e->getMessage()]);
    }




}

    //about us section
    public function about()
    {
        try {
            $about = About::get()->first();
            return response()->json([
                'data' => $about->load('images')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function aboutCreate(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'images' => 'array|required|max:2|min:2',
            'images.*' => 'image|mimes:jpeg,jpg,png'
        ]);

        try {
            $about = $this->AboutService->createAbout($request);
            return response()->json([
                'status' => 'success',
                'message' => 'about created successfully!',
                'data' => $about->load('images')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function aboutUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:abouts,id',
            'title' => 'nullable|string',
            'body' => 'nullable|string',
            'images' => 'array|nullable|max:2',
            'images.*.id' => 'required_with:images|integer|exists:images,id',
            'images.*.image' => 'image|mimes:jpeg,jpg,png',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'integer|exists:images,id'

        ]);
        try {

            $about = $this->AboutService->updateAbout($request);

            return response()->json([
                'message' => "about updated successfully!",
                'data' => $about
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],$e->getCode());
        }


    }

    public function aboutDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:abouts,id'
        ]);
        try {
            $this->AboutService->deleteAbout($request);
            return response()->json([
                'message' => "about deleted successfully!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function addImage(Request $request){
        $request->validate([
            'id'=>'required|integer|exists:abouts,id',
            'image'=>'required|image|mimes:jpeg,jpg,png'
        ]);
        try {
            $about = $this->AboutService->addImage($request);
            return response()->json([
                    'message' => "image added successfully!",
                'about' => $about->load('images')

            ]);
        }
        catch (\Exception $e){

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ],$e->getCode());
        }
    }

    //team section
    public function team()
    {
        try {
            $team = Team::get();
            return response()->json([
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function teamShow(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:teams,id'

        ]);
        try {
            $team = Team::findOrFail($request->id);
            return response()->json([
                'message'=>'team',
                'data' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function teamCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:teams,name',
            'role' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        try {
            $team = $this->TeamService->TeamCreate($request);
            return response()->json([
                'message' => "team created successfully!",
                'team' => $team
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function teamUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:teams,id',
            'name' => 'nullable|string|unique:teams,name,' . $request->id,
            'role' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);
        try {
            $team = $this->TeamService->TeamUpdate($request);
            return response()->json([
                'message' => "team updated successfully!",
                'team' => $team
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function teamDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:teams,id'
        ]);
        try {
            $this->TeamService->TeamDelete($request);
            return response()->json([
                'message' => "team deleted successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    //service section
    Public function service(){
        try {
            $services= Service::get();
            return response()->json([
                'data' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function serviceShow(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:services,id'

        ]);
        try {
            $service = Service::findOrFail($request->id);
            return response()->json([
                'message'=>'service',
                'data' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    Public function serviceCreate(Request $request){
        $request->validate([
            'name' => 'required|string|unique:services,name',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        try {
            $service = $this->ServiceService->ServiceCreate($request);
            return response()->json([
                'message' => "service saved successfully!",
                'service' => $service

            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    Public function serviceUpdate(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:services,id',
            'name'=>'nullable|string|unique:services,name,'.$request->id,
            'description'=>'nullable|string',
            'image'=>'nullable|image|mimes:jpeg,jpg,png',
        ]);
        try {

            $service = $this->ServiceService->ServiceUpdate($request);
            return response()->json([
                'message' => "service updated successfully!",
                'service' => $service
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    Public function serviceDelete(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:services,id'
        ]);
        try {
            $this->ServiceService->ServiceDelete($request);
            return response()->json([
                'message' => "service deleted successfully!"
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
