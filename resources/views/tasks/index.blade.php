<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between mb-8">
                        <a href="{{ route('tasks.create') }}" class="text-white bg-black py-2 px-4 rounded">
                            Create New Task
                        </a>
                        <div class="flex justify-between">
                            <form action="{{ route('tasks.index') }}" method="GET" class="flex">
                                <select name="month" class="appearance-none bg-white border border-gray-400 hover:border-gray-500 px-4 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month', date('n')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                                <input type="text" name="search" style="margin: 0 10px" class="appearance-none bg-white border border-gray-400 hover:border-gray-500 px-4 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500 w-full ml-2" placeholder="Search tasks..." value="{{ request('search') }}">
                                <x-primary-button type="submit">Search</x-primary-button>
                            </form>
                        </div>
                    </div>
                    <div class="py-6">
                        @foreach ($tasks as $task)
                        @php
                            $allCompleted = $task->details->every(function ($detail) {
                                return $detail->status === 'completed';
                            });
                        @endphp
                        <a href="{{ route('tasks.show', $task->id) }}" class="block border border-gray-300 rounded-lg overflow-hidden mb-6 p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-black">
                                        {{ shortenDescription($task->title) }}
                                    </h3>
                                    <p class="text-gray-600 mt-2">
                                        @php
                                            $maxUsers = 3; // Set the maximum number of users to display
                                            $userNames = $task->details->pluck('user.name')->take($maxUsers)->implode(', ');
                                            $remainingUsers = $task->details->count() - $maxUsers;
                                        @endphp

                                        Assigned Users: {{ $userNames }}
                                        
                                        @if ($remainingUsers > 0)
                                            <span class="text-sm text-gray-500">+ {{ $remainingUsers }} more</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600">
                                        Due Date: {{ $task->due_date }}
                                    </p>
                                    <p class="my-4">
                                        <span class="font-semibold text-sm px-2.5 py-0.5 rounded border {{ $allCompleted ? 'bg-green-100 text-green-800 border-green-800' : 'bg-yellow-100 text-yellow-800 border-yellow-800' }}">
                                            {{ $allCompleted ? 'Completed' : 'Still going' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
