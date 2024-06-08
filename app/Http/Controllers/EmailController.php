<?php

namespace App\Http\Controllers;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        $countEmail = Email::all()->count();
        if($countEmail > 30){
            $firstEmail = Email::first();
            $firstEmail->delete();
        }
        $request->validate([
            'fullName' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'email' => 'required|email'
        ]);
        $email = new Email();
        $email->fullName = $request->fullName;
        $email->email = $request->email;
        $email->subject = $request->subject;
        $email->message = $request->message;
        $email->save();
    }
    public function destroy($id){
        $email = Email::find($id);
        if (!$email) {
            return response()->json(['message' => 'cannot delete undefined email!'], 404);
        }
        $email->delete();
        return response()->json(['message' => 'Email de stage supprimée avec succès']);
    }
}
