<?php

namespace Tests;

use Illuminate\Http\Request;
use mradang\LaravelCustomField\Traits\CustomFieldControllerTrait;

class UserController extends Controller
{
    use CustomFieldControllerTrait;

    // 定制字段模型
    protected function customFieldModel()
    {
        return User::class;
    }
}
