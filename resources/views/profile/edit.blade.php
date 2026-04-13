@extends('layouts.app', ['breadcrumb' => 'Profile', 'breadcrumbRight' => 'Dashboard -> Profile'])

@section('content')
    <div class="max-w-full">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Sidebar: Basic Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded shadow-sm border border-gray-200 p-6 text-center">
                        <div
                            class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 text-blue-600 mb-4">
                            <i data-lucide="user" class="h-10 w-10"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <hr class="my-4 border-gray-100">
                        <div class="text-left space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="shield" class="h-4 w-4 mr-2 text-blue-500"></i>
                                <span class="font-medium mr-1">Role:</span> {{ Auth::user()->role?->name ?? 'User' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="calendar" class="h-4 w-4 mr-2 text-blue-500"></i>
                                <span class="font-medium mr-1">Joined:</span>
                                {{ Auth::user()->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile Information -->
                    <!-- <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                    <h3 class="font-bold text-gray-800">{{ __('Update Profile Information') }}</h3>
                                </div>
                                <div class="p-6">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div> -->

                    <!-- Update Password -->
                    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800">{{ __('Change Password') }}</h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <!-- <div class="bg-white rounded shadow-sm border border-red-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-red-50 bg-red-50/30">
                                <h3 class="font-bold text-red-800">{{ __('Danger Zone') }}</h3>
                            </div>
                            <div class="p-6">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection
