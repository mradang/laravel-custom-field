# laravel custom field

为模型增加字段定制功能

## 安装
```
composer require mradang/laravel-custom-field
```

## 添加的内容

### 添加的数据表迁移
- custom_field_groups
- custom_fields
- custom_field_values

## 定制字段功能

### 控制器 Trait
```php
use mradang\LaravelCustomField\Traits\CustomFieldControllerTrait;
```

增加以下内容：

```php
// 获取定制模型类
abstract protected function customFieldModel();
// 保留字段分组名（array）
protected function customFieldExcludeGroups();
// 保留字段名（array）
protected function customFieldExcludeFields();
// 保存字段分组
saveFieldGroup(Request $request) // [id, name]
// 获取字段分组
getFieldGroups()
// 删除字段分组
deleteFieldGroup(Request $request) // [id]
// 字段分组排序
sortFieldGroups(Request $request) // [{id, sort}]
// 保存字段
saveField(Request $request) // [id, name, type, options, group_id]
// 获取字段
getFields()
// 删除字段
deleteField(Request $request) // [id]
// 字段排序
sortFields(Request $request) // [{id, sort}]
// 字段移动
moveField(Request $request) // [id, group_id]
```

### 模型 Trait
```php
use mradang\LaravelCustomField\Traits\CustomFieldTrait;
```

增加以下内容
```php
// 获取字段分组
Model::customFieldGroups()
// 创建字段分组
Model::customFieldGroupCreate($name)
// 确保字段分组存在
Model::customFieldGroupEnsureExists($name)
// 更新字段分组
Model::customFieldGroupUpdate($id, $name)
// 删除字段分组
Model::customFieldGroupDelete($id)
// 字段分组排序
Model::customFieldGroupSaveSort(array $data)
// 获取字段
Model::customFields()
// 按分组获取字段
Model::customFieldsByGroupId($id, $name)
// 创建字段
Model::customFieldCreate($name, $type, array $options = [], $group_id = 0)
// 修改字段
Model::customFieldUpdate($id, $name, $type, array $options = [], $group_id = 0)
// 删除字段
Model::customFieldDelete($id)
// 字段排序
Model::customFieldSaveSort(array $data)
// 字段移动
Model::customFieldMove($id, $group_id)
// 保存定制字段数据
$model->customFieldSaveData(array $data)
// 保存单个定制字段数据
$model->customFieldSaveDataItem(int $field_id, $value)
// 获取单个定制字段数据
$model->customFieldGetDataItem(int $field_id)
// 取定制字段数据
array $model->customFieldData
// 清理字段值
$model->customFieldClearValues()
```

### 异常
- mradang\LaravelCustomField\Exceptions\CustomFieldException

### 根据需要增加路由
```php
Route::post('getFieldGroups', 'XXXXController@getFieldGroups');
Route::post('getFields', 'XXXXController@getFields');

Route::post('saveFieldGroup', 'XXXXController@saveFieldGroup');
Route::post('deleteFieldGroup', 'XXXXController@deleteFieldGroup');
Route::post('sortFieldGroups', 'XXXXController@sortFieldGroups');
Route::post('saveField', 'XXXXController@saveField');
Route::post('deleteField', 'XXXXController@deleteField');
Route::post('sortFields', 'XXXXController@sortFields');
Route::post('moveField', 'XXXXController@moveField');
```