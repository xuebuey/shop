<?php
/**
 * Created by PhpStorm.
 * User: Kang
 * Date: 2017/11/09
 * Time: 16:03
 */

namespace app\back\model;

use think\Model;

class Category extends Model
{
    //
    public function getTree()
    {
        #获取全部分类
        $rows = $this -> order('sort') -> select();

        # 递归树状排序
        $tree = $this -> tree($rows,0,0);

        return $tree;
    }
    protected function tree($rows,$id=0,$level=0)
    {
        static $tree = [];
        foreach($rows as $row) {
            if ($id == $row['parent_id']){
                $row['level'] = $level;
                $tree[]=$row;
                $this -> tree($rows,$row['id'],$level+1);
            }
        }
        return $tree;

    }

}
