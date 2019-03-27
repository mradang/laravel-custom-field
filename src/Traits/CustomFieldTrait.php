<?php

namespace mradang\LumenCustomField\Traits;

use Illuminate\Validation\Rule;

use mradang\LumenCustomField\Services\ModelService;
use mradang\LumenCustomField\Services\GroupService;
use mradang\LumenCustomField\Services\FieldService;
use mradang\LumenCustomField\Services\ValueService;

trait CustomFieldTrait {

    public static function customFieldGroups() {
        return GroupService::all(__CLASS__);
    }

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

    public static function customFieldGroupDelete($id) {
        return GroupService::delete(__CLASS__, $id);
    }

    public static function customFieldGroupSaveSort(array $data) {
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        return GroupService::saveSort($sorts);
    }

    public static function customFields() {
        return FieldService::all(__CLASS__);
    }

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

    public static function customFieldDelete($id) {
        return FieldService::delete(__CLASS__, $id);
    }

    public static function customFieldSaveSort(array $data) {
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        return FieldService::saveSort($sorts);
    }

    // data: 每项需包含两个属性：field_id, value
    public function customFieldSaveData(array $data) {
        $validator = validator(['values' => $data], [
            'values.*.field_id' => 'required|integer|min:1',
            'values.*.value' => 'required|string|nullable',
        ]);
        $ret = $validator->validate();
        return ValueService::save(__CLASS__, $this->getKey(), $ret['values']);
    }

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
