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

    public $createSubcategory;

    public $listeners = ['createSubcategory'];

    public $subcategory;

    public $image;

    public array $rules = [
        'subcategory.name'        => ['required', 'string', 'max:255'],
        'subcategory.category_id' => ['nullable', 'integer'],
        'subcategory.language_id' => ['nullable'],
        'image'                   => ['nullable', 'file', 'image', 'max:2048'], // max 2MB image file
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
        if ($this->image) {
            info('Image upload exists:', ['image' => $this->image]);
        } else {
            info('No image uploaded');
        }
        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->subcategory->name).'-'.Str::random(3).'.'.$this->image->extension();
            $path = $this->image->storeAs('subcategories', $imageName, 'public'); // store once, in 'public'
            $this->subcategory->image = $path; // Save the relative path to DB
        }


        $this->subcategory->slug = Str::slug($this->subcategory->name);

        $this->subcategory->save();
        // dd($this->subcategory);

        $this->alert('success', __('Subcategory created successfully.'));

        $this->emit('refreshIndex');

        $this->createSubcategory = false;
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
