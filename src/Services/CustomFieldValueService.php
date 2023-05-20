<?php

namespace mradang\LaravelCustomField\Services;

use Illuminate\Support\Arr;
use mradang\LaravelCustomField\Models\CustomFieldValue as FieldValue;
use Illuminate\Support\Str;

class CustomFieldValueService
{
    public static function save($class, $key, array $data)
    {
        $value = FieldValue::orderByDesc('no')->firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        $diff = self::diff($value->data, $data);

        FieldValue::create([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
            'no' => ($value->no ?: 0) + 1,
            'data' => $data,
        ]);

        return $diff;
    }

    private static function diff(array $old, array $new)
    {
        $new_values = collect($new)->mapWithKeys(function ($item) {
            return ['field' . $item['field_id'] => $item['value']];
        });
        $old_values = collect($old)->mapWithKeys(function ($item) {
            return ['field' . $item['field_id'] => $item['value']];
        });

        $diff = $new_values->diffAssoc($old_values);

        return $diff->map(function ($value, $key) use ($old_values) {
            return [
                'field_id' => Str::after($key, 'field'),
                'old_value' => $old_values->get($key),
                'new_value' => $value,
            ];
        })->values();
    }

    public static function saveItem($class, $key, array $item)
    {
        $value = FieldValue::orderByDesc('no')->firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        $data = $value->data;
        $pos = collect($data)->search(fn ($row) => $row['field_id'] === $item['field_id']);

        if ($pos === false) {
            $data[] = $item;
        } else {
            $data[$pos] = $item;
        }

        return FieldValue::create([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
            'no' => ($value->no ?: 0) + 1,
            'data' => $data,
        ]);
    }

    public static function getItem($class, $key, $field_id)
    {
        $value = FieldValue::orderByDesc('no')->firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        $row = collect($value->data)->firstWhere('field_id', $field_id);

        return Arr::get($row, 'value');
    }

    public static function get($class, $key)
    {
        $value = FieldValue::orderByDesc('no')->firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        return $value->data;
    }

    public static function delete($class, $key)
    {
        FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->delete();
    }
}
