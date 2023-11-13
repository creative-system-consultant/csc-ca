<?php

namespace App\Livewire\SysAdmin;

use App\Models\Ref\System;
use App\Models\Ref\SystemModule;
use App\Services\General\PopupService;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;
use WireUi\Traits\Actions;

class Role extends Component
{
    use Actions;

    public $openModal = false;
    public $modalTitle;
    public $modalDescription;
    public $modalMethod;
    public $search = '';

    #[Rule('required')]
    public $name;

    public $selectedPermission = [];

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
        $this->setupModal("create", "Create Role", "Role Name");
    }

    public function create()
    {
        $this->validate();

        $id = ModelsRole::create([
            'name' => strtolower($this->name)
        ])->id;

        $role = ModelsRole::find($id);
        $role->syncPermissions($this->selectedPermission);

        $this->reset('name', 'selectedPermission');
        $this->openModal = false;

        $this->dialog()->success('Success!', 'Role Created Successfully');
    }

    public function edit($id)
    {
        $role = ModelsRole::whereId($id)->first();
        $this->name = $role->name;
        $this->selectedPermission = $role->permissions->pluck('name')->toArray();
        $this->setupModal("update", "Update Role", "Role Name", "update({$id})");
    }

    public function update($id)
    {
        $this->validate();

        $role = ModelsRole::whereId($id)->first();

        $role->update([
            'name' => strtolower($this->name)
        ]);

        $role->syncPermissions($this->selectedPermission);

        $this->reset('name', 'selectedPermission');
        $this->openModal = false;

        $this->dialog()->success('Success!', 'Role Successfully Updated.');
    }

    public function delete($id)
    {
        $this->popupService->confirm($this, 'ConfirmDelete', 'Delete the role?', 'Are you sure you want to delete the role?', $id);
    }

    public function ConfirmDelete($id)
    {
        ModelsRole::whereId($id)->delete();
        $this->dialog()->success('Success!', 'Role Successfully Deleted.');
    }

    public function render()
    {
        $roles = ModelsRole::paginate(10);
        $systems = System::all();
        $modules = SystemModule::where('description', 'like', '%' . $this->search . '%')->get();
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')->get();

        return view('livewire.sys-admin.role',[
            'roles' => $roles,
            'permissions' => $permissions,
            'systems' => $systems,
            'modules' => $modules,
        ])->extends('layouts.main');
    }
}
