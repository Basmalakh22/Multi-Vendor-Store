<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct(){
        // $this->middleware(middleware: ['auth']);
    }

    public function index(){

        $user = Auth::user();
        return view('dashboard.index',[
            'user' => 'Basmala Khaled'
        ]);
    }
}
