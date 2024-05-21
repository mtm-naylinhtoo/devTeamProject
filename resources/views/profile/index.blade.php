<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @php
                $profiles = $profiles->sortByDesc(function ($profile) {
                    return (Auth::user()->isAdmin() && permission_allow(auth()->user(), $profile)) || Auth::user()->id === $profile->id;
                });
            @endphp
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($profiles as $profile)
                    @php
                        $hasPermission = (Auth::user()->isAdmin() && permission_allow(auth()->user(), $profile)) || Auth::user()->id === $profile->id;
                    @endphp
                    <div class="bg-white border-2 {{ $hasPermission ? 'border-gray-200' : 'bg-gray-100 opacity-90' }} rounded-lg shadow-sm hover:bg-gray-100 transition">
                        <a href="{{ route('profiles.show', $profile->id)}}" class="block p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="flex">
                                        @if (Auth::user()->id == $profile->id)
                                            <span class="text-lg font-semibold text-gray-800">You</span>
                                            @if ($hasPermission)
                                                <svg class="w-6 h-6 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        @else
                                        <span class="text-lg font-semibold text-gray-800">{{ $profile->name }}</span>
                                        @if ($hasPermission)
                                            <svg class="w-6 h-6 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                        @endif
                                    </div>
                                    <p class="text-gray-600">{{ $profile->email }}</p>
                                    <p class="text-gray-600">{{ ucfirst($profile->role) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
