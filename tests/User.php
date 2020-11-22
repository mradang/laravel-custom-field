<?php

namespace mradang\LaravelCustomField\Test;

use Illuminate\Database\Eloquent\Model;
use mradang\LaravelCustomField\Traits\CustomFieldTrait;

class User extends Model
{
    use CustomFieldTrait;

    protected $fillable = ['name'];

    public static function customFieldBaseFields()
    {
        return [
            'name' => '姓名',
            'phone' => '手机',
        ];
    }

    public static function customFieldBaseGroups()
    {
        return [
            'base' => '基本字段',
            'default' => '通用字段',
        ];
    }
}
