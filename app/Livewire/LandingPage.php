<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class LandingPage extends Component
{
    public function getStarted()
    {
        // Redirect to registration or login page
        // Adjust the route name based on your authentication setup
        if (Route::has('register')) {
            return redirect()->route('register');
        } elseif (Route::has('login')) {
            return redirect()->route('login');
        }
        
        // Fallback to dashboard if authenticated, or home
        return auth()->check() 
            ? redirect()->route('dashboard') 
            : redirect()->route('home');
    }

    public function aboutUs()
    {
        // You can redirect to an about page or emit an event
        // For now, we'll show a flash message
        session()->flash('message', 'About Us page coming soon!');
        
        // If you have an about route, uncomment this:
        // return redirect()->route('about');
    }

    public function render()
    {
        return view('livewire.landing-page');
    }
}
