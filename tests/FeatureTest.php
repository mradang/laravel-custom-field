<?php

namespace Tests;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testBasicFeatures()
    {
        // 字段分组
        $this->assertTrue(in_array('通用字段', User::customFieldBaseGroups()));

        $group1 = User::customFieldGroupCreate('基本信息');
        $this->assertSame(1, $group1->id);
        $group2 = User::customFieldGroupEnsureExists('家庭信息');
        User::customFieldGroupUpdate($group2->id, '家庭信息update');
        $group3 = User::customFieldGroupEnsureExists('test');

        $groups = User::customFieldGroups();
        $this->assertSame(3, $groups->count());

        $sqls = $this->getQueryLog(function () {
            User::customFieldGroupEnsureExists('家庭信息update');
        });
        $this->assertSame(1, $sqls->count());

        User::customFieldGroupDelete($group3->id);
        $this->assertSame(2, User::customFieldGroups()->count());

        // 字段
        $this->assertTrue(in_array('姓名', User::customFieldBaseFields()));

        $field1 = User::customFieldCreate('生日', 3, [], $group1->id, false);
        $this->assertSame(1, $field1->id);
        User::customFieldUpdate($field1->id, '出生日期', 3, [], $group1->id, true);
        $field2 = User::customFieldCreate('婚姻状况', 2, ['未婚', '已婚'], $group2->id, false);

        $this->assertSame(2, User::customFields()->count());
        $this->assertSame(1, User::customFieldsByGroupId($group1->id)->count());
        User::customFieldMove($field2->id, $group1->id);
        $this->assertSame(2, User::customFieldsByGroupId($group1->id)->count());

        // 模型
        $user1 = User::create(['name' => 'user1']);
        $this->assertSame(1, $user1->id);
        $user1->customFieldSaveData([
            [
                'field_id' => $field1->id,
                'value' => '2020-07-08',
            ],
            [
                'field_id' => $field2->id,
                'value' => '未婚',
            ]
        ]);
        $this->assertEquals('未婚', $user1->customFieldGetDataItem($field2->id));

        $user1->customFieldSaveDataItem($field2->id, '已婚');
        $this->assertEquals('已婚', $user1->customFieldGetDataItem($field2->id));

        $this->assertSame(
            [
                [
                    'field_id' => $field1->id,
                    'value' => '2020-07-08',
                ],
                [
                    'field_id' => $field2->id,
                    'value' => '已婚',
                ]
            ],
            $user1->customFieldData
        );

        $user1_field_data = $user1->customFieldValues->pop()->data;
        $field2_value = Arr::first($user1_field_data, function ($value) use ($field2) {
            return $value['field_id'] === $field2->id;
        });
        $this->assertEquals('已婚', $field2_value['value']);

        $user1->customFieldClearValues();
        $this->assertSame([], $user1->customFieldData);
    }

    protected function getQueryLog(\Closure $callback): \Illuminate\Support\Collection
    {
        $sqls = \collect([]);
        DB::listen(function ($query) use ($sqls) {
            $sqls->push(['sql' => $query->sql, 'bindings' => $query->bindings]);
        });

        $callback();

        return $sqls;
    }
}
