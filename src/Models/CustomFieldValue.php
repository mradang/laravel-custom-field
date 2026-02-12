<?php

namespace mradang\LaravelCustomField\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $fillable = [
        'valuetable_type',
        'valuetable_id',
        'field_id',
        'field_value',
    ];

    protected $hidden = [
        'valuetable_type',
        'valuetable_id',
    ];

    public function fieldvaluetable()
    {
        return $this->morphTo();
    }
}
