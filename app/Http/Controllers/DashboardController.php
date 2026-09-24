<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // function index(){
    //     echo "hallo selamat datang " . Auth::user()->name;
    //     echo "<br>";
    //     echo "<a href='logout'>Logout</a>";
    // }
    function pelanggan(){
        return view('pelanggan.Dashboard');
    }
    function kasir(){
        return view('kasir.Dashboard');
    }
    function admin(){
        return view('admin.Dashboard');
    }

    function barbershop(){
        return view('pelanggan.Barbershop');
    }
    function MUA(){
        return view('pelanggan.MUA');
    }

    function booking(){
        return view('pelanggan.Booking');
    }
}
