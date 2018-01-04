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
		if (!$_POST) {
			$this->display();die;
		} 
		
		//活动图片，标题，内容。参与价格，点赞价格，参与活动奖励，点赞活动奖励
		$_POST['stop_time'] ? $stop_time = strtotime($_POST['stop_time']) : $stop_time = NULL;
		if ($_FILES['images']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            foreach ($images as $k => $v) {
               $path_all[]='http://'.$_SERVER['HTTP_HOST'].$config_path.$v;
            }
            $images = implode(',', $path_all);
        }else{
        	$data['msg'] = '缺少参数';
       		$this->error($data['msg']);
        }
        //判断数据
        if ((!$_POST['title'] AND !$_POST['activity_name']) || !$_POST['target_num'] || !$_POST['stop_time'] || !$_POST['price'] || !$_POST['like_price'] || !$_POST['like_info']) {
        	$data['msg'] = '缺少参数';
       		$this->error($data['msg']);
        }
		$data['title'] = $_POST['title']?:$_POST['activity_name'];
		$data['activity_name'] = $_POST['activity_name']?:$_POST['title'];
		$data['images'] = $images;
		$data['target_num'] = $_POST['target_num'];
		$data['stop_time'] = $stop_time;
		$data['price'] = $_POST['price'];
		$data['like_price'] = $_POST['like_price'];
		$data['like_info'] = $_POST['like_info'];
		$data['activity_type'] = $_POST['activity_type'];
		$data['activity_info'] = $_POST['activity_info'];
		$data['shop_id'] = $_SESSION['adminInfo']['id'];
		$data['ctime'] = time();
		M('activity')->add($data);
		$this->success('添加成功', 'activityList');
	}

	//编辑活动参数
	public function activityInfoEdit(){
		$activity_id = $_POST['activity_id'];
	}

	//删除活动
	public function delActivity(){
		$activity_id = $_POST['id'];
		$activity_info = M('activity')->where(array('activity_id' => $activity_id))->find();
		if ($activity_info['status'] == 0) {
			$data['msg'] = '活动已删除';
       		$this->error($data['msg']);
		}
		$ret = M('activity')->where(array('activity_id' => $activity_id))->save(array('status' => 0));
		if ($ret) {
			$this->success('删除成功', 'activityList');
		}else{
			$data['msg'] = '删除失败';
       		$this->error($data['msg']);
		}
	}
}