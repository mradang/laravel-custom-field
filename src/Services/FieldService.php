<?php

namespace mradang\LumenCustomField\Services;

use mradang\LumenCustomField\Models\CustomField as Field;

class FieldService {

    public static function create($class, $group_id, $name, $type, $options) {
        $field = new Field([
            'model' => $class,
            'group_id' => $group_id,
            'name' => $name,
            'type' => $type,
            'options' => $options,
            'sort' => Field::where([
                'model' => $class,
                'group_id' => $group_id,
            ])->max('sort') + 1,
        ]);
        $field->save();
        return $field;
    }

    public static function all($class) {
        return Field::where('model', $class)->orderBy('sort')->get();
    }

    public static function update($class, $group_id, $id, $name, $type, $options) {
        $field = Field::where([
            'model' => $class,
            'group_id' => $group_id,
            'id' => $id,
        ])->firstOrFail();

        $field->name = $name;
        $field->type = $type;
        $field->options = $options;

        $field->save();
        return $field;
    }

    public static function delete($class, $id) {
        $field = Field::findOrFail($id);
        if ($field->model === $class) {
            $field->delete();
        }
    }

    // 保存排序值，data中的项目需要2个属性：id, sort
    public static function saveSort(array $data) {
        foreach ($data as $item) {
            Field::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }
    }

}