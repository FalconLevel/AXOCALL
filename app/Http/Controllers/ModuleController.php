<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public $data;
    public function __construct() {
        $this->data = [];
    }

    public function dashboard(){
        $this->data['title'] = 'Dashboard Overview';
        $this->data['description'] = "Welcome back! Here's what's happening with your communications.";
        $this->data['panel_type'] = 'dashboard';
        return view('pages.admin.dashboard', $this->data);
    }

    public function communications(){
        $this->data['title'] = 'Communications';
        $this->data['description'] = 'Manage all your call logs and SMS messages in one place.';
        $this->data['panel_type'] = 'communications';
        return view('pages.admin.communications', $this->data);
    }

    public function contacts(){
        $this->data['title'] = 'Contacts';
        $this->data['description'] = 'Manage your contacts, extensions, and caller information.';
        $this->data['panel_type'] = 'contacts';
        return view('pages.admin.contacts', $this->data);
    }

    public function extensions(){
        $this->data['title'] = 'Extensions';
        $this->data['description'] = 'Browse, add, or edit extensions in your directory.';
        $this->data['panel_type'] = 'extensions';
        return view('pages.admin.extensions', $this->data);
    }

    public function follow_ups(){
        $this->data['title'] = 'Follow Ups';
        $this->data['description'] = 'Manage your flagged calls and SMS messages that need follow-up';
        $this->data['panel_type'] = 'follow_ups';
        return view('pages.admin.follow_ups', $this->data);
    }
}