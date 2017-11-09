<?php
/**
 * Created by PhpStorm.
 * User: THINK
 * Date: 2017/11/7
 * Time: 16:07
 */

namespace app\back\controller;


use think\Controller;

class SiteController extends Controller
{

    public function indexAction()
    {
        return $this->fetch();
    }

}