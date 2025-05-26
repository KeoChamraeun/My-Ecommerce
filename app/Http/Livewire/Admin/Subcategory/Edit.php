<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Subcategory;

use App\Models\Category;
use App\Models\Language;
use App\Models\Subcategory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editSubcategory = false;
    public $listeners = ['editModal'];

    public ?Subcategory $subcategory;
    public $image;

    protected array $rules = [
        'subcategory.name'        => ['required', 'string', 'max:255'],
        'subcategory.category_id' => ['nullable', 'integer'],
        'subcategory.language_id' => ['nullable', 'integer'],
        'image'                   => ['nullable', 'image', 'max:2048'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('subcategory_edit'), 403);

        return view('livewire.admin.subcategory.edit', [
            'categories' => Category::select('name', 'id')->get(),
            'languages' => Language::select('name', 'id')->get(),
        ]);
    }

    public function editModal($subcategoryId): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->subcategory = Subcategory::findOrFail($subcategoryId);
        $this->image = null;
        $this->editSubcategory = true;
    }

    public function update(): void
    {
        $this->validate();

        $this->subcategory->slug = Str::slug($this->subcategory->name);

        if ($this->image && $this->image->isValid()) {
            $imageName = Str::slug($this->subcategory->name) . '-' . Str::random(6) . '.' . $this->image->getClientOriginalExtension();
            $path = $this->image->storeAs('subcategories', $imageName, 'public');
            $this->subcategory->image = $path;
        }

        $this->subcategory->save();

        $this->alert('success', __('Subcategory updated successfully.'));
        $this->emit('refreshIndex');

        $this->editSubcategory = false;
        $this->subcategory = null;
        $this->image = null;
    }
}
