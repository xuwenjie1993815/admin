<?php

namespace Home\Controller;

use Think\Exception;

class NoticeController extends \Base\Controller\BaseController
{
	//通知列表
	public function noticeList(){
        $admin_name = $_POST['amdin_name'];
        $type = $_POST['type'];
        $notice_status = (string)$_POST['notice_status'];
		$admin_id = $_SESSION['adminInfo']['id'];
		$role_id = $_SESSION['adminInfo']['role_id'];
		if ($role_id != '0') {
        	$where['shop_id'] = $admin_id;
        }
        if ($type) {
            $where['type'] = $type;
        }
        if ($notice_status == '0' || $notice_status == '1') {
            $where['notice_status'] = $notice_status;
        }

        //如果是超级管理员则可以查找所有管理员发布的信息
        if ($role_id == '0') {
            if ($admin_name) {
                $admin_id = M('admin')->where(array('nick_name' => $admin_name))->getField('id');
                $where['shop_id'] = $admin_id;
            }
        }
        // $join_a = "hyz_user AS u ON u.user_id = n.user_id";
        $order = 'add_time desc';
        // $field = 'n.*,u.*';
		$notice_list = M('notice')->where($where)->order($order)->select();
		foreach ($notice_list as $key => $value) {
			$notice_list[$key]['admin_name'] = M('admin')->where(array('id' => $value['shop_id']))->getField('nick_name');
            if ($value['user_id']) {
                $notice_user_info = M('user')->field('user_name,real_name')->where(array('user_id' => $value['user_id']))->find();
                $notice_list[$key]['user_name'] = $notice_user_info['user_name'];
                $notice_list[$key]['real_name'] = $notice_user_info['real_name'];
            }else{
                $notice_list[$key]['user_name'] = '全部用户';
                $notice_list[$key]['real_name'] = '全部用户';
            }
            
			switch ($value['notice_status']) {
				case '1':
					$notice_list[$key]['notice_status_name'] = '已发布';
					break;
				case '0':
					$notice_list[$key]['notice_status_name'] = '已删除';
					break;
			}
		}
        $this->assign('admin_name',$admin_name);
        $this->assign('type',$type);
        $this->assign('notice_status',$notice_status);
    	$this->assign('list',$notice_list)->display();
	}

	//创建通知
	public function createNotice(){
		if (empty($_POST)) {
    		$this->display();
    		die;
    	}
    	if (!$_POST['notice_title']) {
       	$data['msg'] = '标题必填';
       	$this->error($data['msg']);
    	}

    	//标题长度限制
    	if (strlen($_POST['notice_title']) > 60) {
    		$data['msg'] = '标题最多30字';
			$this->error($data['msg']);
    	}
		if (!$_POST['content']) {
       	$data['msg'] = '内容必填';
		$this->error($data['msg']);
    	}

        
        $data['shop_id'] = $_SESSION['adminInfo']['id'];
        //权限判断
        $data['notice_title'] = $_POST['notice_title'];
        $data['content'] = $_POST['content'];
        $data['type'] = $_POST['type'];
        $data['add_time'] = time();
    	//指定用户
    	if ($_POST['user_tel']) {
            //判断多个用户
            if (count(explode(',',$_POST['user_tel'])) > 1) {
                foreach (explode(',',$_POST['user_tel']) as $key => $value) {
                    $user_info = '';
                    $user_info = M('user')->where(array('tel' => $value,'status' => '1'))->find();
                    // if (!$user_info) {
                    //     $data['msg'] = '指定发送用户不存在或已注销';
                    //     $this->error($data['msg']);
                    // }
                    $data['user_id'] = $user_info['user_id'];
                    $res = M('notice')->add($data);
                }
            }else{
                $user_info = M('user')->where(array('tel' => $_POST['user_tel'],'status' => '1'))->find();
                if (!$user_info) {
                    $data['msg'] = '指定发送用户不存在或已注销';
                    $this->error($data['msg']);
                }
                $data['user_id'] = $user_info['user_id'];
                $res = M('notice')->add($data);
            }
    		
    	}

		
		if ($res) {
			$this->success('新增成功', 'noticeList');
		}else{
			$this->error('新增失败');
		}
		
	}

	//通知详情页
    function noticeInfo(){
        $id = $_GET['notice_id'];
        $notice_info = M('notice')->where("notice_id = '$id'")->find();
        switch ($notice_info['type']) {
        	case '1':
        		$notice_info['type_name'] = '中奖通知';
        		break;
        	case '2':
        		$notice_info['type_name'] = '公告';
        		break;
        }
        switch ($notice_info['notice_status']) {
        	case '1':
        		$notice_info['status_name'] = '通知已送达';
        		break;
        	case '2':
        		$notice_info['status_name'] = '通知已删除';
        		break;
        }
        if ($notice_info['user_id']) {
            $user_info = M('user')->where(array('user_id' => $notice_info['user_id']))->find();
            $notice_info['user_name'] = $user_info['real_name']?:$user_info['user_name'];
        }else{
            $notice_info['user_name'] = '全部用户';
        }
        
        // $notice_type = getNoticeType();
        // $notice_info['type'] = $notice_type[$notice_info['type']];
        $notice_info['admin_name'] = M('admin')->where(array('id' =>  $notice_info['shop_id']))->getField('nick_name');
        // var_dump($notice_info);die;
        $this->assign('notice_info',$notice_info);
        $this->display();
    }

    //删除通知
   	public function delNotice(){
   		$return_data =array();
        $return_data['status'] = 0;
        if(isset($_POST['id'])){
            $id = I('post.id');
            $rst = M('notice')->where(array('notice_id'=>$id))->save(array('notice_status'=>0));
            if($rst){
                $return_data['status'] = 1;
                $return_data['msg'] = "删除成功";
                echo json_encode($return_data);die();
            }else{
                $return_data['msg'] = "删除失败";
                echo json_encode($return_data);die();
            }
        }else{
            $return_data['msg'] = "参数错误";
            echo json_encode($return_data);die();
        }
   	}
}