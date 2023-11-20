<?php

namespace App\Livewire\SysAdmin;

use Livewire\Component;

class EditRole extends Component
{
    public $setIndex = 0;


    public function setState($index)
    {
        $this->setIndex = $index;
    }
    
    public function render()
    {
        
        return view('livewire.sys-admin.edit-role')->extends('layouts.main');
    }
}
