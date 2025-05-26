<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editSubcategory">
        <x-slot name="title">
            {{ __('Edit Subcategory') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit.prevent="update">
                <div class="flex flex-wrap space-y-4 px-4">
                    <div class="px-2 w-1/2 sm:w-full">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" wire:model.defer="subcategory.name" />
                        <x-input-error :messages="$errors->get('subcategory.name')" for="subcategory.name"
                            class="mt-2" />
                    </div>

                    <div class="px-2 w-1/2 sm:w-full">
                        <x-label for="category_id" :value="__('Category')" required />
                        <select id="category_id"
                            class="block bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                            wire:model="subcategory.category_id">
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subcategory.category_id')" for="subcategory.category_id"
                            class="mt-2" />
                    </div>

                    <div class="px-2 w-1/2 sm:w-full mt-4">
                        <x-label for="language_id" :value="__('Language')" required />
                        <select id="language_id"
                            class="block bg-white text-gray-700 rounded border border-gray-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500"
                            wire:model="subcategory.language_id">
                            <option value="">{{ __('Select Language') }}</option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subcategory.language_id')" for="subcategory.language_id"
                            class="mt-2" />
                    </div>

                    <div class="w-full py-2 px-2">
                        <x-label for="image" :value="__('Image')" />
                        <x-fileupload wire:model="image" :file="$image" accept="image/jpg,image/jpeg,image/png" />
                        @if ($subcategory && $subcategory->image)
                            <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}"
                                class="w-20 h-20 mt-2">
                        @endif
                        <x-input-error :messages="$errors->get('image')" for="image" class="mt-2" />
                    </div>

                    <div class="w-full px-2">
                        <x-button primary type="submit" class="w-full" wire:loading.attr="disabled">
                            {{ __('Update') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
