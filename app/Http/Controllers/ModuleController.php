<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function dashboard(){
        return view('pages.admin.dashboard');
    }

    public function communications(){
        return view('pages.admin.communications');
    }

    public function contacts(){
        return view('pages.admin.contacts');
    }

    public function extensions(){
        return view('pages.admin.extensions');
    }

    public function follow_ups(){
        return view('pages.admin.follow_ups');
    }
}