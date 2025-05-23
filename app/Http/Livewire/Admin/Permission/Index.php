<?php

namespace App\Http\Livewire\Admin\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $paginationOptions = [5, 10, 25, 50];
    public $search = '';
    public $sortField = 'title'; // Use 'title' instead of 'name'
    public $sortDirection = 'asc';
    public $selected = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirm($action, $permissionId = null)
    {
        if ($action === 'delete' && $permissionId) {
            Permission::find($permissionId)->delete();
            $this->dispatchBrowserEvent('alert', ['message' => 'Permission deleted successfully!']);
        } elseif ($action === 'deleteSelected') {
            Permission::whereIn('id', $this->selected)->delete();
            $this->selected = [];
            $this->dispatchBrowserEvent('alert', ['message' => 'Selected permissions deleted successfully!']);
        }
    }

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function render()
    {
        $permissions = Permission::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.permission.index', [
            'permissions' => $permissions,
        ]);
    }
}
