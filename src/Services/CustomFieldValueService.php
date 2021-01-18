<?php

namespace mradang\LaravelCustomField\Services;

use mradang\LaravelCustomField\Models\CustomFieldValue as FieldValue;
use Illuminate\Support\Str;

class CustomFieldValueService
{
    public static function save($class, $key, array $data)
    {
        $value = FieldValue::firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);
        $diff = self::diff($value->data ?? [], $data);
        $value->data = $data;
        $value->save();
        return $diff;
    }

    private static function diff(array $old, array $new)
    {
        $new_values = collect($new)->map(function ($item) {
            return ['field' . $item['field_id'] => $item['value']];
        })->collapse();
        $old_values = collect($old)->map(function ($item) {
            return ['field' . $item['field_id'] => $item['value']];
        })->collapse();
        $diff = $new_values->diffAssoc($old_values);
        return $diff->map(function($value, $key) use ($old_values) {
            return [
                'field_id' => Str::after($key, 'field'),
                'old_value' => $old_values->get($key),
                'new_value' => $value,
            ];
        })->values();
    }

    public static function saveItem($class, $key, array $item)
    {
        $value = FieldValue::firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        $data = $value->data ?: [];
        $pos = -1;
        foreach ($data as $index => $row) {
            if ($row['field_id'] === $item['field_id']) {
                $data[$index] = $item;
                $pos = $index;
            }
        }
        if ($pos === -1) {
            $data[] = $item;
        }

        $value->data = $data;
        $value->save();
        return $value;
    }

    public static function getItem($class, $key, $field_id)
    {
        $value = FieldValue::firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);

        $data = $value->data ?: [];
        $pos = -1;
        foreach ($data as $index => $row) {
            if ($row['field_id'] === $field_id) {
                $pos = $index;
                break;
            }
        }
        return $pos === -1 ? null : $data[$pos]['value'];
    }

    public static function get($class, $key)
    {
        $value = FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->first();
        return $value ? $value->data : [];
    }

    public static function delete($class, $key)
    {
        FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->delete();
    }
}
