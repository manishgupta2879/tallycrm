@extends('layouts.app', ['breadcrumb' => 'Log Detail', 'breadcrumbRight' => 'User Logs -> Detail'])

@section('content')
<div class="p-4">
    <div class="max-w-5xl mx-auto">

        {{-- ─── Compact header card ────────────────────────────────────────── --}}
        <div class="bg-white rounded shadow-sm border border-gray-200 px-4 py-3 flex items-center justify-between mb-4">

            {{-- Left: Avatar + name + email --}}
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0 uppercase">
                    {{ substr($userLog->user->name ?? '?', 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-800 leading-tight">{{ $userLog->user->name ?? 'Deleted User' }}</div>
                    <div class="text-[11px] text-gray-400">{{ $userLog->user->email ?? '' }}</div>
                </div>
            </div>

            {{-- Right: Date / Login / Logout / Actions --}}
            <div class="flex items-center gap-6 text-[11px]">
                <div>
                    <div class="text-[9px] uppercase font-bold text-gray-400 mb-0.5">Date</div>
                    <div class="text-gray-700 font-medium">{{ $userLog->date?->format('d M Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-[9px] uppercase font-bold text-gray-400 mb-0.5">Login</div>
                    <div class="text-gray-700 font-medium">{{ $userLog->log_in ? $userLog->log_in->format('H:i:s') : '—' }}</div>
                </div>
                <div>
                    <div class="text-[9px] uppercase font-bold text-gray-400 mb-0.5">Logout</div>
                    <div class="text-gray-700 font-medium">{{ $userLog->log_out ? $userLog->log_out->format('H:i:s') : '—' }}</div>
                </div>
                <div>
                    <div class="text-[9px] uppercase font-bold text-gray-400 mb-0.5">Actions</div>
                    <div class="text-gray-700 font-medium">{{ count($details) }}</div>
                </div>
            </div>
        </div>

        {{-- ─── Activity list card ──────────────────────────────────────────── --}}
        <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
            {{-- Card header --}}
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-100 bg-gray-50">
                <span class="text-xs font-semibold text-gray-600">Activity Timeline</span>
                <span class="text-[10px] text-gray-400">{{ count($details) }} total actions</span>
            </div>
            <div class="px-2 py-2 space-y-0.5">
            @forelse(array_reverse($details) as $item)
                @php
                    $action = $item['action'] ?? 'VIEW';

                    // Icon + color per action
                    [$iconName, $iconColor] = match($action) {
                        'CREATE' => ['plus',       'text-green-500'],
                        'UPDATE' => ['pencil',     'text-blue-500'],
                        'DELETE' => ['trash-2',    'text-red-500'],
                        'LOGIN'  => ['log-in',     'text-blue-500'],
                        'LOGOUT' => ['log-out',    'text-gray-400'],
                        default  => ['eye',        'text-gray-400'],
                    };

                    // Action badge colors
                    [$badgeBg, $badgeTxt] = match($action) {
                        'CREATE' => ['bg-green-50 text-green-700',  'CREATE'],
                        'UPDATE' => ['bg-blue-50 text-blue-700',    'UPDATE'],
                        'DELETE' => ['bg-red-50 text-red-700',      'DELETE'],
                        'LOGIN'  => ['bg-indigo-50 text-indigo-700','LOGIN'],
                        'LOGOUT' => ['bg-gray-100 text-gray-600',   'LOGOUT'],
                        default  => ['bg-gray-100 text-gray-600',    'VIEW'],
                    };

                    // Model badge colors
                    $modelBadge = match(strtolower($item['model'] ?? '')) {
                        'company'     => 'bg-blue-50 text-blue-600',
                        'distributor' => 'bg-purple-50 text-purple-600',
                        'user'        => 'bg-teal-50 text-teal-600',
                        'role'        => 'bg-orange-50 text-orange-600',
                        default       => 'bg-gray-100 text-gray-500',
                    };
                @endphp

                <div class="flex items-center gap-3 px-2 py-1.5 rounded hover:bg-gray-50 group">

                    {{-- Icon --}}
                    <div class="flex-shrink-0 w-5 flex items-center justify-center {{ $iconColor }}">
                        <i data-lucide="{{ $iconName }}" class="w-3.5 h-3.5"></i>
                    </div>

                    {{-- Activity text + model badge --}}
                    <div class="flex-1 flex items-center gap-2 min-w-0">
                        <span class="text-[11px] text-gray-700 truncate">{{ $item['detail'] ?? 'No description' }}</span>

                        @if(!empty($item['model']))
                            <span class="flex-shrink-0 text-[9px] font-semibold px-1.5 py-0.5 rounded uppercase {{ $modelBadge }}">
                                {{ $item['model'] }}@if(!empty($item['model_id'])) #{{ $item['model_id'] }}@endif
                            </span>
                        @endif
                    </div>

                    {{-- Right: action badge + time --}}
                    <div class="flex-shrink-0 flex flex-col items-end gap-0.5 min-w-[60px]">
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded uppercase {{ $badgeBg }}">{{ $badgeTxt }}</span>
                        <span class="text-[10px] text-gray-400">
                            {{ isset($item['date']) ? date('H:i:s', strtotime($item['date'])) : '' }}
                        </span>
                    </div>
                </div>

            @empty
                <div class="py-10 text-center text-xs text-gray-400 italic">No activity recorded for this session.</div>
            @endforelse
            </div>{{-- /.px-2 --}}
        </div>{{-- /.card --}}

        {{-- ─── Back link ─────────────────────────────────────────────────── --}}
        <div class="mt-4 pt-3 border-t border-gray-100">
            <a href="{{ route('user-logs.index') }}"
                class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-800 transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Back to Logs
            </a>
        </div>

    </div>
</div>
@endsection
