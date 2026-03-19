<?php

namespace App\Traits;

trait Searchable
{
    /**
     * Scope for generic searching across defined columns.
     * Expects a $searchable property on the model.
     */
    public function scopeSearch($query, $term)
    {
        if (!$term || !isset($this->searchable)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            foreach ($this->searchable as $column) {
                if (!str_contains($column, '.')) {
                    // Simple column search
                    $q->orWhere($column, 'like', "%{$term}%");
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
}
