<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Ref\RefBank;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.home.home',[
           
        ])->extends('layouts.main');
    }
}
