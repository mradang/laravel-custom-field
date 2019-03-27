<?php

namespace mradang\LumenCustomField\Traits;

use Illuminate\Validation\Rule;

use mradang\LumenCustomField\Services\ModelService;
use mradang\LumenCustomField\Services\GroupService;
use mradang\LumenCustomField\Services\FieldService;
use mradang\LumenCustomField\Services\ValueService;

trait CustomFieldTrait {

    // 获取字段分组
    public static function customFieldGroups() {
        return GroupService::all(__CLASS__);
    }

    // 创建字段分组
    public static function customFieldGroupCreate($name) {
        $validator = validator(['name' => $name], [
            'name' => Rule::unique('custom_field_groups')->where(function ($query) {
                $query->where('model', __CLASS__);
            }),
        ], [
            'name.unique' => '字段分组已存在',
        ]);
        $passes = $validator->validate();
        return GroupService::create(__CLASS__, $passes['name']);
    }

    // 更新字段分组
    public static function customFieldGroupUpdate($id, $name) {
        $validator = validator(['name' => $name], [
            'name' => Rule::unique('custom_field_groups')->where(function ($query) {
                $query->where('model', __CLASS__);
            })->ignore($id),
        ], [
            'name.unique' => '字段分组已存在',
        ]);
        $passes = $validator->validate();
        return GroupService::update(__CLASS__, $id, $name);
    }

    // 删除字段分组
    public static function customFieldGroupDelete($id) {
        return GroupService::delete(__CLASS__, $id);
    }

    // 字段分组排序
    public static function customFieldGroupSaveSort(array $data) {
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        return GroupService::saveSort($sorts);
    }

    // 获取字段
    public static function customFields() {
        return FieldService::all(__CLASS__);
    }

    // 创建字段
    public static function customFieldCreate($name, $type, array $options = [], $group_id = 0) {
        $validator = validator(compact('name', 'type', 'options', 'group_id'), [
            'name' => Rule::unique('custom_fields')->where(function ($query) {
                $query->where('model', __CLASS__);
            }),
            'type' => 'required|integer',
            'options' => 'array',
            'group_id' => 'integer',
        ], [
            'name.unique' => '字段名已存在',
        ]);

        $passes = $validator->validate();
        return FieldService::create(
            __CLASS__,
            array_get($passes, 'group_id', 0),
            $passes['name'],
            $passes['type'],
            array_get($passes, 'options', [])
        );
    }

    // 修改字段
    public static function customFieldUpdate($id, $name, $type, array $options = [], $group_id = 0) {
        $validator = validator(compact('id', 'name', 'type', 'options', 'group_id'), [
            'name' => Rule::unique('custom_fields')->where(function ($query) {
                $query->where('model', __CLASS__);
            })->ignore($id),
            'type' => 'required|integer',
            'options' => 'array',
            'group_id' => 'integer',
        ], [
            'name.unique' => '字段名已存在',
        ]);

        $passes = $validator->validate();
        return FieldService::update(
            __CLASS__,
            array_get($passes, 'group_id', 0),
            $id,
            $passes['name'],
            $passes['type'],
            array_get($passes, 'options', [])
        );
    }

    // 删除字段
    public static function customFieldDelete($id) {
        return FieldService::delete(__CLASS__, $id);
    }

    // 字段排序
    public static function customFieldSaveSort(array $data) {
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        return FieldService::saveSort($sorts);
    }

    // 保存定制字段数据
    // data: 每项需包含两个属性：field_id, value
    public function customFieldSaveData(array $data) {
        $validator = validator(['values' => $data], [
            'values.*.field_id' => 'required|integer|min:1',
            'values.*.value' => 'required|string|nullable',
        ]);
        $ret = $validator->validate();
        return ValueService::save(__CLASS__, $this->getKey(), $ret['values']);
    }

    // 取定制字段数据
    // Attribute: customFieldData
    public function getCustomFieldDataAttribute() {
        return ValueService::get(__CLASS__, $this->getKey());
    }

    public static function bootCustomFieldTrait() {
        static::deleting(function($model) {
            ValueService::delete(__CLASS__, $model->id);
        });
    }

}
