<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/08
 * Time: 20:46
 */

namespace app\back\validate;

use think\Validate;

class ActionValidate extends Validate
{
    // 规则数组
    protected $rule = [
        ## 令牌校验
        '__token__' => 'require|token',
        # 自定义规则
    ];

    // 字段名称翻译
    protected $field = [
        'id' => 'ID',
        'title' => '权限',
        'rule' => '规则',
        'description' => '描述',
        'sort' => '排序',
        'create_time' => '创建时间',
        'update_time' => '修改时间',

    ];
}