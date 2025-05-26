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

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $createSubcategory = false;

    public $listeners = ['createSubcategory'];

    public ?Subcategory $subcategory = null;

    public $image;

    protected array $rules = [
        'subcategory.name'        => ['required', 'string', 'max:255'],
        'subcategory.category_id' => ['nullable', 'integer'],
        'subcategory.language_id' => ['nullable', 'integer'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('subcategory_create'), 403);

        return view('livewire.admin.subcategory.create');
    }

    public function createSubcategory()
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->subcategory = new Subcategory();

        $this->createSubcategory = true;
    }

    public function create()
    {
        $this->validate();

        if ($this->image) {
            // Store the image in the 'public' disk under 'subcategories' folder
            $imageName = Str::slug($this->subcategory->name) . '-' . Str::random(6) . '.' . $this->image->extension();
            $this->image->storeAs('subcategories', $imageName, 'public');
            $this->subcategory->image = $imageName;
        }

        $this->subcategory->slug = Str::slug($this->subcategory->name);

        $this->subcategory->save();

        $this->alert('success', __('Subcategory created successfully.'));

        $this->emit('refreshIndex');

        $this->createSubcategory = false;

        // Reset form
        $this->subcategory = null;
        $this->image = null;
    }

    public function getCategoriesProperty()
    {
        return Category::select('name', 'id')->get();
    }

    public function getLanguagesProperty()
    {
        return Language::select('name', 'id')->get();
    }
}
