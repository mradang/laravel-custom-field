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
// 指定定制模型类（未重载该函数时，默认使用控制器同名模型）
customFieldModel()
// 获取基本字段
getBaseFields()
// 获取基本分组信息
getBaseGroups()
// 保存字段分组
saveFieldGroup(Request $request) // [id, name]
// 获取字段分组
getFieldGroups()
// 获取字段分组带字段
getFieldGroupsWithFields() // [ids]
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
// 模型基本字段定义
Model::customFieldBaseFields() // [field => label, ...]
// 模型基本分组信息
Model::customFieldBaseGroups() // [base => '基本组名', default => '默认组名']
// 定制字段名在所有组中唯一
Model::customFieldGloballyUnique(): bool // false
// 定制字段在指定组内唯一（默认是字段所在的组内唯一）
Model::customFieldUniqueWithinGroupIds($group_id): array // [$group_id]
// 获取字段分组
Model::customFieldGroups()
// 获取字段分组带字段
Model::customFieldGroupsWithFields($group_id)
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
Model::customFieldsByGroupId($id)
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

关联关系
- customFieldValues

### 根据需要增加路由

```php
Route::post('getBaseFields', 'XXXXController@getBaseFields');
Route::post('getBaseGroups', 'XXXXController@getBaseGroups');
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

### 模型删除时自动清理定制字段值

```php
protected static function boot()
{
    parent::boot();
    static::deleting(function ($model) {
        $model->customFieldClearValues();
    });
}
```
