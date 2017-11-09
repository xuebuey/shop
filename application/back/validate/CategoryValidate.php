<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/09
 * Time: 16:03
 */

namespace app\back\validate;

use think\Validate;

class CategoryValidate extends Validate
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
        'title' => '分类',
        'parent_id' => '上级分类',
        'sort' => '排序',
        'is_used' => '启用',
        'meta_title' => 'SEO标题',
        'meta_keywords' => 'SEO关键字',
        'meta_description' => 'SEO描述',
        'create_time' => '创建时间',
        'update_time' => '修改时间',

    ];
}