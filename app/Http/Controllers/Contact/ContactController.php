<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    Protected $ContactService;
    public function __construct(ContactService $ContactService){
        $this->ContactService = $ContactService;
    }

    public function index(){
        try {
            $contacts = Contact::all();

            $readContacts = $contacts->where('is_read', true)->values();    // المقرؤة
            $unreadContacts = $contacts->where('is_read', false)->values(); // الغير مقرؤة
            $unreadCount = $unreadContacts->count();

            return response()->json([
                'read' => $readContacts,
                'unread' => $unreadContacts,
                'unread_count' => $unreadCount,
            ]);




        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
    public function create(Request $request){
       $request->validate([
           'first_name' => 'required|string',
           'last_name' => 'required|string',
           'phone' => 'required|string',
           'email' => 'nullable|string|email',
           'message' => 'required|string',
       ]);
        try {
   $Contact  = $this->ContactService->create($request);
   return response()->json([
       'success' => true,
       'message' => 'Contact has been created successfully',
       'data' => $Contact
   ]);

        }catch (\Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
    public function show($id){}
public function markAsRead(Request $request){
        $request->validate([
            'id' => 'required|integer|exists:contacts,id',
        ]);
    try {
        $this->ContactService->markAsRead($request);

        return response()->json([
            'success' => true,
            'message' => 'Contact has been updated successfully',
        ]);
    }catch (\Exception $e){
        return response()->json(["message" => $e->getMessage()], 500);
    }
}

}
