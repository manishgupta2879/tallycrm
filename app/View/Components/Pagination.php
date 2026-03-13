<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Pagination extends Component
{
    /**
     * The current page number
     */
    public int $currentPage;

    /**
     * The total number of pages
     */
    public int $totalPages;

    /**
     * The total number of records
     */
    public ?int $totalRecords;

    /**
     * Records per page
     */
    public int $perPage;

    /**
     * The base URL for pagination links
     */
    public string $baseUrl;

    /**
     * Query parameters to preserve in pagination links
     */
    public array $queryParams;

    /**
     * Number of pagination links to display
     */
    public int $displayLinks;

    /**
     * Create a new component instance.
     */
    public function __construct(
        int $currentPage = 1,
        int $totalPages = 1,
        ?int $totalRecords = null,
        int $perPage = 15,
        string $baseUrl = '',
        array $queryParams = [],
        int $displayLinks = 5
    ) {
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = max(1, $totalPages);
        $this->totalRecords = $totalRecords;
        $this->perPage = max(1, $perPage);
        $this->baseUrl = $baseUrl ?: url()->current();

        // Merge provided query params with all current request query params (except 'page')
        $allQueryParams = request()->query();
        unset($allQueryParams['page']);
        $this->queryParams = array_merge($allQueryParams, $queryParams);

        $this->displayLinks = max(1, $displayLinks);
    }

    /**
     * Get the start record for the current page
     */
    public function getStartRecord(): int
    {
        return ($this->currentPage - 1) * $this->perPage + 1;
    }

    /**
     * Get the end record for the current page
     */
    public function getEndRecord(): int
    {
        if (!$this->totalRecords) {
            return $this->currentPage * $this->perPage;
        }

        return min($this->currentPage * $this->perPage, $this->totalRecords);
    }

    /**
     * Get the page numbers to display
     */
    public function getPageNumbers(): array
    {
        $pages = [];
        $halfDisplay = intdiv($this->displayLinks, 2);

        $startPage = max(1, $this->currentPage - $halfDisplay);
        $endPage = min($this->totalPages, $startPage + $this->displayLinks - 1);

        // Adjust start if we're near the end
        if ($endPage - $startPage + 1 < $this->displayLinks) {
            $startPage = max(1, $endPage - $this->displayLinks + 1);
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            $pages[] = $i;
        }

        return $pages;
    }

    /**
     * Build URL with query parameters
     */
    public function buildUrl(int $page): string
    {
        $separator = str_contains($this->baseUrl, '?') ? '&' : '?';
        $query = http_build_query(array_merge($this->queryParams, ['page' => $page]));
        return $this->baseUrl . $separator . $query;
    }

    /**
     * Check if current page is first
     */
    public function isFirstPage(): bool
    {
        return $this->currentPage === 1;
    }

    /**
     * Check if current page is last
     */
    public function isLastPage(): bool
    {
        return $this->currentPage === $this->totalPages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.pagination');
    }
}
