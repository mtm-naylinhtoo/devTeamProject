<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="title" :value="__('URL')" />
                    <x-text-input id="url" class="block mt-1 w-full" type="text" name="url" :value="old('url')" required autofocus />
                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" style="height:250px" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="users" :value="__('Assign Users')" />
                    <select id="users" name="users[]"required multiple class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        @foreach ($users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('users.*')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="due_date" :value="__('Due Date')" />
                    <input id="due_date" type="date" name="due_date" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>{{ __('Create Task') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#users').select2({
                    placeholder: "Select users",
                    allowClear: true
                });
            });
        </script>
    </x-slot>
</x-app-layout>
