<?php

namespace mradang\LaravelCustomField\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $fillable = [
        'valuetable_type',
        'valuetable_id',
        'no',
        'data',
    ];

    protected $hidden = [
        'valuetable_type',
        'valuetable_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '[]',
    ];

    public function fieldvaluetable()
    {
        return $this->morphTo();
    }
}
