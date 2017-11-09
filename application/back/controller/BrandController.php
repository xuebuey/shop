<?php
/**
 * Created by PhpStorm.
 * User: THINK
 * Date: 2017/11/4
 * Time: 14:39
 */

namespace app\back\controller;

use app\back\model\Brand;
use app\back\validate\BrandValidate;
use think\Config;
use think\Controller;
use think\Db;
use think\Session;
use think\Validate;

class BrandController extends Controller
{
    /**
     * 设置（添加和更新）动作
     */
    public function setAction($id = null)
    {
        #完成自定义权限的校验
        if (is_null($id)){
            checkPrivRedirect('brand-create');
        }else{
            checkPrivRedirect('brand-edit');
        }
        $this->assign('id', $id);

        # 获取请求对象
        $request = request();

        # get, 展示表单
        if ($request->isGet()) {

            ## 获取session中的消息，并分配到模板
            $message = Session::get('message') ?: [];
            $this->assign('message', $message);
            // 上次错误数据与当前正在编辑的数据进行整合
            $data = Session::get('data') ?: (!is_null($id)?Db::name('brand')->find($id) : []);
            $this->assign('data', $data);

            ## 展示视图模板
            return $this->fetch();
        }

        # post, 处理数据
        elseif ($request->isPost()) {
            ## 验证
            $brand_validate = new BrandValidate();
            // 验证失败
            if (! $brand_validate->batch(true)->check($request->post())) {
                return $this->redirect('set', ['id'=>$id], 302, [
                    'message' => $brand_validate->getError(),
                    'data' => $request->post(),
                ]);
            }

            ## 添加到数据库
            if (is_null($id)) {
                $model = new Brand();
            } else {
                $model = Brand::get($id);
            }
            $model->data($request->post());
            $result = $model->allowField(true)->save();

//            sleep(5);
            if ($result) {
//                重定向列表
                return $this->redirect('index');
            } else {
//                重定向到创建表单
                return $this->redirect('set', ['id'=>$id]);
            }
        }

    }

    /**
     * 创建动作
     */
    public function createAction()
    {
        # 获取请求对象
        $request = request();

        # get, 展示表单
        if ($request->isGet()) {

            ## 获取session中的消息，并分配到模板
            $message = Session::get('message') ?: [];
            $this->assign('message', $message);
            // 上次错误数据
            $this->assign('data', Session::get('data') ?: []);

            ## 展示视图模板
            return $this->fetch();
        }

        # post, 处理数据
        elseif ($request->isPost()) {
            ## 验证
            $brand_validate = new BrandValidate();
            // 验证失败
            if (! $brand_validate->batch(true)->check($request->post())) {
                return $this->redirect('create', [], 302, [
                    'message' => $brand_validate->getError(),
                    'data' => $request->post(),
                ]);
            }

            ## 添加到数据库
            $model = new Brand();
            $model->data($request->post());
            $result = $model->save();
            if ($result) {
//                重定向列表
                return $this->redirect('index');
            } else {
//                重定向到创建表单
                return $this->redirect('create');
            }
        }

    }

    /**
     * 首页
     * @return string
     */
    public function indexAction()
    {
        # 先获取空的查询构建器
        $builder = Brand::where(null);

        # 条件
//        $filter = input('filter/a', []);
//        $filter_order = [];
//        ## 判断是否具有title条件
//        if (isset($filter['title']) && '' !== $filter['title']) {
//            $builder->where('title', 'like', '%'. $filter['title'].'%');
//            $filter_order['filter[title]'] = $filter['title'];
//        }
//        ## 判断是否具有site条件
//        if (isset($filter['site']) && '' !== $filter['site']) {
//            $builder->where('site', 'like', '%'. $filter['site'].'%');
//            $filter_order['filter[site]'] = $filter['site'];
//        }
//        ## 搜索条件分配到模板
//        $this->assign('filter', $filter);
//        $this->assign('filter_order', $filter_order);
        $filter = [];
        ## 判断是否具有title条件
        $filter_title = input('filter_title', '');
        if ('' !== $filter_title) {
            $builder->where('title', 'like', '%'. $filter_title.'%');
            $filter['filter_title'] = $filter_title;
        }
        ## 判断是否具有site条件
        $filter_site = input('filter_site', '');
        if ('' !== $filter_site) {
            $builder->where('site', 'like', '%'. $filter_site.'%');
            $filter['filter_site'] = $filter_site;
        }
        ## 搜索条件分配到模板
        $this->assign('filter', $filter);

        # 排序
        $order_field = input('order_field', '');
        $order_type = input('order_type', 'asc');
        if ('' !== $order_field) {
            $builder->order([$order_field => $order_type]);
        }
        $order = compact('order_field', 'order_type');
        $this->assign('order', $order);

        # 分页
        $limit = 1;

        # 检索数据
        $paginator = $builder->paginate($limit, false, [
            'query' => array_merge($filter, $order),
        ]);
        $this->assign('paginator', $paginator);
        // 起始记录
        $this->assign('start', $paginator->listRows()*($paginator->currentPage()-1)+1);
        // 结束记录
        $this->assign('end', min($paginator->listRows()*$paginator->currentPage(), $paginator->total()));

        # 渲染模板
        return $this->fetch();
    }

    /**
     * 校验标题唯一性
     */
    public function titleUniqueCheckAction()
    {
        // ajax请求默认会转为json，但是我们需要的是字符串，将默认响应类型改为html
        Config::set('default_ajax_return', 'html');

        # 校验是否唯一，利用验证器校验
        return Validate::unique(null, 'brand,title', input(), 'title') ? 'true' : 'false';
    }

    /**
     * 批量操作
     */
    public function multiAction()
    {
        $selected = input('selected/a', []);
        if (empty($selected)) {
            return $this->redirect('index');
        }

        # 批量删除
        Brand::destroy($selected);

        return $this->redirect('index');
    }
}