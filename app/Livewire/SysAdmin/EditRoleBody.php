<?php

namespace App\Livewire\SysAdmin;

use App\Models\Ref\System;
use App\Models\Ref\SystemModule;
use App\Services\CachecClearService;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use WireUi\Traits\Actions;

class EditRoleBody extends Component
{
    use Actions;

    public $selectedSystem = false;
    public $role;
    public $name;
    public $currentSystem;
    public $currentSystemData;
    public $modules;
    public $permissions;
    public $selectedPermission = [];
    public $selectedModule = [];
    public $allModules = [];
    public $allPermissions = [];

    protected $cacheClearService;

    public function __construct()
    {
        $this->cacheClearService = app(CachecClearService::class);
    }

    public function mount($systemId, $roleId, $name)
    {
        $this->updateSystemData($systemId);
        $this->role = Role::find($roleId);
        $this->name = $name;

        // Cache all modules and permissions
        $this->allModules = SystemModule::whereSystemId($this->currentSystem)->pluck('id')->toArray();
        $this->allPermissions = Permission::where('system_id', $this->currentSystem)->pluck('id')->toArray();

        $this->selectedPermission = $this->role->permissions->where('system_id', $this->currentSystem)->pluck('id')->toArray();
        $this->modules = SystemModule::whereSystemId($this->currentSystem)->get();
        $this->permissions = Permission::all();

        // Iterate over each module and check if the role has all permissions of the module
        foreach ($this->modules as $module) {
            $modulePermissions = Permission::where('system_id', $this->currentSystem)->where('module_id', $module->id)->pluck('id')->toArray();
            if (!array_diff($modulePermissions, $this->selectedPermission) && count($modulePermissions) > 0) {
                $this->selectedModule[] = $module->id;
            }
        }

        // Check if all modules and permissions in the system are selected
        $allModules = SystemModule::whereSystemId($this->currentSystem)->pluck('id')->toArray();
        $allPermissions = Permission::where('system_id', $this->currentSystem)->pluck('id')->toArray();

        $this->selectedSystem = !array_diff($allModules, $this->selectedModule) && !array_diff($allPermissions, $this->selectedPermission);
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'currentSystem') {
            $this->updateSystemData($this->currentSystem);
        }
    }

    private function updateSystemSelection()
    {
        $this->selectedSystem = !array_diff($this->allModules, $this->selectedModule) && !array_diff($this->allPermissions, $this->selectedPermission);
    }

    public function updatedSelectedSystem($value)
    {
        if ($value) {
            // Select all modules and permissions
            $this->selectedModule = SystemModule::whereSystemId($this->currentSystem)->pluck('id')->toArray();
            $this->selectedPermission = Permission::where('system_id', $this->currentSystem)->pluck('id')->toArray();
        } else {
            // Deselect all modules and permissions
            $this->selectedModule = [];
            $this->selectedPermission = [];
        }
    }

    public function updatedSelectedModule()
    {
        foreach ($this->selectedModule as $moduleId) {
            $moduleId = (int) $moduleId; // Cast to integer
            $modulePermissions = Permission::where('module_id', $moduleId)->pluck('id')->toArray();
            $this->selectedPermission = array_merge($this->selectedPermission, $modulePermissions);
        }

        $this->selectedPermission = array_values(array_unique($this->selectedPermission));

        // Update selectedSystem status
        $this->updateSystemSelection();
    }

    public function updatedSelectedPermission()
    {
        $allModulePermissions = Permission::whereIn('module_id', $this->modules->pluck('id'))->get()->groupBy('module_id');

        $this->selectedModule = [];
        foreach ($this->modules as $module) {
            $modulePermissions = $allModulePermissions[$module->id] ?? collect();
            if ($modulePermissions->pluck('id')->diff($this->selectedPermission)->isEmpty()) {
                $this->selectedModule[] = $module->id;
            }
        }

        // Cast permission IDs to integers
        $this->selectedPermission = array_map('intval', $this->selectedPermission);

        // Update selectedSystem status
        $this->updateSystemSelection();
    }

    private function updateSystemData($systemId)
    {
        $this->currentSystem = $systemId;
        $this->currentSystemData = System::find($this->currentSystem);
        $this->modules = SystemModule::whereSystemId($this->currentSystem)->get();
        $this->permissions = Permission::all();
    }

    #[On('updateRole')]
    public function update()
    {
        $this->role->update([
            'name' => strtolower($this->name),
            'client_id' => auth()->user()->client_id,
            'created_by' => auth()->id()
        ]);

        // Revoke all current permissions
        $this->role->revokePermissionTo($this->role->permissions);

        // Assign new permissions
        $this->role->givePermissionTo($this->selectedPermission);

        // webhook to clear cache on all system that been oversee
        $urls = [
            'http://127.0.0.1:9000/webhook/clear-cache'
            // ,'http://127.0.0.1:9001/webhook/clear-cache'
        ]; // change to use url of siskop/fms

        foreach ($urls as $url) {
            $this->cacheClearService->clearCache($url);
        }

        $this->notification()->success('Success!', 'Role Successfully Updated.');
    }

    public function render()
    {
        return view('livewire.sys-admin.edit-role-body');
    }
}
