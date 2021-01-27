<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class formController extends Controller
{
    public function goForm(Request $request){
        return view("form");
    }

    public function goResults(Request $request){
        /*$validateForm = $request->validate([
            'email' => 'required|email',
            'nif' => 'required|regex:/^\d{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/',
            'archivo' => 'file|max:1024',
            'imagen' => 'image|dimensions:min_width=1920,min_height=1080'
        ]);*/
        $validator = $request-> validate([
            'email' => 'required|exists:users',
            'nif' =>'required|regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i',
            'archivo'=>'required|file|max:1024',
            "imagen"  => "required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:min_width=1920,min_height=1080"
        ]);

        $archivo = $request->file('archivo');
        $imagen = $request->file('imagen');
        
        $nameF = time().$archivo->getClientOriginalName();
        $nameI = time().$imagen->getClientOriginalName();

        $routeFile->move(public_path().'/files/',$nameF);
        $rotueImage->move(public_path().'/imgs/',$nameI);
        
        $msg["email"]=$request->input("email"); 
        $msg["nif"]=$request->input("nif"); 
        $msg["file"]=$nameF; 
        $msg["image"]=$nameI;
        
        return view("result",$msg);
    }
}
