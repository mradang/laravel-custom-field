<?php

namespace mradang\LumenCustomField\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait CustomFieldControllerTrait {

    abstract protected function customFieldModel();

    public function saveFieldGroup(Request $request) {
        $validatedData = $this->validate($request, [
            'id' => 'required|integer',
            'name' => [
                'required',
                'string',
                'not_in:默认分组,保留字段',
                'name' => Rule::unique('custom_field_groups')->where(function ($query) {
                    $query->where('model', $this->customFieldModel());
                })->ignore($request->input('id')),
            ],
        ], [
            'name.not_in' => '分组名无效',
            'name.required' => '分组名必填',
            'name.unique' => '分组名已存在',
        ]);

        extract($validatedData);

        if ($id) {
            return $this->customFieldModel()::customFieldGroupUpdate($id, $name);
        } else {
            return $this->customFieldModel()::customFieldGroupCreate($name);
        }
    }

    public function getFieldGroups() {
        return $this->customFieldModel()::customFieldGroups();
    }

    public function deleteFieldGroup(Request $request) {
        $validatedData = $this->validate($request, [
            'id' => 'required|integer',
        ]);

        extract($validatedData);

        try {
            $this->customFieldModel()::customFieldGroupDelete($id);
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function sortFieldGroups(Request $request) {
        $data = json_decode($request->getContent(), true);
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        $this->customFieldModel()::customFieldGroupSaveSort($sorts);
    }

    public function saveField(Request $request) {
        $validatedData = $this->validate($request, [
            'id' => 'required|integer',
            'name' => [
                'required',
                'string',
                Rule::unique('custom_fields')->where(function ($query) {
                    $query->where('model', $this->customFieldModel());
                })->ignore($request->input('id')),
            ],
            'type' => 'required|integer|min:1',
            'options' => 'nullable|array',
            'group_id' => 'required|integer',
        ], [
            'name.unique' => '字段名已存在',
        ]);

        extract($validatedData);

        if ($id) {
            return $this->customFieldModel()::customFieldUpdate($id, $name, $type, $options, $group_id);
        } else {
            return $this->customFieldModel()::customFieldCreate($name, $type, $options, $group_id);
        }
    }

    public function getFields() {
        return $this->customFieldModel()::customFields();
    }

    public function deleteField(Request $request) {
        $validatedData = $this->validate($request, [
            'id' => 'required|integer',
        ]);

        extract($validatedData);

        $this->customFieldModel()::customFieldDelete($id);
    }

    public function sortFields(Request $request) {
        $data = json_decode($request->getContent(), true);
        $validator = validator(['sorts' => $data], [
            'sorts.*.id' => 'required|integer|min:1',
            'sorts.*.sort' => 'required|integer',
        ]);
        $sorts = $validator->validate()['sorts'];
        $this->customFieldModel()::customFieldSaveSort($sorts);
    }

    public function moveField(Request $request) {
        $validatedData = $this->validate($request, [
            'id' => 'required|integer',
            'group_id' => 'required|integer',
        ]);

        extract($validatedData);

        return $this->customFieldModel()::customFieldMove($id, $group_id);
    }

}