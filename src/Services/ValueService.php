<?php

namespace mradang\LumenCustomField\Services;

use mradang\LumenCustomField\Models\CustomFieldValue as FieldValue;

class ValueService {

    public static function save($class, $key, array $data) {
        $value = FieldValue::firstOrNew([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ]);
        $value->data = $data;
        $value->save();
        return $value;
    }

    public static function get($class, $key) {
        $value = FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->first();
        return $value ? $value->data : [];
    }

    public static function clear($class, $key) {
        FieldValue::where([
            'valuetable_type' => $class,
            'valuetable_id' => $key,
        ])->delete();
    }

}
