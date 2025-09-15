<?php

namespace App\Services;

use App\Models\Contact\Contact;
use Illuminate\Support\Facades\DB;

class ContactService
{
public function create($request){
    try {
$contact = DB::transaction(function () use ($request) {
   $contact = Contact::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'is_read' => false,
        'read_at' => now(),
        'message' => $request->message,
    ]);

   return $contact;
});
        return $contact;
    }catch (\Exception $e){
            return $e;
    }
}

public function markAsRead($request){
    try {
        Contact::where('id', $request->id)->update([
            'is_read' => true,
            'read_at' => now()]);

        return true;
    }catch (\Exception $e){
        return $e;
    }
}
}
