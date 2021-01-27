<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sValController extends Controller
{
    public function goForm(Request $request){
        return view("form");
    }

    public function getForm(Request $request){

        $validateData = $request->validate([
            'email' => 'required|exists:users|email'
        ]);

        $data["email"]=$request->input("email");

        return view("respond",$data);
        
    }
}
