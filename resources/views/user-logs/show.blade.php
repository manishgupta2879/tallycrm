@extends('layouts.app', ['breadcrumb' => 'Log Detail', 'breadcrumbRight' => 'User Logs -> Detail'])

@section('content')
<div class="p-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header Info -->
        <div class="bg-white rounded shadow-sm border border-gray-200 p-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4 font-bold text-lg">
                        {{ strtoupper(substr($userLog->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $userLog->user->name ?? 'Deleted User' }}</h1>
                        <p class="text-sm text-gray-500">{{ $userLog->user->email ?? '' }} | {{ $userLog->date->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="text-right">
                        <div class="text-xs text-gray-400 uppercase font-bold">{{ __('Login') }}</div>
                        <div class="text-sm font-medium text-gray-700">{{ $userLog->log_in ? $userLog->log_in->format('h:i:s A') : '-' }}</div>
                    </div>
                    <div class="h-10 w-px bg-gray-200"></div>
                    <div class="text-right">
                        <div class="text-xs text-gray-400 uppercase font-bold">{{ __('Logout') }}</div>
                        <div class="text-sm font-medium text-gray-700">{{ $userLog->log_out ? $userLog->log_out->format('h:i:s A') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline of Activities -->
        <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">{{ __('Activity Timeline') }}</h3>
                <span class="text-xs font-medium text-gray-500">{{ count($details) }} {{ __('Total Actions') }}</span>
            </div>
            
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach(array_reverse($details) as $index => $item)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white @php
                                            echo match($item['action'] ?? '') {
                                                'CREATE' => 'bg-green-100 text-green-600',
                                                'UPDATE' => 'bg-blue-100 text-blue-600',
                                                'DELETE' => 'bg-red-100 text-red-600',
                                                'LOGIN' => 'bg-indigo-100 text-indigo-600',
                                                'LOGOUT' => 'bg-gray-100 text-gray-600',
                                                default => 'bg-gray-100 text-gray-600'
                                            };
                                        @endphp">
                                            @php
                                            $iconName = match($item['action'] ?? '') {
                                                'CREATE' => 'plus-circle',
                                                'UPDATE' => 'edit',
                                                'DELETE' => 'trash-2',
                                                'LOGIN' => 'log-in',
                                                'LOGOUT' => 'log-out',
                                                default => 'activity'
                                            };
                                            @endphp
                                            <i data-lucide="{{ $iconName }}" class="h-4 w-4"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">
                                                {{ $item['detail'] ?? 'No description' }}
                                            </p>
                                            @if(isset($item['model']) && $item['model'])
                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 rounded text-gray-600">
                                                    {{ $item['model'] }}
                                                </span>
                                                @if(isset($item['model_id']) && $item['model_id'])
                                                <span class="text-xs text-gray-400">#{{ $item['model_id'] }}</span>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                            <time datetime="{{ $item['date'] ?? '' }}">
                                                {{ isset($item['date']) ? date('h:i:s A', strtotime($item['date'])) : '' }}
                                            </time>
                                            <div class="font-medium text-gray-400">{{ $item['role'] ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('user-logs.index') }}" class="btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                {{ __('Back to Logs') }}
            </a>
        </div>
    </div>
</div>
@endsection
