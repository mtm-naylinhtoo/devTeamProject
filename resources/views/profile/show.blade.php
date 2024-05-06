<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-10 border-b border-gray-200">
                    <h3 class="font-semibold text-lg">Profile Information</h3>
                    <p class="py-4">Name: {{ $profile->name }}</p>
                    <p>Email: {{ $profile->email }}</p>

                    <h2 class="font-semibold text-lg mt-8">Tasks Assigned</h2>
                    @foreach ($task_details as $detail)
                        <div class="flex justify-between border-b py-4 mt-4">
                            <div>
                                <h3 class="font-semibold text-lg mb-4">{{ $detail->task->title }}</h3>
                                <p>{{ $detail->task->description }}</p>
                            </div>
                            @if (auth()->user()->id === $profile->id)
                                <div>
                                    <!-- Form to change task status -->
                                    <form action="{{ route('tasks.update', $detail->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status">
                                            <option value="pending" {{ $detail->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $detail->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $detail->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button type="submit">Update Status</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
