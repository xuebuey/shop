<?php
/**
 * Created by PhpStorm.
 * User: THINK
 * Date: 2017/11/5
 * Time: 10:43
 */

use think\Config;
/**
 * back，生成带有排序参数的URL
 * @param array $order 当前排序参数
 * @param string $field 需要排序链接字段
 */
function urlOrder($route, $params=[], $order=[], $field=null)
{
    // 将参数变为普通参数?这种， 因为数组型参数 pathinfo不支持解析
    Config::set('url_common_param', true);
    # 计算排序参数
    // 已经按照该字段，进行升序排序，那么形成降序。否则都是升序。
    $params['order_field'] =  $field;
    $params['order_type'] = (isset($order['order_field']) && isset($order['order_type'])&& $order['order_field']==$field && 'asc'==$order['order_type']) ? 'desc' : 'asc';

    # 利用url生成链接
    return url($route, $params);
}

function classOrder($order=[], $field=null)
{
    if (! isset($order['order_field']) || !isset($order['order_type'])) return '';

    if ($order['order_field'] == $field) {
        return 'asc' == $order['order_type'] ? 'desc' : 'asc';
    } else {
        return '';
    }
}
/*
 * 授权认证助手函数
 */
function checkPrivRedirect($rules,$admin_id=null,$logic='and')
{
    if(!\priv\Privilege::checkPriv($rules,$admin_id=null,$logic='and')){
        redirect('back/admin/login',[],'302',[
            'message' => '没有权限执行'
        ])->send();
        die;
    }
}
function checkPriv($rules,$admin_id=null,$logic='and'){
    return \priv\Privilege::checkPriv($rules,$admin_id,$logic);
}