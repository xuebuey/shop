<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/08
 * Time: 20:46
 */

namespace app\back\controller;

use app\back\model\Action;
use app\back\validate\ActionValidate;
use think\Controller;
use think\Db;
use think\Session;

class ActionController extends Controller
{
    /**
     * 设置（添加和更新）动作
     */
    public function setAction($id = null)
    {

        $this->assign('id', $id);

        # 获取请求对象
        $request = request();

        # get, 展示表单
        if ($request->isGet()) {

            ## 获取session中的消息，并分配到模板
            $message = Session::get('message') ?: [];
            $this->assign('message', $message);
            // 上次错误数据与当前正在编辑的数据进行整合
            $data = Session::get('data') ?: (!is_null($id)?Db::name('action')->find($id) : []);
            $this->assign('data', $data);

            ## 展示视图模板
            return $this->fetch();
        }

        # post, 处理数据
        elseif ($request->isPost()) {
            ## 验证
            $validate = new ActionValidate();
            // 验证失败
            if (! $validate->batch(true)->check($request->post())) {
                return $this->redirect('set', ['id'=>$id], 302, [
                    'message' => $validate->getError(),
                    'data' => $request->post(),
                ]);
            }

            ## 添加到数据库
            if (is_null($id)) {
                $model = new Action();
            } else {
                $model = Action::get($id);
            }
            $model->data($request->post());
            $result = $model->allowField(true)->save();

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
     * 首页
     * @return string
     */
    public function indexAction()
    {
        # 先获取空的查询构建器
        $builder = Action::where(null);

        # 条件
        $filter = [];
        
        ## 判断是否具有title条件
        $filter_title = input('filter_title', '');
        if ('' !== $filter_title) {
            $builder->where('title', 'like', '%'. $filter_title.'%');
            $filter['filter_title'] = $filter_title;
        }

        ## 判断是否具有rule条件
        $filter_rule = input('filter_rule', '');
        if ('' !== $filter_rule) {
            $builder->where('rule', 'like', '%'. $filter_rule.'%');
            $filter['filter_rule'] = $filter_rule;
        }

        ## 判断是否具有description条件
        $filter_description = input('filter_description', '');
        if ('' !== $filter_description) {
            $builder->where('description', 'like', '%'. $filter_description.'%');
            $filter['filter_description'] = $filter_description;
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
        $limit = 10;

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
     * 批量操作
     */
    public function multiAction()
    {
        $selected = input('selected/a', []);
        if (empty($selected)) {
            return $this->redirect('index');
        }

        # 批量删除
        Action::destroy($selected);

        return $this->redirect('index');
    }
}