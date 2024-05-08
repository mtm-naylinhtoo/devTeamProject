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
                    <h3 class="font-semibold text-lg mb-4">Profile Information</h3>
                    <p>Name: {{ $profile->name }}</p>
                    <p class="py-4">Role: {{ ucfirst($profile->role) }}</p>
                    <p>Email: {{ $profile->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-10 border-b border-gray-200">
                    <h2 class="font-semibold text-lg">Tasks Assigned</h2>
                    @foreach ($task_details as $detail)
                        <div class="border rounded-lg p-4 my-8 flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-lg mb-4">
                                    <a href="{{ route('tasks.show', $detail->task->id) }}">{{ $detail->task->title }}</a>
                                </h3>
                                <p>{{ $detail->task->description }}</p>
                            </div>
                            @if (auth()->user()->id === $profile->id)
                                <form id="statusForm-{{ $detail->id }}" action="{{ route('tasks.update_status', $detail->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="relative mx-2" style="width: 150px;">
                                        <select name="status" data-id="{{ $detail->id }}" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500">
                                            <option value="pending" {{ $detail->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $detail->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $detail->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                    <span id="statusMessage-{{ $detail->id }}"></span>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')
                }
            });

            // Event handler for changing the status select box
            $('select[name="status"]').change(function() {
                var form = $(this).closest('form');
                var statusMessageId = '#statusMessage-' + form.data('id');
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        $(statusMessageId).text('Status updated successfully');
                    },
                    error: function(xhr) {
                        $(statusMessageId).text('Error updating status');
                    }
                });
            });
        });
    </script>

</x-app-layout>
