<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/07
 * Time: 14:39
 */

namespace app\back\model;

use think\Model;

class Admin extends Model
{

    // 自动完成
    protected $auto = ['password'];

    /**
     * 密码的自动完成方法
     */
    protected function setPasswordAttr($value) {
        return md5($value);
    }
}
