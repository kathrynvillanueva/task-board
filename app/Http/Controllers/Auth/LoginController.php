<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use Auth;
use Hash;
use Session;

use App\Employee;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct () {
      $this->middleware('guest')->except('logout');
    }

    public function index () {
      return view('welcome');
    }

    public function logout(Request $request) {
      Auth::guard('web')->logout();
      $sessionKey = Auth::guard('web')->getName();
      $request->session()->forget($sessionKey);
      $request->session()->regenerate();
      return redirect('/');
    }
}