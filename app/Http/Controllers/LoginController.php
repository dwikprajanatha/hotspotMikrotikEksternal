<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function index(Request $request)
    {
        return view('hotspot/login', ['request' => $request->all()]);
    }


    public function create(Request $request)
    {
        return view('hotspot/register',['request' => $request->all()]);
    }

    public function daftar(Request $request)
    {
        dd($request->all());
    }

}
