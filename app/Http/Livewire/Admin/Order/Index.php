<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Order;

use App\Http\Livewire\WithSorting;
use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;

    public int $perPage;

    public $status;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions = [10, 25, 50, 100];

    public array $listsForFields = [];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function resetSelected(): void
    {
        $this->selected = [];
    }

    public function mount(): void
    {
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 25;
        $this->paginationOptions = [25, 50, 100];
        $this->orderable = (new Order())->orderable ?? ['id', 'status', 'total_qty', 'total_cost'];
    }

    public function render(): View|Factory
    {
        $query = Order::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $orders = $query->paginate($this->perPage);

        return view('livewire.admin.order.index', [
            'orders' => $orders,
            'paginationOptions' => $this->paginationOptions,
        ]);
    }
}
