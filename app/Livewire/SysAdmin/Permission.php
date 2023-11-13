<?php

namespace App\Livewire\SysAdmin;

use App\Models\Ref\System;
use App\Models\Ref\SystemModule;
use App\Services\General\PopupService;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission as ModelsPermission;
use WireUi\Traits\Actions;

class Permission extends Component
{
    use Actions;

    public $openModal = false;
    public $modalTitle;
    public $modalDescription;
    public $modalMethod;
    public $search = '';

    #[Rule('required')]
    public $system;

    #[Rule('required')]
    public $module;

    #[Rule('required')]
    public $name;

    protected $popupService;

    public function __construct()
    {
        $this->popupService = app(PopupService::class);
    }

    private function setupModal($method, $title, $description, $actualMethod = null)
    {
        $this->openModal = true;
        $this->modalTitle = $title;
        $this->modalDescription = $description;
        $this->modalMethod = $actualMethod ?? $method;
    }

    public function add()
    {
        $this->setupModal("create", "Create Permission", "Permission Name");
    }

    public function create()
    {
        $this->validate();

        ModelsPermission::create([
            'name' => strtolower($this->name),
            'system_id' => $this->system,
            'module_id' => $this->module
        ]);

        $this->reset('name', 'system', 'module');
        $this->openModal = false;

        $this->dialog()->success('Success!', 'Permission Created Successfully');
    }

    public function edit($id)
    {
        $permission = ModelsPermission::whereId($id)->first();
        $this->name = $permission->name;
        $this->system = $permission->system_id;
        $this->module = $permission->module_id;
        $this->setupModal("update", "Update Permission", "Permission Name", "update({$id})");
    }

    public function update($id)
    {
        $this->validate();

        ModelsPermission::whereId($id)->update([
            'name' => strtolower($this->name),
            'system_id' => $this->system,
            'module_id' => $this->module
        ]);

        $this->reset('name');
        $this->openModal = false;

        $this->dialog()->success('Success!', 'Permission Successfully Updated.');
    }

    public function delete($id)
    {
        $this->popupService->confirm($this, 'ConfirmDelete', 'Delete the permission?', 'Are you sure you want to delete the permission?', $id);
    }

    public function ConfirmDelete($id)
    {
        ModelsPermission::whereId($id)->delete();
        $this->dialog()->success('Success!', 'Permission Successfully Deleted.');
    }

    public function render()
    {
        $systems = System::all();
        $modules = SystemModule::where('system_id', $this->system)->get();
        $permissions = ModelsPermission::where('name', 'like', '%' . $this->search . '%')->get();

        return view('livewire.sys-admin.permission', [
            'systems' => $systems,
            'modules' => $modules,
            'permissions' => $permissions
        ])->extends('layouts.main');
    }
}
