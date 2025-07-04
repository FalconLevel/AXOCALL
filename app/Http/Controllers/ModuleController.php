<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use App\Models\Contact;
use App\Models\Extension;
use App\Models\Message;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public $data;
    public function __construct() {
        $this->data = [];
    }

    public function dashboard() {
        $this->data['title'] = 'Dashboard Overview';
        $this->data['description'] = "Welcome back! Here's what's happening with your communications.";
        $this->data['panel_type'] = 'dashboard';
        $this->data['dashboard_data'] = globalHelper()->getDashboardData();

        return view('pages.admin.dashboard', $this->data);
    }

    public function communications() {
        $this->data['title'] = 'Communications';
        $this->data['description'] = 'Manage all your call logs and SMS messages in one place.';
        $this->data['panel_type'] = 'communications';
        $this->data['communications'] = globalHelper()->getCommunicationData();
        $this->data['messages'] = globalHelper()->getMessageData();
        return view('pages.admin.communications', $this->data);
    }

    public function contacts() {
        $this->data['title'] = 'Contacts';
        $this->data['description'] = 'Manage your contacts, extensions, and caller information.';
        $this->data['panel_type'] = 'contacts';
        $this->data['tags'] = globalHelper()->getTags();
        return view('pages.admin.contacts', $this->data);
    }

    public function extensions() {
        $this->data['title'] = 'Extensions';
        $this->data['description'] = 'Browse, add, or edit extensions in your directory.';
        $this->data['panel_type'] = 'extensions';
        $this->data['contacts'] = Contact::all();
        return view('pages.admin.extensions', $this->data);
    }

    public function follow_ups() {
        $this->data['title'] = 'Follow Ups';
        $this->data['description'] = 'Manage your flagged calls and SMS messages that need follow-up';
        $this->data['panel_type'] = 'follow_ups';
        $this->data['communications'] = Communication::where('category', 'follow-up')
        ->orderBy('date_time', 'desc')->with('transcriptions', 'contact_from', 'contact_to')->get();
        
        $this->data['messages'] = Message::where('category', 'follow-up')
        ->orderBy('date_sent', 'desc')->with('contact_from', 'contact_to')->get();
        
        return view('pages.admin.follow_ups', $this->data);
    }

    public function settings() {
        $this->data['title'] = 'Application Settings';
        $this->data['description'] = 'Customize and manage your AXOCALL system preferences.';
        $this->data['panel_type'] = 'settings';
        return view('pages.admin.maintenance.settings', $this->data);
    }

    public function profile() {
        $this->data['title'] = 'Profile';
        $this->data['description'] = 'Manage your profile and preferences.';
        $this->data['panel_type'] = 'profile';
        return view('pages.admin.maintenance.profile', $this->data);
    }

}