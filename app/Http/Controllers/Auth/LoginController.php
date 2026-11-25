<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers; // @phpstan-ignore-line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers; // @phpstan-ignore-line
    
    protected $redirectTo = '/dashboard';
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    protected function authenticated(Request $request, $user)
    {
        // Tambahkan log atau redirect custom jika perlu
        // Log: User login
        
        return redirect()->route('dashboard');
    }
    
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Log: User logout
        
        return redirect('/');
    }
}