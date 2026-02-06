<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class LandingPage extends Component
{
    public function getStarted()
    {
        if (Route::has('register')) {
            return redirect()->route('register');
        }
        
        if (Route::has('login')) {
            return redirect()->route('login');
        }
        
        return auth()->check() 
            ? redirect()->route('dashboard') 
            : redirect()->route('home');
    }

    public function aboutUs(): void
    {
        session()->flash('message', 'About Us page coming soon!');
    }

    public function render(): View
    {
        return view('livewire.landing-page');
    }
}
