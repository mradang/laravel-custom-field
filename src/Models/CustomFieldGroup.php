<?php

namespace mradang\LaravelCustomField\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldGroup extends Model
{
    protected $fillable = [
        'model',
        'name',
        'sort',
    ];

    public function fields()
    {
        return $this->hasMany(CustomField::class, 'group_id');
    }
}
