<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Subcategory;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Facades\Storage; // Add this import

class Edit extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $editModal = false;

    public $subcategory;

    public $image;

    public $listeners = [
        'editModal',
    ];

    public array $rules = [
        'subcategory.name'        => ['required', 'string', 'max:255'],
        'subcategory.category_id' => ['nullable', 'integer'],
        'subcategory.language_id' => ['nullable'],
        'subcategory.slug'        => ['required'],
    ];

    public function editModal($subcategory)
    {
        abort_if(Gate::denies('subcategory_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->subcategory = Subcategory::findOrFail($subcategory);

        $this->editModal = true;
    }

    public function update()
    {
        abort_if(Gate::denies('subcategory_update'), 403);

        $this->validate();

        if ($this->image) {
            $imageName = Str::slug($this->subcategory->name).'-'.Str::random(3).'.'.$this->image->extension();

            $img = Image::make($this->image->getRealPath())->encode('webp', 85);

            $img->stream();

            Storage::disk('local_files')->put('subcategories/'.$imageName, $img, 'public');

            // Removed $this->brand->image = $imageName; as it seems to be a mistake
            $this->subcategory->image = $imageName;
        }

        $this->subcategory->save();

        $this->alert('success', __('Subcategory updated successfully'));

        $this->emit('refreshIndex');

        $this->editModal = false;
    }

    public function getCategoriesProperty()
    {
        return Category::select('name', 'id')->get();
    }

    public function getLanguagesProperty()
    {
        return Language::select('name', 'id')->get();
    }

    public function render(): View
    {
        return view('livewire.admin.subcategory.edit');
    }
}
