<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->wallet_id = Str::uuid();
        });
    }

    public function getKeyType(): string
    {
        return 'uuid';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function isIncrementing(): bool
    {
        return false;
    }
}