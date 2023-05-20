<?php

namespace mradang\LaravelCustomField\Test;

use Illuminate\Http\Request;
use mradang\LaravelCustomField\Traits\CustomFieldControllerTrait;

class UserController extends Controller
{
    use CustomFieldControllerTrait;

    // 定制字段模型
    public function customFieldModel()
    {
        return User::class;
    }
}
