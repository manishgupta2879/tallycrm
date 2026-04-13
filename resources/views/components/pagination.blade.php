<div class="flex items-center justify-between px-3 py-2 border-t border-gray-200 bg-gray-50 text-xs">
    <div class="text-gray-600">
        Showing <span class="font-medium">{{ $getStartRecord() }}</span> to
        <span class="font-medium">{{ $getEndRecord() }}</span> of
        <span class="font-medium">{{ $totalRecords ?? ($currentPage * $perPage) }}</span>
        results
    </div>

    @if ($totalPages > 1)
        <div class="flex items-center space-x-0.5">
            <!-- First Page Button -->
            @if ($isFirstPage())
                <span class="border border-gray-300 text-gray-400 text-xs py-1.5 px-3 rounded-lg bg-gray-100 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    title="First Page">
                    First Page
                </span>
            @else
                <a href="{{ $buildUrl(1) }}"
                    class="border border-primary-0 text-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    title="First Page">
                    First Page
                </a>
            @endif

            <!-- Previous Button -->
            @if ($isFirstPage())
                <span class="border border-gray-300 text-gray-400 text-xs py-1.5 px-3 rounded-lg bg-gray-100 font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $buildUrl($currentPage - 1) }}"
                    class="border border-primary-0 text-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition flex items-center justify-center gap-1 hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            <!-- Page Numbers -->
            @php
                $pages = $getPageNumbers();
                $firstPage = reset($pages);
                $lastPage = end($pages);
            @endphp

            @for ($i = $firstPage; $i <= $lastPage; $i++)
                @if ($i == $currentPage)
                    <span class="border border-primary-0 bg-primary-0 text-white text-xs py-1.5 px-3 rounded-lg font-semibold shadow-md">{{ $i }}</span>
                @else
                    <a href="{{ $buildUrl($i) }}"
                        class="border border-primary-0 text-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">{{ $i }}</a>
                @endif
            @endfor

            <!-- Next Button -->
            @if ($isLastPage())
                <span class="border border-gray-300 text-gray-400 text-xs py-1.5 px-3 rounded-lg bg-gray-100 font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $buildUrl($currentPage + 1) }}"
                    class="border border-primary-0 text-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition flex items-center justify-center gap-1 hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @endif

            <!-- Last Page Button -->
            @if ($isLastPage())
                <span class="border border-gray-300 text-gray-400 text-xs py-1.5 px-3 rounded-lg bg-gray-100 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Last Page">
                    Last Page
                </span>
            @else
                <a href="{{ $buildUrl($totalPages) }}"
                    class="border border-primary-0 text-primary-0 text-xs py-1.5 px-3 rounded-lg hover:text-white transition hover:bg-primary-0 active:bg-primary-0 font-semibold shadow-md hover:shadow-lg ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    title="Last Page">
                    Last Page
                </a>
            @endif
        </div>
    @endif
</div>
