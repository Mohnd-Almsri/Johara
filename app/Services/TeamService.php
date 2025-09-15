<?php

namespace App\Services;

use App\Models\About\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamService
{
    public function TeamCreate($request)
    {

        $team = DB::transaction(function () use ($request) {

            $team = Team::create([
                'name' => $request->name,
                'role' => $request->role,
            ]);
            $team->update([
                'image' => $this->storeImage($request->image, $team)
            ]);
            return $team;
        });

        return $team;
    }

    public function TeamUpdate($request)
    {
        $team = DB::transaction(function () use ($request) {
            $team = Team::findOrFail($request->id);


            $team->update([
                'name' => $request->name ?? $team->name,
                'role' => $request->role ?? $team->role,
            ]);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                Storage::disk('public')->delete($team->image);

                $newImagePath = $this->storeImage($request->file('image'), $team);
                $team->update(['image' => $newImagePath]);
            }

            return $team;
        });

        return $team;
    }

    public function TeamDelete($request){
        $team = DB::transaction(function () use ($request) {
            $Team = Team::find($request->id);
            Storage::disk('public')->delete($Team->image);
            $Team->delete();
        });
        return true;
    }

    private function storeImage($image, $team)
    {
        $extension = $image->getClientOriginalExtension();

        $filename = str_replace(' ', '_', $team->name) . '.' . $extension;
        $folder = "team" . '/' . $team->id;
        $path = $image->storeAs($folder, $filename, 'public');
        return $path;

    }

}
