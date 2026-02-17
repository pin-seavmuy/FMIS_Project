<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserService;
use App\Traits\IncomeTrait;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListUser extends BaseComponent
{
    use IncomeTrait;

    public string $userName = '';
    public string $userEmail = '';
    public string $userPassword = '';
    public $userId = null;
    public bool $isEditMode = false;
    public string $search = '';
    public $viewUser = null;

    protected $listeners = [
        'view-user' => 'viewUser',
        'edit-user' => 'editUser',
        'confirm-delete' => 'confirmDelete',
        'delete-user' => 'deleteUser'
    ];

    public function mount(UserService $service)
    {
        $this->datas = $this->UserReport($service);
    }

    public function updatedSearch(UserService $service)
    {
        $this->datas = $this->UserReport($service, $this->search);
        $this->dispatch('users-updated', data: (object) $this->datas->toArray());
    }

    public function confirmDelete($id)
    {
        $this->userId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function createUser(UserService $service)
    {
        $this->validate([
            'userName' => 'required|min:2',
            'userEmail' => 'required|email|unique:users,email',
            'userPassword' => 'required|min:6',
        ]);

        $service->create([
            'name' => $this->userName,
            'email' => $this->userEmail,
            'password' => $this->userPassword,
        ]);

        $createdName = $this->userName;
        $this->resetForm();
        $this->datas = $this->UserReport($service);

        $this->dispatch('users-updated', data: (object) $this->datas->toArray());
        $this->dispatch('user-created', name: $createdName);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal');
    }

    public function viewUser($id, UserService $service)
    {
        $user = $service->find($id);
        if ($user) {
            $this->viewUser = $user;
            $this->dispatch('open-view-modal');
        }
    }

    public function editUser($id, UserService $service)
    {
        $user = $service->find($id);
        if ($user) {
            $this->userId = $user->id;
            $this->userName = $user->name;
            $this->userEmail = $user->email;
            $this->userPassword = ''; // Don't populate password
            $this->isEditMode = true;
            $this->dispatch('open-modal');
        }
    }

    public function updateUser(UserService $service)
    {
        $this->validate([
            'userName' => 'required|min:2',
            'userEmail' => 'required|email|unique:users,email,' . $this->userId,
            'userPassword' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $this->userName,
            'email' => $this->userEmail,
        ];

        if (!empty($this->userPassword)) {
            $data['password'] = $this->userPassword;
        }

        $service->update($this->userId, $data);

        $this->resetForm();
        $this->datas = $this->UserReport($service);

        $this->dispatch('users-updated', data: (object) $this->datas->toArray());
        $this->dispatch('user-updated'); // New event for success message
    }

    public function deleteUser($id, UserService $service)
    {
        $service->delete($id);
        $this->datas = $this->UserReport($service);
        $this->dispatch('users-updated', data: (object) $this->datas->toArray());
        $this->dispatch('user-deleted'); // Success message event
    }

    private function resetForm()
    {
        $this->userName = '';
        $this->userEmail = '';
        $this->userPassword = '';
        $this->userId = null;
        $this->isEditMode = false;
    }

    public function getColumns()
    {
        return [
            ['headerName' => '#', 'valueGetter' => 'node.rowIndex + 1', 'maxWidth' => 60, 'sortable' => false, 'filter' => false],
            ['field' => 'name', 'headerName' => 'Name', 'flex' => 1, 'minWidth' => 150, 'filter' => 'agTextColumnFilter'],
            ['field' => 'email', 'headerName' => 'Email', 'flex' => 1, 'minWidth' => 200, 'filter' => 'agTextColumnFilter'],
            [
                'field' => 'email_verified_at',
                'headerName' => 'Verified',
                'maxWidth' => 110,
                'filter' => 'agDateColumnFilter',
                'valueFormatter' => 'FmisFormatters.date'
            ],
            [
                'field' => 'created_at',
                'headerName' => 'Created',
                'maxWidth' => 120,
                'filter' => 'agDateColumnFilter',
                'valueFormatter' => 'FmisFormatters.date'
            ],
            [
                'headerName' => 'Actions',
                'cellRenderer' => 'FmisRenderers.actions',
                'width' => 140,
                'sortable' => false,
                'filter' => false,
                'cellStyle' => ['display' => 'flex', 'alignItems' => 'center', 'justifyContent' => 'center']
            ]
        ];
    }

    public function render()
    {
        return view('livewire.listUser', [
            'columns' => $this->getColumns()
        ]);
    }
}