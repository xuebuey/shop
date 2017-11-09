<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: %date%
 * Time: %time%
 */

namespace app\back\validate;

use think\Validate;

class %validate% extends Validate
{
    // 规则数组
    protected $rule = [
        ## 令牌校验
        '__token__' => 'require|token',
        # 自定义规则
    ];

    // 字段名称翻译
    protected $field = [
%label_list%
    ];
}