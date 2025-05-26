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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
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

        if ($this->image instanceof \Livewire\TemporaryUploadedFile && $this->image->isValid()) {
            $imageName = Str::slug($this->subcategory->name) . '-' . Str::random(6) . '.webp';

            try {
                // Use Intervention Image to encode as webp
                $image = Image::make($this->image->get())->encode('webp', 85);

                $path = 'subcategories/' . $imageName;

                // Store the encoded image to the 'public' disk
                Storage::disk('public')->put($path, $image);

                $this->subcategory->image = $path;

                Log::info('Image processed and stored', ['path' => $path]);

            } catch (\Exception $e) {
                Log::error('Failed to process/store image', ['error' => $e->getMessage()]);
                $this->alert('error', __('Failed to process image.'));
                return;
            }
        }

        $this->subcategory->save();

        $this->alert('success', __('Subcategory updated successfully.'));
        $this->emit('refreshIndex');

        // Reset the modal
        $this->editSubcategory = false;
        $this->subcategory = null;
        $this->image = null;
    }
}
