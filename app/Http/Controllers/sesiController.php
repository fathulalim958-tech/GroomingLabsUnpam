<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class sesiController extends Controller
{
    function index(){
        return view('login');
    }

    function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'admin') {
                return redirect('dashboard/admin');
            } elseif (Auth::user()->role == 'kasir') {
                return redirect('dashboard/kasir');
            } elseif (Auth::user()->role == 'pelanggan') {
                return redirect('dashboard/pelanggan');
            }
        }
        else{
            return back()->withErrors(['email' => 'Email atau password yang anda masukan salah'])-> withInput();
        }

    }
    function logout(){
        Auth::logout();
        return redirect('/');
    }
}
