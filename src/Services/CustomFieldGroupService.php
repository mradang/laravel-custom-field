<?php

namespace mradang\LaravelCustomField\Services;

use mradang\LaravelCustomField\Models\CustomFieldGroup as Group;

class CustomFieldGroupService
{
    public static function create($class, $name)
    {
        $group = new Group([
            'model' => $class,
            'name' => $name,
        ]);
        $group->sort = Group::where(['model' => $class])->max('sort') + 1;
        $group->save();

        return $group;
    }

    public static function ensureExists($class, $name)
    {
        $group = Group::firstOrNew([
            'model' => $class,
            'name' => $name,
        ]);
        if (! $group->exists) {
            $group->sort = Group::where(['model' => $class])->max('sort') + 1;
            $group->save();
        }

        return $group;
    }

    public static function all($class)
    {
        return Group::where('model', $class)->orderBy('sort')->get();
    }

    public static function allWithFields($class, $group_id)
    {
        $query = Group::where('model', $class);
        if (\is_array($group_id)) {
            $query->whereIn('id', $group_id);
        } else {
            $query->where('id', $group_id);
        }

        return $query->with(['fields' => function ($query) {
            $query->orderBy('sort');
        }])->orderBy('sort')->get();
    }

    public static function update($class, $id, $name)
    {
        $group = Group::findOrFail($id);
        if ($group->model === $class) {
            $group->name = $name;
            $group->save();

            return $group;
        }
    }

    public static function delete($class, $id)
    {
        $group = Group::withCount('fields')->findOrFail($id);
        if ($group->fields_count) {
            abort(400, '分组下存在字段，不能删除！');
        }
        if ($group->model === $class) {
            return $group->delete();
        }
    }

    // 保存排序值，data中的项目需要2个属性：id, sort
    public static function saveSort(array $data)
    {
        foreach ($data as $item) {
            Group::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }
    }
}
