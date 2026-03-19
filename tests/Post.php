<?php

namespace mradang\LaravelCustomField\Test;

use Illuminate\Database\Eloquent\Model;
use mradang\LaravelCustomField\Traits\CustomFieldTrait;

class Post extends Model
{
    use CustomFieldTrait;

    protected $fillable = ['name'];

    public static function customFieldBaseFields()
    {
        return [
            'name' => '标题',
        ];
    }
}
