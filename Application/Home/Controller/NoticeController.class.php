<?php

namespace Home\Controller;

use Think\Exception;

class NoticeController extends \Base\Controller\BaseController
{
	//通知列表
	public function noticeList(){
		$admin_id = $_SESSION['adminInfo']['id'];
		$role_id = $_SESSION['adminInfo']['role_id'];
		if ($role_id != '0') {
        	$where['n.shop_id'] = $admin_id;
        }
        $join_a = "hyz_user AS u ON u.user_id = n.user_id";
        $order = 'n.add_time desc';
        $field = 'n.*,u.*';
		$notice_list = M('notice')->alias('n')->join($join_a)->field($field)->where($where)->order($order)->select();
		foreach ($notice_list as $key => $value) {
			$notice_list[$key]['admin_name'] = M('admin')->where(array('id' => $value['shop_id']))->getField('nick_name');
			switch ($value['notice_status']) {
				case '1':
					$notice_list[$key]['notice_status_name'] = '已发布';
					break;
				case '2':
					$notice_list[$key]['notice_status_name'] = '已删除';
					break;
			}
		}
    	$this->assign('list',$notice_list)->display();
	}

	//创建通知
	public function createNotice(){
		$data['shop_id'] = $_SESSION['adminInfo']['id'];
		//权限判断
		$data['notice_title'] = $_POST['notice_title'];
		$data['content'] = $_POST['content'];
		$data['type'] = $_POST['type'];
		$data['user_id'] = $_POST['user_id'];//通知用户 未0则是全部用户
		$data['add_time'] = time();
		if ($data['content']) {
			$res = M('notice')->add($data);
		}
		if ($res) {
			$this->success('新增成功', 'productList');
		}else{
			$this->error('新增失败');
		}
		
	}

	//删除通知
}