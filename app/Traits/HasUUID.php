<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait HasUUID
{
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
            $model->meta_id = uniqid();
        });
    }

    public function scopeMetaID(Builder $query, string $meta_id): Builder
    {
        return $query->where('meta_id', $meta_id);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('meta_id', $value)->firstOrFail();
    }

    public function resolveSoftDeletableRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where('meta_id', $value)->firstOrFail();
    }
}
