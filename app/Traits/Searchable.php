<?php

namespace App\Traits;

trait Searchable
{
    /**
     * Date columns that should support date format searching
     */
    protected $dateSearchableColumns = [
        'tally_expiry',
        'rollout_request_date',
        'tcp_generated_date',
        'rollout_done_date',
        'remarks_date',
        'last_sync_date',
    ];

    /**
     * Scope for generic searching across defined columns.
     * Expects a $searchable property on the model.
     */
    public function scopeSearch($query, $term)
    {
        if (!$term || !isset($this->searchable)) {
            return $query;
        }

        // Check if search term is a date in dd/mm/yyyy format
        $dateFormat = $this->convertDateFormat($term);

        return $query->where(function ($q) use ($term, $dateFormat) {
            foreach ($this->searchable as $column) {
                if (!str_contains($column, '.')) {
                    // Simple column search
                    $q->orWhere($column, 'like', "%{$term}%");

                    // If date format detected and column is a date field, also search by converted date
                    if ($dateFormat && in_array($column, $this->dateSearchableColumns)) {
                        $q->orWhere($column, 'like', "%{$dateFormat}%");
                    }
                } else {
                    // Relationship search (e.g., 'company.name')
                    [$relation, $relColumn] = explode('.', $column);
                    $q->orWhereHas($relation, function ($rel) use ($relColumn, $term) {
                        $rel->where($relColumn, 'like', "%{$term}%");
                    });
                }
            }
        });
    }

    /**
     * Convert dd/mm/yyyy or dd-mm-yyyy format to yyyy-mm-dd
     * Returns null if the format doesn't match
     */
    private function convertDateFormat($term)
    {
        // Check for dd/mm/yyyy or dd-mm-yyyy format
        if (preg_match('/^(\d{2})[\/-](\d{2})[\/-](\d{4})$/', $term, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];

            // Validate date
            if (checkdate((int)$month, (int)$day, (int)$year)) {
                // Return in yyyy-mm-dd format
                return "$year-$month-$day";
            }
        }

        return null;
    }
}
