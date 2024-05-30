{{-- resources/views/project/showcase.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Project Showcase
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-8 border-b border-gray-200">
                    <h2 class="text-lg font-semibold mb-4">Welcome to the Project Showcase/Help Page!</h2>
                    <p class="mb-4">This page is designed to showcase various elements and buttons used in the project.</p>

                    <div class="border-l-4 border-gray-500 p-4 mb-6" role="alert">
                        <p class="font-bold">Brief Description</p>
                        <p>The system allows the upper-tier users (Managers, BSEs, Leaders) to assign tasks to members and then give feedback and ratings. 
                        After every month or every evaluation, the upper-tier users can generate the summary evaluation of the members based on their work and feedbacks with generated text from AI in PDF format.</p>
                    </div>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Buttons</h3>

                    <!-- Generate Evaluation Button -->
                    <a href="#" class="tooltip flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-purple-600 hover:to-green-600 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-6">
                        <span class="tooltiptext">This will generate an evaluation report for the user using generative AI.</span>
                        <span>Generate Evaluation</span>
                    </a>
                    <br>
                    <!-- Primary Button -->
                    <button class="bg-black hover:bg-black text-white font-bold py-2 px-4 rounded mb-6">
                        Primary Button
                    </button>

                    <!-- Edit Profile Button -->
                    <a href="#" class="text-black mb-6 block">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                    </a>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Select Boxes</h3>

                    <!-- Leader Select Box -->
                    <div class="px-6 mb-6">
                        <form method="POST" action="#" data-userid="#">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="leaderSelect">Assigned to:</label>
                                <select id="leaderSelect" name="assigned_to" class="ml-4 appearance-none bg-white border border-gray-400 hover:border-gray-500 px-4 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500 mb-4">
                                    <option value="">Select a Leader</option>
                                    <option value="1">Leader 1</option>
                                    <option value="2">Leader 2</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Filter Form -->
                    <form id="filterForm" method="GET" action="#" class="flex items-center mb-6">
                        <label for="month" class="sr-only">Month</label>
                        <select name="month" id="month" class="appearance-none bg-white border border-gray-400 hover:border-gray-500 px-4 pr-8 rounded shadow leading-tight focus:outline-none focus:border-gray-500 mr-4 mb-4">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                            Filter
                        </button>
                    </form>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Task Status Elements</h3>
                    <div class="flex justify-between py-8 mb-6">
                        <div class="flex flex-grow text-center space-x-4">
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Completed</p>
                                <p class="text-lg text-green-600">5</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">In Progress</p>
                                <p class="text-lg text-yellow-600">3</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Pending</p>
                                <p class="text-lg text-gray-600">2</p>
                            </div>
                            <div class="flex-1 bg-gray-200 px-4 py-2 rounded-lg">
                                <p class="text-gray-700 font-semibold">Late</p>
                                <p class="text-lg text-red-600">1</p>
                            </div>
                        </div>
                    </div>

                    <p class="mb-4">The task status elements provide a quick overview of the task counts in different states. These include the number of tasks that are completed, in progress, pending, and late. This helps users quickly understand their workload and prioritize tasks accordingly.</p>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Sample Task Card</h3>
                    <div class="border rounded-lg p-4 my-8 block hover:bg-gray-100 mb-6">
                        <div class="flex justify-between items-center">
                            <div class="pr-12">
                                <h3 class="font-semibold text-lg mb-4">CirleCI Fail Error</h3>
                                <p class="mb-4">
                                    <span class="bg-red-100 text-red-800 font-semibold px-2.5 py-0.5 rounded border border-red-800">
                                        Late
                                    </span>
                                </p>
                                <p class="pb-4 font-semibold">Deadline: 2024-06-01</p>
                                <p>This is a sample description for a task. It demonstrates how task details will be displayed.</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-600">
                                In Progress
                            </span>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-2">Sample User Card</h3>
                    <div class="border rounded-lg p-4 mt-6 block hover:bg-gray-100 mb-6">
                        <div class="flex justify-between items-center">
                            <div class="pr-12">
                                <span class="font-semibold text-black">Nay Lin Htoo</span>
                                <p>Role: Senior Developer</p>
                                <p>Assigned Tasks: 10</p>
                                <p>Completed Tasks: 7</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Page Explanations</h3>
                    <div class="mb-6">
                        <h4 class="text-md font-semibold">User Page</h4>
                        <p>The User Page allows upper-tier users to view and manage individual user profiles. This includes assigning tasks to members, viewing their task progress, and providing feedback and ratings.</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold">Task Page</h4>
                        <p>The Task Page lists all the tasks assigned to the members. Users can filter tasks based on various criteria such as status (completed, in progress, pending) and time period. This page also allows users to update the status of tasks and view detailed information about each task.</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold">Dashboard</h4>
                        <p>The Dashboard provides an overview of the tasks and their statuses. It includes summary statistics such as the number of completed, in-progress, and pending tasks. The dashboard also highlights any late tasks and provides options to generate summary evaluations in PDF format using Generative AI.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
