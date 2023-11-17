<?php

namespace App\Livewire\SysAdmin;

use App\Models\User;
use App\Services\CachecClearService;
use App\Services\General\PopupService;
use GuzzleHttp\Client;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use WireUi\Traits\Actions;

class UserManagement extends Component
{
    use Actions;

    public $openModal = false;
    public $modalMethod;

    public $role = [];

    protected $popupService;
    protected $cacheClearService;

    public function __construct()
    {
        $this->popupService = app(PopupService::class);
        $this->cacheClearService = app(CachecClearService::class);
    }

    public function assign($id)
    {
        $user = User::whereId($id)->first();
        $this->openModal = true;
        $this->modalMethod = "save({$id})";
        $this->role = $user->roles->pluck('name')->toArray();
    }

    public function save($id)
    {
        $user = User::whereId($id)->first();
        // $user->syncRoles($this->role);
        $clientId = $user->client_id;

        // Retrieve the role by its name and client ID
        $role = Role::where('name', $this->role)->first();

        // First, detach all roles for this client
        $currentRoles = $user->roles()->wherePivot('client_id', $clientId)->get();
        foreach ($currentRoles as $currentRole) {
            $user->removeRole($currentRole);
        }

        // Then, assign the new role for this client
        if ($role) {
            // Use syncRoles to manually attach the role with extra pivot column
            $user->roles()->sync([$role->id => ['client_id' => $clientId]]);
        }

        // webhook to clear cache on all system that been oversee
        $urls = [
            'http://127.0.0.1:9000/webhook/clear-cache'
            // ,'http://127.0.0.1:9001/webhook/clear-cache'
        ]; // change to use url of siskop/fms

        foreach ($urls as $url) {
            $this->cacheClearService->clearCache($url);
        }

        $this->openModal = false;
        $this->dialog()->success('Assign Successful', 'Role assigning to this user is successful.');
    }

    public function render()
    {
        $users = User::where('user_type', 2)->paginate(10);
        $roles = Role::whereNull('client_id')->get();

        return view('livewire.sys-admin.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->extends('layouts.main');
    }
}
