<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class GenderScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        // Check if the model's table has a 'gender' column
        if (!Schema::hasColumn($model->getTable(), 'gender')) {
            return;
        }

        // Get gender from request header
        $gender = request()->header('Gender');
        
        // If no gender header or invalid gender, don't apply filter
        if (!$gender || !in_array($gender, ['man', 'woman'])) {
            return;
        }

        // Apply gender filter: show records with matching gender OR 'both'
        $builder->where(function($query) use ($gender) {
            $query->where('gender', $gender)
                  ->orWhere('gender', 'both');
        });
    }
}