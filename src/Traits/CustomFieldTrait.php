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
        return GroupService::create(__CLASS__, $name);
    }

    // 更新字段分组
    public static function customFieldGroupUpdate($id, $name) {
        return GroupService::update(__CLASS__, $id, $name);
    }

    // 删除字段分组
    public static function customFieldGroupDelete($id) {
        return GroupService::delete(__CLASS__, $id);
    }

    // 字段分组排序
    public static function customFieldGroupSaveSort(array $sorts) {
        return GroupService::saveSort($sorts);
    }

    // 获取字段
    public static function customFields() {
        return FieldService::all(__CLASS__);
    }

    // 创建字段
    public static function customFieldCreate($name, $type, array $options = [], $group_id = 0) {
        return FieldService::create(
            __CLASS__,
            $group_id,
            $name,
            $type,
            $options
        );
    }

    // 修改字段
    public static function customFieldUpdate($id, $name, $type, array $options = [], $group_id = 0) {
        return FieldService::update(
            __CLASS__,
            $group_id,
            $id,
            $name,
            $type,
            $options
        );
    }

    // 删除字段
    public static function customFieldDelete($id) {
        return FieldService::delete(__CLASS__, $id);
    }

    // 字段排序
    public static function customFieldSaveSort(array $sorts) {
        return FieldService::saveSort($sorts);
    }

    // 字段移动
    public static function customFieldMove($id, $group_id) {
        return FieldService::move(__CLASS__, $id, $group_id);
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

    // 保存单个定制字段数据
    public function customFieldSaveDataItem(int $field_id, $value) {
        $validator = validator(compact('field_id', 'value'), [
            'field_id' => 'required|integer|min:1',
            'value' => 'required|string|nullable',
        ]);
        $ret = $validator->validate();
        return ValueService::saveItem(__CLASS__, $this->getKey(), $ret);
    }

    // 获取单个定制字段数据
    public function customFieldGetDataItem(int $field_id) {
        $validator = validator(compact('field_id'), [
            'field_id' => 'required|integer|min:1',
        ]);
        $ret = $validator->validate();
        return ValueService::getItem(__CLASS__, $this->getKey(), $ret['field_id']);
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
