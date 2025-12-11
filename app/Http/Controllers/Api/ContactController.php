<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Public : Envoyer un message
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'subject' => 'nullable'
        ]);

        Message::create($data);
        return response()->json(['message' => 'Messaggio inviato!']);
    }

    // Admin : Lire les messages
    public function index()
    {
        return Message::latest()->get();
    }

    // Admin : Marquer comme lu
    public function update(Message $message)
    {
        $message->update(['is_read' => true]);
        return $message;
    }
    
    // Admin : Supprimer
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->noContent();
    }
}
