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
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $selected = [];
    public $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'title'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = ['delete', 'deleteSelected'];

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

    public function confirmDelete($action, $id = null)
    {
        // Emit event to JS to open SweetAlert confirm popup
        $this->deleteId = $id;
        $this->emit('confirmDelete', $action, $id);
    }

    public function delete()
    {
        if ($this->deleteId) {
            Permission::findOrFail($this->deleteId)->delete();
            $this->deleteId = null;
            session()->flash('message', __('Permission deleted successfully!'));
            $this->selected = [];
        }
    }

    public function deleteSelected()
    {
        Permission::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        session()->flash('message', __('Selected permissions deleted successfully!'));
    }

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function render()
    {
        $permissions = Permission::query()
            ->when($this->search, fn($query) =>
                $query->where('title', 'like', '%' . $this->search . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.permission.index', [
            'permissions' => $permissions,
            'sorts' => [$this->sortField => $this->sortDirection],
            'selectedCount' => $this->selectedCount,  // <--- pass this here
        ]);
    }
}
