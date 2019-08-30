# lumen custom field

为模型增加字段定制功能

## 安装
```
composer require mradang/lumen-custom-field
```

## 配置
1. 修改 bootstrap\app.php 文件
```php
// 注册 ServiceProvider
$app->register(mradang\LumenCustomField\LumenCustomFieldServiceProvider::class);
```

## 控制器
引入 CustomFieldControllerTrait
```php
use mradang\LumenCustomField\Traits\CustomFieldControllerTrait;
```

- 获取定制模型类
```php
abstract protected function customFieldModel();
```

- 保留字段分组名（array）
```php
protected function customFieldExcludeGroups();
```

- 保留字段名（array）
```php
protected function customFieldExcludeFields();
```

- 保存字段分组
```php
saveFieldGroup(Request $request)
[id, name]
```

- 获取字段分组
```php
getFieldGroups()
```

- 删除字段分组
```php
deleteFieldGroup(Request $request)
[id]
```

- 字段分组排序
```php
sortFieldGroups(Request $request)
[{id, sort}]
```

- 保存字段
```php
saveField(Request $request)
[id, name, type, options, group_id]
```

- 获取字段
```php
getFields()
```

- 删除字段
```php
deleteField(Request $request)
[id]
```

- 字段排序
```php
sortFields(Request $request)
[{id, sort}]
```

- 字段移动
```php
moveField(Request $request)
[id, group_id]
```

## 模型
引入 CustomFieldTrait
```php
use mradang\LumenCustomField\Traits\CustomFieldTrait;
```

- 获取字段分组
```php
Model::customFieldGroups()
```

- 创建字段分组
```php
Model::customFieldGroupCreate($name)
```

- 确保字段分组存在
```php
Model::customFieldGroupEnsureExists($name)
```

- 更新字段分组
```php
Model::customFieldGroupUpdate($id, $name)
```

- 删除字段分组
```php
Model::customFieldGroupDelete($id)
```

- 字段分组排序
```php
Model::customFieldGroupSaveSort(array $data)
```

- 获取字段
```php
Model::customFields()
```

- 按分组获取字段
```php
Model::customFieldsByGroupId($id, $name)
```

- 创建字段
```php
Model::customFieldCreate($name, $type, array $options = [], $group_id = 0)
```

- 修改字段
```php
Model::customFieldUpdate($id, $name, $type, array $options = [], $group_id = 0)
```

- 删除字段
```php
Model::customFieldDelete($id)
```

- 字段排序
```php
Model::customFieldSaveSort(array $data)
```

- 字段移动
```php
Model::customFieldMove($id, $group_id)
```

- 保存定制字段数据
```php
$model->customFieldSaveData(array $data)
```

- 保存单个定制字段数据
```php
$model->customFieldSaveDataItem(int $field_id, $value)
```

- 获取单个定制字段数据
```php
$model->customFieldGetDataItem(int $field_id)
```

- 取定制字段数据
```php
array $model->customFieldData
```

- 清理字段值
```php
$model->customFieldClearValues()
```

## 异常
- mradang\LumenCustomField\CustomFieldException

## 添加的数据表迁移
- custom_field_groups
- custom_fields
- custom_field_values

## 根据需要增加路由
```php
$router->post('getFieldGroups', 'XXXXController@getFieldGroups');
$router->post('getFields', 'XXXXController@getFields');

$router->post('saveFieldGroup', 'XXXXController@saveFieldGroup');
$router->post('deleteFieldGroup', 'XXXXController@deleteFieldGroup');
$router->post('sortFieldGroups', 'XXXXController@sortFieldGroups');
$router->post('saveField', 'XXXXController@saveField');
$router->post('deleteField', 'XXXXController@deleteField');
$router->post('sortFields', 'XXXXController@sortFields');
$router->post('moveField', 'XXXXController@moveField');
```