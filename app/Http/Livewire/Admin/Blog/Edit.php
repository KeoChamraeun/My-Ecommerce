<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Blog;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Language;
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

    public bool $editModal = false;

    public $image;

    public $blog;

    public array $categories = [];

    public array $languages = [];

    public $listeners = ['editModal'];

    protected $rules = [
        'blog.title' => 'required|string|min:3|max:255',
        'blog.category_id' => 'required|integer|exists:blog_categories,id',
        'blog.slug' => 'required|string|max:255',
        'blog.details' => 'required|string|min:3',
        'blog.language_id' => 'nullable|integer|exists:languages,id',
        'blog.meta_title' => 'nullable|string|max:100',
        'blog.meta_desc' => 'nullable|string|max:200',
        'image' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function mount()
    {
        $this->categories = BlogCategory::pluck('title', 'id')->toArray();
        $this->languages = Language::pluck('name', 'id')->toArray();
    }

    public function editModal($id)
    {
        try {
            // abort_if(Gate::denies('blog_edit'), 403); // Uncomment after testing
            $this->resetErrorBag();
            $this->resetValidation();
            $this->blog = Blog::where('id', $id)->firstOrFail();
            $this->editModal = true;
            \Log::info("Edit modal opened for blog ID: {$id}");
        } catch (\Exception $e) {
            \Log::error("Failed to load blog ID {$id}: {$e->getMessage()}");
            $this->alert('error', __('Unable to load blog for editing.'));
        }
    }

    public function update()
    {
        try {
            $this->validate();

            if ($this->image) {
                $imageName = Str::slug($this->blog->title) . '-' . time() . '.' . $this->image->extension();
                $this->image->storeAs('blogs', $imageName, 'public');
                $this->blog->photo = $imageName; // Use 'photo' to match table column
            }

            // Ensure slug is unique, excluding the current blog
            $this->blog->slug = Str::slug($this->blog->title);
            if (Blog::where('slug', $this->blog->slug)->where('id', '!=', $this->blog->id)->exists()) {
                $this->blog->slug .= '-' . $this->blog->id;
            }

            $this->blog->save();

            $this->alert('success', __('Blog updated successfully.'));
            $this->dispatch('refreshIndex');
            $this->editModal = false;
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            $this->alert('error', __('Validation failed. Please check the form.'));
        } catch (\Exception $e) {
            \Log::error("Failed to update blog ID {$this->blog->id}: {$e->getMessage()}");
            $this->alert('error', __('Failed to update blog.'));
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.admin.blog.edit');
    }
}