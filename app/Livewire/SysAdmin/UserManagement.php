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
        $user->syncRoles($this->role);

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
        $roles = Role::all();

        return view('livewire.sys-admin.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->extends('layouts.main');
    }
}
