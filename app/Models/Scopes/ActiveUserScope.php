<?php

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveUserScope implements Scope
{

    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('status',User::STATUS_ACTIVE);
    }
}
