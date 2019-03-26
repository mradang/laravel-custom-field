<?php

namespace mradang\LumenCustomField\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldGroup extends Model {

    protected $fillable = [
        'model',
        'name',
        'sort',
    ];

    public function fields() {
        return $this->hasMany('mradang\LumenCustomField\Models\CustomField', 'group_id');
    }

}
