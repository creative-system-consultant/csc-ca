<?php

use App\Livewire\SysAdmin\Role;
use App\Livewire\SysAdmin\EditRole;


Route::get('/index', Role::class)->name('index');
Route::get('/edit-role/{id}', EditRole::class)->name('edit');