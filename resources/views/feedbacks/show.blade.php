<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="font-semibold text-lg mb-4">Feedback Information</h3>
                    <div class="mb-4">
                        <p>For : <a href="{{ route('profiles.show', $feedback->taskDetail->user_id) }}" class="underline">{{ $feedback->taskDetail->user->name }}</a></p>
                    </div>
                    <div class="mb-4">
                        <p>Task : <a href="{{ route('tasks.show', $feedback->taskDetail->task_id) }}" class="underline">{{ $feedback->taskDetail->task->title }}</a></p>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <p><span class="font-semibold">Rating : </span>{{ $feedback->rating }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Comment:</h3>
                        <p>{{ $feedback->comment }}</p>
                    </div>

                    @if(auth()->user()->id === $feedback->user_id)
                    <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
