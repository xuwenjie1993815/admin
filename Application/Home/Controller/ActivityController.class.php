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
        foreach ($activity_list as $k => $v) {
        	$activity_list[$k]['images'] = explode(',', $v['images']);
        }
        //var_dump($activity_list);die;
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
		$data['activity_info'] = strip_tags($_POST['activity_info']);
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

	//后台添加节目
	public function ac_add(){
		if ($_POST) {
			$data['activity_id'] = I('activity_id');
			$data['apply_real_name'] = I('name');
			$data['sex'] = I('sex');
			$data['age'] = I('age');
			if($this->is_idcard(I('card'))){
				$data['id_card_no'] = I('card');
			}else{
       		$this->error('请填写正确的身份证号码');
			}
			if($this->is_mobile_phone(I('tel'))){
				$data['tel'] = I('tel');
			}else{
       		$this->error('请填写正确的手机号码');
			}
			$data['company'] = I('company');
			$data['address'] = I('address');
			$data['job'] = I('job');
			$data['ctime'] = time();
			$data['apply_status'] = '1';
			$data['address'] = I('address');
			$data['apply_price'] = I('apply_price');
			$data['other_info'] = I('other_info');
			M('apply')->add($data);
			$this->success('添加成功', 'activityList');
		}else{
			$this->display();
		}
	}

	/********************php验证身份证号码是否正确函数*********************/
	function is_idcard( $id ){ 
	  $id = strtoupper($id); 
	  $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
	  $arr_split = array(); 
	  if(!preg_match($regx, $id)) 
	  { 
	    return FALSE; 
	  } 
	  if(15==strlen($id)) //检查15位 
	  { 
	    $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
	  
	    @preg_match($regx, $id, $arr_split); 
	    //检查生日日期是否正确 
	    $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
	    if(!strtotime($dtm_birth)) 
	    { 
	      return FALSE; 
	    } else { 
	      return TRUE; 
	    } 
	  } 
	  else      //检查18位 
	  { 
	    $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
	    @preg_match($regx, $id, $arr_split); 
	    $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
	    if(!strtotime($dtm_birth)) //检查生日日期是否正确 
	    { 
	      return FALSE; 
	    } 
	    else
	    { 
	      //检验18位身份证的校验码是否正确。 
	      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
	      $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
	      $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
	      $sign = 0; 
	      for ( $i = 0; $i < 17; $i++ ) 
	      { 
	        $b = (int) $id{$i}; 
	        $w = $arr_int[$i]; 
	        $sign += $b * $w; 
	      } 
	      $n = $sign % 11; 
	      $val_num = $arr_ch[$n]; 
	      if ($val_num != substr($id,17, 1)) 
	      { 
	        return FALSE; 
	      } //phpfensi.com 
	      else
	      { 
	        return TRUE; 
	      } 
	    } 
	  }
	}

	//手机号验证
	function is_mobile_phone ($mobile_phone){
	    $chars = "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/";
	    if(preg_match($chars, $mobile_phone)){
	        return true;
	    }
	    return false;
	}


}