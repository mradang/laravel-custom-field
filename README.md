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

## 引入
模型引入 CustomFieldTrait
```php
use mradang\LumenCustomField\Traits\CustomFieldTrait;
```

## 使用
- 获取字段分组
```php
Model::customFieldGroups()
```

- 创建字段分组
```php
Model::customFieldGroupCreate($name)
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

- 保存定制字段数据
```php
$model->customFieldSaveData(array $data)
```

- 取定制字段数据
```php
array $model->customFieldData
```

## 添加的数据表迁移
- custom_field_groups
- custom_fields
- custom_field_values