<?php
/**
 * Created by PhpStorm.
 * User: THINK
 * Date: 2017/11/7
 * Time: 16:22
 */

namespace app\back\behavior;


use priv\Privilege;
use think\Session;

class CheckAuth
{
    /**
     * @param $params
     * @return mixed
     */
    public function run(& $params)
    {
        $request = request();
        # 特例的跳过
        $except = [
            // 控制器 => [动作数组]
            'admin' => ['login', 'captcha'],
        ];
        if (isset($except[strtolower($request->controller())])) {
            // 当前控制器中存在特例动作
            if (in_array($request->action(), $except[strtolower($request->controller())])) {
                // 动作是特例
                // 不需要校验
                return;
            }
        }

        # 登录校验
        if (!Session::has('admin')) {
            # 未登录
            ## 记录之前的URL
            Session::set('route', $request->path());
            redirect('back/admin/login')->send();
            die;
        }

        #校验权限
        if (!Privilege::checkPriv($request->path())) {
            #未授权
            redirect('back/admin/login', [], '302', [
                'message' => '没有权限',
            ])->send();
            die;
        }
    }

}