<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use Illuminate\Http\Request;

class CommunicationController extends Controller {
    public function all() {
        return Communication::orderBy('date_time', 'desc')->paginate(10);
    }
}