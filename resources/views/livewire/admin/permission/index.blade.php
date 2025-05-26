<div>
    {{-- Controls --}}
    <div class="flex flex-wrap justify-center mb-4">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex items-center gap-3">
            <select wire:model="perPage"
                class="w-20 p-2 border border-gray-300 rounded text-sm">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('permission_delete')
                <button
                    wire:click="confirmDelete('deleteSelected')"
                    wire:loading.attr="disabled"
                    @if($selectedCount === 0) disabled @endif
                    class="bg-red-600 text-white px-3 py-1 rounded disabled:opacity-50">
                    {{ __('Delete Selected') }}
                </button>
            @endcan
        </div>

        <div class="lg:w-1/2 md:w-1/2 sm:w-full mt-2 md:mt-0">
            <input type="text" wire:model.debounce.300ms="search"
                class="w-full p-2 border border-gray-300 rounded"
                placeholder="{{ __('Search') }}" />
        </div>
    </div>

    {{-- Loading indicator --}}
    <div wire:loading.delay class="mb-2">
        {{ __('Loading...') }}
    </div>

    {{-- Table --}}
    <table class="w-full border-collapse border border-gray-200 text-sm">
        <thead>
            <tr>
                <th class="border border-gray-300 px-2 py-1">
                    <input type="checkbox" wire:model="selected" value="" wire:click="$toggle('selected')" />
                </th>
                <th class="border border-gray-300 px-2 py-1 cursor-pointer" wire:click="sortBy('title')">
                    {{ __('Title') }}
                    @if(isset($sorts['title']))
                        @if($sorts['title'] === 'asc')
                            &uarr;
                        @else
                            &darr;
                        @endif
                    @endif
                </th>
                <th class="border border-gray-300 px-2 py-1">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td class="border border-gray-300 text-center">
                        <input type="checkbox" value="{{ $permission->id }}" wire:model="selected" />
                    </td>
                    <td class="border border-gray-300 px-2 py-1">{{ $permission->title }}</td>
                    <td class="border border-gray-300 px-2 py-1">
                        <div class="flex gap-2">
                            @can('permission_show')
                                <a href="{{ route('admin.permissions.show', $permission) }}"
                                    class="text-blue-600 hover:underline">{{ __('View') }}</a>
                            @endcan
                            @can('permission_edit')
                                <a href="{{ route('admin.permissions.edit', $permission) }}"
                                    class="text-green-600 hover:underline">{{ __('Edit') }}</a>
                            @endcan
                            @can('permission_delete')
                                <button
                                    wire:click="confirmDelete('delete', {{ $permission->id }})"
                                    wire:loading.attr="disabled"
                                    class="text-red-600 hover:underline">
                                    {{ __('Delete') }}
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4">{{ __('No entries found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $permissions->links() }}
    </div>

    {{-- Selected count --}}
    @if ($selectedCount)
        <p class="mt-2 text-sm">
            {{ $selectedCount }} {{ __('entries selected') }}
        </p>
    @endif

    {{-- SweetAlert Confirmation JS --}}
    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                Livewire.on('confirmDelete', (action, id) => {
                    Swal.fire({
                        title: action === 'delete'
                            ? '{{ __('Are you sure you want to delete this permission?') }}'
                            : '{{ __('Are you sure you want to delete the selected permissions?') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __('Delete') }}',
                        cancelButtonText: '{{ __('Cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (action === 'delete') {
                                @this.call('delete');
                            } else if (action === 'deleteSelected') {
                                @this.call('deleteSelected');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</div>
