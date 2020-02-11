<?php

namespace mradang\LaravelCustomField\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = [
        'model',
        'group_id',
        'name',
        'type',
        'options',
        'required',
        'sort',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    protected $hidden = ['model'];

    public function group()
    {
        return $this->belongsTo('mradang\LaravelCustomField\Models\CustomFieldGroup', 'group_id');
    }

    public function values()
    {
        return $this->hasMany('mradang\LaravelCustomField\Models\CustomFieldValue', 'field_id');
    }
}
