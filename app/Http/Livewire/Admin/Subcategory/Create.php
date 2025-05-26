<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Subcategory;

use App\Models\Category;
use App\Models\Language;
use App\Models\Subcategory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        'image'                   => ['nullable', 'image', 'max:2048'],
    ];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('subcategory_create'), 403);

        return view('livewire.admin.subcategory.create', [
            'categories' => $this->categories,
            'languages' => $this->languages,
        ]);
    }

    public function createSubcategory(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $this->subcategory = new Subcategory();
        $this->image = null;
        $this->createSubcategory = true;
    }

    public function create(): void
    {
        $this->validate();

        Log::info('Create method called', [
            'image_exists' => !empty($this->image),
            'image_type' => is_object($this->image) ? get_class($this->image) : 'null',
            'image_valid' => $this->image instanceof \Livewire\TemporaryUploadedFile ? $this->image->isValid() : false,
        ]);

        $this->subcategory->slug = Str::slug($this->subcategory->name);

        if ($this->image instanceof \Livewire\TemporaryUploadedFile && $this->image->isValid()) {
            $imageName = Str::slug($this->subcategory->name) . '-' . Str::random(6) . '.' . $this->image->getClientOriginalExtension();
            try {
                $path = $this->image->storeAs('subcategories', $imageName, 'public');
                $this->subcategory->image = $path;
                Log::info('Image stored successfully', ['path' => $path]);
            } catch (\Exception $e) {
                Log::error('Failed to store image', ['error' => $e->getMessage()]);
                $this->alert('error', __('Failed to store image.'));
                return;
            }
        } else {
            Log::info('No valid image uploaded');
        }

        $this->subcategory->save();

        $this->alert('success', __('Subcategory created successfully.'));
        $this->emit('refreshIndex');

        $this->createSubcategory = false;
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
