<?php

namespace mradang\LaravelCustomField\Services;

use mradang\LaravelCustomField\Models\CustomField as Field;
use mradang\LaravelCustomField\Models\CustomFieldGroup as Group;

class CustomFieldService
{
    public static function create($class, $group_id, $name, $type, $options, $required)
    {
        $field = new Field([
            'model' => $class,
            'group_id' => $group_id,
            'name' => $name,
            'type' => $type,
            'options' => $options,
            'required' => $required,
            'sort' => Field::where([
                'model' => $class,
                'group_id' => $group_id,
            ])->max('sort') + 1,
        ]);
        $field->save();
        return $field;
    }

    public static function all($class)
    {
        return Field::where('model', $class)->orderBy('sort')->get();
    }

    public static function getByGroupId($class, $group_id)
    {
        return Field::where([
            'model' => $class,
            'group_id' => $group_id,
        ])->orderBy('sort')->get();
    }

    public static function update($class, $group_id, $id, $name, $type, $options, $required)
    {
        $field = Field::where([
            'model' => $class,
            'group_id' => $group_id,
            'id' => $id,
        ])->firstOrFail();

        $field->name = $name;
        $field->type = $type;
        $field->options = $options;
        $field->required = $required;

        $field->save();
        return $field;
    }

    public static function delete($class, $id)
    {
        $field = Field::findOrFail($id);
        if ($field->model === $class) {
            $field->delete();
        }
    }

    // 保存排序值，data中的项目需要2个属性：id, sort
    public static function saveSort(array $data)
    {
        foreach ($data as $item) {
            Field::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }
    }

    public static function move($class, $id, $group_id)
    {
        $field = Field::findOrFail($id);
        if ($field->model !== $class) {
            abort(400, '非法参数');
        }

        if ($group_id) {
            $group = Group::findOrFail($group_id);
            if ($group->model !== $class) {
                abort(400, '非法参数');
            }
        }

        // 检查目标组是否有同名字段
        $exists = Field::where([
            'model' => $class,
            'group_id' => $group_id,
            'name' => $field->name,
        ])->exists();
        if ($exists) {
            abort(400, '目标分组下存在同名字段！');
        }

        $field->group_id = $group_id;
        $field->sort = Field::where([
            'model' => $class,
            'group_id' => $group_id,
        ])->max('sort') + 1;
        $field->save();
        return $field;
    }
}
