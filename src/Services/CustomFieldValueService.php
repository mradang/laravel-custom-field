<?php

namespace mradang\LaravelCustomField\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use mradang\LaravelCustomField\Models\CustomFieldValue as FieldValue;

class CustomFieldValueService
{
    public static function save($class, $key, array $data): Collection
    {
        $oldValues = FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])
            ->orderBy('field_id')
            ->get()
            ->map(function ($item) {
                return ['field_id' => $item->field_id, 'field_value' => $item->field_value];
            })
            ->toArray();

        $diff = self::diff($oldValues, $data);

        collect($data)->each(function ($item) use ($class, $key) {
            FieldValue::updateOrCreate([
                'valuetable_type' => $class,
                'valuetable_id' => $key,
                'field_id' => $item['field_id'],
            ], [
                'field_value' => $item['field_value'],
            ]);
        });

        return $diff;
    }

    private static function diff(array $old, array $new): Collection
    {
        $new_values = collect($new)->mapWithKeys(function ($item) {
            return ['field' . $item['field_id'] => $item['field_value']];
        });
        $old_values = collect($old)->mapWithKeys(function ($item) {
            return ['field' . $item['field_id'] => $item['field_value']];
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
        return FieldValue::updateOrCreate([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
            'field_id' => $item['field_id'],
        ], [
            'field_value' => $item['field_value'],
        ]);
    }

    public static function getItem($class, $key, $field_id)
    {
        return FieldValue::firstWhere([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
            'field_id' => $field_id,
        ])?->field_value;
    }

    public static function get($class, $key): array
    {
        return FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])
            ->orderBy('field_id')
            ->get()
            ->map(function ($item) {
                return ['field_id' => $item->field_id, 'field_value' => $item->field_value];
            })
            ->toArray();
    }

    public static function delete($class, $key)
    {
        FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->delete();
    }
}
