<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between">
                    <div>
                        <div class="flex items-center space-x-0 mb-4">
                            <h2 class="text-lg font-semibold">{{ $task->title }}</h2>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-black pl-4">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                        <p class="mb-4"><strong>Link:</strong> 
                            @if (filter_var($task->url, FILTER_VALIDATE_URL))
                                <a href="{{ $task->url }}" target="_blank" class="underline">
                                    {{ shortenText($task->url) }}
                                </a>
                            @else
                                {{ shortenText($task->url) }}
                            @endif
                        </p>
                        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
                    </div>
                    @if ($userDetail)
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Your Status</label>
                        <form id="statusForm" action="{{ route('tasks.update_status', $userDetail->id) }}" method="PUT">
                            @csrf
                            @method('PUT')
                            <select style="width:150px" id="status" name="status" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500">
                                <option value="pending" {{ $userDetail->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $userDetail->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $userDetail->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="mb-4">{{ $task->description }}</p>
                </div>
            </div>

            <div class="mt-12">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">Assigned Users</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($task->details as $detail)
                                <div class="bg-gray-100 border border-gray-300 rounded p-4 flex items-center justify-between">
                                    <div>
                                        @if (auth()->user()->id === $detail->user_id)
                                            You
                                        @else
                                            <a href="{{ route('profiles.show', $detail->user->id) }}" class="text-black hover:underline">{{ $detail->user->name }}</a>
                                        @endif
                                    </div>
                                    <div>
                                        <span id="status-{{ $detail->user_id }}" class="text-sm font-semibold {{ $detail->status === 'completed' ? 'text-green-600' : ($detail->status === 'in_progress' ? 'text-yellow-600' : 'text-gray-600') }}">{{ ucfirst($detail->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($userDetail)
        <script>
            $(document).ready(function() {
            // Event handler for changing the status select box
            $('#status').change(function() {
                var status = $(this).val();
                var form = $(this).closest('form');
                // Send AJAX request to update status
                $.ajax({
                    url: $('#statusForm').attr('action'),
                    type: $('#statusForm').attr('method'),
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update the status dynamically
                        var statusText = ucfirst(status);
                        var statusClass = '';
                        if (status === 'completed') {
                            statusClass = 'text-green-600';
                        } else if (status === 'in_progress') {
                            statusClass = 'text-yellow-600';
                        } else {
                            statusClass = 'text-gray-600';
                        }
                        var statusHtml = '<span id="status-{{ $userDetail->user->id }}" class="text-sm font-semibold ' + statusClass + '">' + statusText + '</span>';
                        $('#status-{{ $userDetail->user->id }}').replaceWith(statusHtml);
                    },
                    error: function(xhr) {
                        // Handle error
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        </script>
    @endif
</x-app-layout>
