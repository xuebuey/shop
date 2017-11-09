<?php
/**
 * Created by PhpStorm.
 * User: THINK
 * Date: 2017/11/4
 * Time: 16:05
 */

namespace app\back\validate;

use think\Validate;

class BrandValidate extends Validate
{
    // 规则数组
    protected $rule = [
        '__token__' => 'require|token',
        'title' => 'require|max:32|unique:brand,title',
        'site' => 'url|max:255',
        'sort' => 'require|integer',
    ];

    // 字段名称翻译
    protected $field = [
        'title' => '品牌',
        'site' => '官网',
        'sort' => '排序',
    ];
}