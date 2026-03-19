<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->recordActivity('CREATE');
        });

        static::updated(function ($model) {
            $model->recordActivity('UPDATE');
        });

        static::deleted(function ($model) {
            $model->recordActivity('DELETE');
        });
    }

    /**
     * Record the activity.
     */
    public function recordActivity($action)
    {
        $detail = $this->getActivityLogDetail($action);
        $modelName = class_basename($this);
        
        if (function_exists('logActivity')) {
            logActivity($detail, $action, $modelName, $this->id);
        }
    }

    /**
     * Get the detail for the activity log.
     * Can be overridden in the model.
     */
    protected function getActivityLogDetail($action)
    {
        $modelName = class_basename($this);
        $name = $this->name ?? $this->id;
        
        switch ($action) {
            case 'CREATE':
                return "Created $modelName (ID - {$this->id}, Name - $name)";
            case 'UPDATE':
                return "Updated $modelName (ID - {$this->id}, Name - $name)";
            case 'DELETE':
                return "Deleted $modelName (ID - {$this->id}, Name - $name)";
            default:
                return "$action $modelName (ID - {$this->id})";
        }
    }
}
