<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/08
 * Time: 20:50
 */

namespace app\back\controller;

use app\back\model\Action;
use app\back\model\Role;
use app\back\validate\RoleValidate;
use think\Controller;
use think\Db;
use think\Session;

class RoleController extends Controller
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
            $data = Session::get('data') ?: (!is_null($id)?Db::name('role')->find($id) : []);
            $this->assign('data', $data);

            ## 展示视图模板
            return $this->fetch();
        }

        # post, 处理数据
        elseif ($request->isPost()) {
            ## 验证
            $validate = new RoleValidate();
            // 验证失败
            if (! $validate->batch(true)->check($request->post())) {
                return $this->redirect('set', ['id'=>$id], 302, [
                    'message' => $validate->getError(),
                    'data' => $request->post(),
                ]);
            }

            ## 添加到数据库
            if (is_null($id)) {
                $model = new Role();
            } else {
                $model = Role::get($id);
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
        $builder = Role::where(null);

        # 条件
        $filter = [];
        
        ## 判断是否具有title条件
        $filter_title = input('filter_title', '');
        if ('' !== $filter_title) {
            $builder->where('title', 'like', '%'. $filter_title.'%');
            $filter['filter_title'] = $filter_title;
        }

        ## 判断是否具有description条件
        $filter_description = input('filter_description', '');
        if ('' !== $filter_description) {
            $builder->where('description', 'like', '%'. $filter_description.'%');
            $filter['filter_description'] = $filter_description;
        }

        ## 判断是否具有is_super条件
        $filter_is_super = input('filter_is_super', '');
        if ('' !== $filter_is_super) {
            $builder->where('is_super', 'like', '%'. $filter_is_super.'%');
            $filter['filter_is_super'] = $filter_is_super;
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
        Role::destroy($selected);

        return $this->redirect('index');
    }
    /*
     * 权限管理
     */
    public function setActionAction($id)
    {
        #角色ID
        $this -> assign('id',$id);
        $role = Role::get($id);
        $this -> assign('role',$role);
        #当前角色基本权限
        $owner_actions = Db::name('role_action')->where('role_id',$id)->column('action_id');
        $request = request();

        #展示当前具备的权限
        if($request -> isGet()){
            ##全部权限动作
            $this -> assign('action_list',Action::order('title')->select());
            ##当前角色具备的权限动作
            $this -> assign('checked_list',$owner_actions);

            return $this -> fetch();

        }
        elseif ($request->isPost()){

            ##本次提交的权限id
            $actions = input('actions/a',[]);

            ##更新 role_action表
            ###确定需要删除
            $deletes = array_diff($owner_actions,$actions);//差集
            Db::name('role_action') -> where([
                'role_id' => $id,
                'action_id' => ['in',$deletes],
            ])-> delete();
            ###确定要插入的
            $inserts = array_diff($actions,$owner_actions);//差集
            $rows = array_map(function ($action_id)use ($id){
                //use 将外部作用域变量导入到当前作用域
                return [
                    'action_id' => $action_id,
                    'role_id' => $id,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
            },$inserts);
            Db::name('role_action')->insertAll($rows);

            return $this -> redirect('setAction',['id'=>$id]);
        }
    }
}