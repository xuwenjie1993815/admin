<?php

namespace Home\Controller;

use Think\Exception;

class ActivityController extends \Base\Controller\BaseController
{
	//活动列表
	public function activityList(){
		$admin_id = $_SESSION['adminInfo']['id'];
		$role_id = $_SESSION['adminInfo']['role_id'];
		//如果是超级管理员
        if ($role_id != '0') {
            $where['a.shop_id'] = $admin_id;
        }
        $join = 'hyz_admin AS ad ON ad.id = a.shop_id';

        $activity_list = M('activity')->alias('a')->join($join)->field('a.*,ad.nick_name')->where($where)->order('a.ctime desc')->select();
        // var_dump($activity_list);die;
        // foreach ($activity_list as $key => $value) {
        // }
        $this->assign('list',$activity_list);
		$this->display();
	}

	//添加活动
	public function activityAdd(){
		$activity_info = $_POST['activity_info'];
		$this->display();
	}

	//编辑活动参数
	public function activityInfoEdit(){
		$activity_id = $_POST['activity_id'];
	}
}