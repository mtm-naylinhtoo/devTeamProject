<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-8 border-b border-gray-200">
                    
                    <div class="flex justify-between">
                        <h2 class="font-semibold text-lg pt-2">Tasks Status</h2>
                    </div>
                    <div class="flex justify-between">
                        <span>You can view all your tasks <a href="{{ route('profiles.show', auth()->user()->id) }}" class="underline text-black hover:underline">here.</a></span>
                    </div>
                    <div class="flex justify-between py-8">
                        <div class="flex flex-grow text-center space-x-4">
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Completed</p>
                                <p class="text-lg text-green-600">{{ $completedCount }}</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">In Progress</p>
                                <p class="text-lg text-yellow-600">{{ $inProgressCount }}</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Pending</p>
                                <p class="text-lg text-gray-600">{{ $pendingCount }}</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Late</p>
                                <p class="text-lg text-red-600">{{ $lateTasksCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->role == 'manager' && $usersWithoutLeader->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
                    <div class="px-6 py-8 border-b border-gray-200">
                        <div class="flex justify-between">
                            <h2 class="font-semibold text-lg pt-2">Members who need a leader assignment</h2>
                        </div>
                        <div class="py-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($usersWithoutLeader as $user)
                                <a href="{{ route('profiles.show', $user->id) }}" class="border rounded-lg p-4 mt-6 block hover:bg-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div class="pr-12">
                                            <span class="font-semibold text-black">{{ $user->name }}</span>
                                            <p>Role: {{ ucfirst($user->role) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="pt-12 pb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1 mb-1 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm4.293 6.293a1 1 0 0 1 1.414 1.414l-7 7a1 1 0 0 1-1.414 0l-3-3a1 1 0 1 1 1.414-1.414L9 13.586l6.293-6.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="pl-4">All members have been assigned leaders. Good job! 👍.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-12">
                <div class="px-6 py-8 border-b border-gray-200">
                    <div class="flex justify-between">
                        <h2 class="font-semibold text-lg">Tasks you haven't finished</h2>
                    </div>
                    @forelse ($task_details as $detail)
                        <a href="{{ route('tasks.show', $detail->task->id) }}" class="border rounded-lg p-4 my-8 block hover:bg-gray-100">
                            <div class="flex justify-between items-center">
                                <div class="pr-12">
                                    <h3 class="font-semibold text-lg mb-4">{{ $detail->task->title }}</h3>
                                    @if($detail->lateTask)
                                        <p class="mb-4">
                                            <span class="bg-red-100 text-red-800 font-semibold px-2.5 py-0.5 rounded border border-red-800">
                                                Late
                                            </span>
                                        </p>
                                    @endif
                                    <p class="pb-4 font-semibold">Deadline: {{ $detail->task->due_date }}</p>
                                    <p>{{ $detail->task->description }}</p>
                                </div>
                                <span id="status-{{ $detail->user_id }}" class="text-sm font-semibold {{ $detail->status === 'completed' ? 'text-green-600' : ($detail->status === 'in_progress' ? 'text-yellow-600' : 'text-gray-600') }}">
                                    {{ ucfirst($detail->status) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="pt-12 pb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1 mb-1 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm4.293 6.293a1 1 0 0 1 1.414 1.414l-7 7a1 1 0 0 1-1.414 0l-3-3a1 1 0 1 1 1.414-1.414L9 13.586l6.293-6.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="pl-4">Wow! There are no unfinished tasks here. Good job! 👍.</span>
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if (Auth::user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
                    <div class="px-6 py-8 border-b border-gray-200">
                        <div class="flex justify-between">
                            <h2 class="font-semibold text-lg pt-2">Members who need reviewing</h2>
                        </div>
                        <div class="py-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($usersWithCompletedTasks as $user)
                                <a href="{{ route('profiles.show', $user->id) }}" class="border rounded-lg p-4 mt-6 block hover:bg-gray-100">
                                    <div class="flex justify-between items-center">
                                        <div class="pr-12">
                                            <span class="font-semibold text-black">{{ $user->name }}</span>
                                            <p>Role: {{ ucfirst($user->role) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="pt-12 pb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1 mb-1 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm4.293 6.293a1 1 0 0 1 1.414 1.414l-7 7a1 1 0 0 1-1.414 0l-3-3a1 1 0 1 1 1.414-1.414L9 13.586l6.293-6.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="pl-4">All good for now 👍.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
