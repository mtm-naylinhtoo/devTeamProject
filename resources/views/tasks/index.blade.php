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
                        <a href="{{ route('tasks.create') }}" class="text-white bg-purple-500 py-2 px-4 rounded">
                            Create New Task
                        </a>
                        <div class="flex justify-between">
                            <form action="{{ route('tasks.index') }}" method="GET" class="flex">
                                <select name="month" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month', date('n')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                                <input type="text" name="search"  style="margin: 0 10px" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full ml-2" placeholder="Search tasks..." value="{{ request('search') }}">
                                <x-primary-button type="submit">Search</x-primary-button>
                            </form>
                        </div>
                    </div>
                    <table class="w-full min-w-full divide-y divide-gray-200 mt-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assigned Users
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $task->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $task->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $task->due_date}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach ($task->details as $detail)
                                        {{ $detail->user->name }}@if (!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
