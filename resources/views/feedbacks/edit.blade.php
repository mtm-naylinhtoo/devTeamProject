<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Feedback') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <form action="{{ route('feedbacks.update', $feedback->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Rating Field -->
                <div class="mb-4">
                    <x-input-label for="rating" :value="__('Rating')" />
                    <input id="rating" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="rating" min="1" max="5" value="{{ old('rating', $feedback->rating) }}" required />
                    <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                </div>

                <!-- Comment Field -->
                <div class="mb-4">
                    <x-input-label for="comment" :value="__('Comment')" />
                    <textarea id="comment" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" name="comment" rows="4" required>{{ old('comment', $feedback->comment) }}</textarea>
                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                </div>

                <!-- Hidden Task Detail ID Field -->
                <input type="hidden" name="task_detail_id" value="{{ $feedback->task_detail_id }}" />

                <!-- Hidden User ID Field -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}" />

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <x-primary-button>{{ __('Update Feedback') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
