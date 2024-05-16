<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile Details') }}
            </h2>
            @if ($profile->hasFeedbacks() && auth()->user()->isAdmin() && permission_allow(auth()->user(), $profile))
                <a href="#" onclick="generateEvaluation({{ $profile->id }})" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Generate Evaluation
                </a>
            @endif
        </div>
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
                <div class="px-6 py-8 border-b border-gray-200">
                    <div class="flex justify-between">
                        <h2 class="font-semibold text-lg pt-2">Tasks Assigned</h2>
                        <form method="GET" action="{{ route('profiles.show', $profile->id) }}" class="flex items-center">
                            <div class="mr-2">
                                <label for="month" class="sr-only">Month</label>
                                <select name="month" id="month" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == request('month', now()->month) ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mr-2">
                                <label for="year" class="sr-only">Year</label>
                                <select name="year" id="year" class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                                    @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                                        <option value="{{ $y }}" {{ $y == request('year', now()->year) ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                    @foreach ($task_details as $detail)
                        <div class="border rounded-lg p-4 my-8 ">
                            <div class="flex justify-between items-center">
                                <div class="pr-12">
                                    <h3 class="font-semibold text-lg mb-4">
                                        <a href="{{ route('tasks.show', $detail->task->id) }}">{{ $detail->task->title }}</a>
                                    </h3>
                                    <p class="pb-4 font-semibold">Deadline: {{ $detail->task->due_date }}</p>
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
                                @else
                                    <span id="status-{{ $detail->user_id }}" class="text-sm font-semibold {{ $detail->status === 'completed' ? 'text-green-600' : ($detail->status === 'in_progress' ? 'text-yellow-600' : 'text-gray-600') }}">
                                        {{ ucfirst($detail->status) }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-4">
                                @if (auth()->user()->isAdmin() && $detail->status === 'completed' && permission_allow(auth()->user(),$profile))
                                    @if (!$detail->feedback_given)
                                        <hr style="border-top: 1px dashed #ccc; margin: 10px 0; padding-bottom:4px;">
                                        You can start reviewing now:
                                        <button onclick="openFeedbackModal({{ $detail->id }})" class="border border-gray-300 hover:bg-black hover:text-white text-black font-bold py-2 px-4 ml-2 rounded">
                                            Review
                                        </button>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm4.293 6.293a1 1 0 0 1 1.414 1.414l-7 7a1 1 0 0 1-1.414 0l-3-3a1 1 0 1 1 1.414-1.414L9 13.586l6.293-6.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-green-600">
                                            <a href="{{ route('feedbacks.show', $detail->feedbacks->where('user_id', auth()->id())->first()->id) }}">
                                                You have already reviewed this task. 
                                            </a>
                                        </span>
                                    </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Spinner Overlay -->
    <div id="loadingSpinner" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex justify-center items-center">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transform transition-all" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="feedbackForm" action="{{ route('feedbacks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_detail_id" id="task_detail_id">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <label for="rating" class="block text-gray-700 text-sm font-bold mb-2">Rating:</label>
                            <input type="number" name="rating" id="rating" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" min="1" max="5" required>
                            <span id="ratingError"></span>
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">Comment:</label>
                            <textarea name="comment" id="comment" class="resize-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required></textarea>
                            <span id="descriptionError"></span>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="submitFeedback()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Submit</button>
                        <button type="button" onclick="closeFeedbackModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFeedbackModal(detailId) {
            // Open the modal and pass the detail id
            var modal = document.getElementById('feedbackModal');
            modal.classList.remove('hidden');
            document.getElementById('task_detail_id').value = detailId;

            document.getElementById('feedbackForm').reset();
            $("#ratingError").text('');
        }

        function closeFeedbackModal() {
            // Close the modal
            var modal = document.getElementById('feedbackModal');
            modal.classList.add('hidden');
        }

        function submitFeedback() {
            var form = $('#feedbackForm');
            var ratingInput = $('#rating');
            var ratingError = $('#ratingError');
            var description = $('#comment').val().trim();
            var error = false;

            // Clear any previous error messages
            ratingError.text('');
            if (description === '') {
            $('#descriptionError').removeClass('hidden').text('This field is required.');
                error = true; // Set the error flag
            }
            // Validate rating field
            if (ratingInput.val() < 1 || ratingInput.val() > 5) {
                ratingError.text('Rating must be between 1 and 5');
                error = true;
            }
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    // Optionally close the modal after successful submission
                    closeFeedbackModal();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                }
            });
        }

        function generateEvaluation(profileId) {
            event.preventDefault();
            $('#loadingSpinner').removeClass('hidden');
            window.location.href = "{{ route('profiles.pdf', $profile->id) }}";
        }

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
