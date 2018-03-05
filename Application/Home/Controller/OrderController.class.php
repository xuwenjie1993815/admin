<?php

namespace Home\Controller;

use Think\Exception;

class OrderController extends \Base\Controller\BaseController
{
	public function orderList(){
		//获取用户信息
		$admin_id = $_SESSION['adminInfo']['id'];
		$role_id = $_SESSION['adminInfo']['role_id'];
		$order_status = $_POST['order_status'];
		$order_sn = $_POST['order_sn'];
        $tel = $_POST['tel'];
        $user_id = M('user')->where(array('tel' => $tel))->getfield('user_id');
        //有三种类型的商品  商品抽奖订单（关联product period order）  活动抽奖订单（apply order activity）  点赞抽奖订单（apply order activity）
        //商品抽奖订单
        $join_a = "hyz_product AS p ON o.order_product_id = p.product_id";
        $join_b = "hyz_period AS pe ON o.order_product_id = pe.p_id";
        $order = "o.order_time desc";
        if ($role_id != '0') {
        	$where['p.shop_id'] = $admin_id;
        }
        if ($order_sn) {
        	$where['o.order_sn'] = $order_sn;
        }
        if ($user_id) {
            $where['o.user_id'] = $user_id;
        }
        $where['pe.status_period'] = 1;
        $where['o.order_type'] = 1;//商品抽奖订单
        $res = M('order')->alias("o")->join($join_a)->join($join_b)->field('o.*, pe.* , p.product_name ,p.price ,p.product_info,p.images')->where($where)->order($order)->select();
        //列表需要数据 订单title order_info(类型  数量  金额  图片  时间  状态)   order_id order_img  order_price order_sn order_time 
        $data = array();
        foreach ($res as $k => $v){
            $data[$k]['order_id'] = $v['order_id'];//order_id
            $data[$k]['tel'] = $v['tel'];//tel
            $data[$k]['order_sn'] = $v['order_sn'];//title

            $data[$k]['province'] = $v['province'];//province
            $data[$k]['city'] = $v['city'];//city
            $data[$k]['county'] = $v['county'];//county
            $data[$k]['address'] = $v['address'];//address

            $data[$k]['title'] = $v['period_name'];//title
            $data[$k]['product_name'] = $v['product_name'];
            $data[$k]['images'] = $v['images'];//商品图片
            $data[$k]['order_price'] = $v['order_money'];//金额
            $data[$k]['product_num'] = $v['product_num'];//数量
            $data[$k]['period_time'] = $v['period_time'];//活动期数
            $data[$k]['order_status'] = $v['order_status'];//订单状态
            $data[$k]['order_time'] = $v['order_time'];//订单时间
            $data[$k]['order_type'] = $v['order_type'];//订单类型
        }
        
        //活动抽奖订单apply order activity
        $join_a = "hyz_apply AS a ON a.order_id = o.order_id";
        $join_b = "hyz_activity AS ac ON ac.activity_id = o.activity_id";
        $order = "o.order_time desc";
        $where_apply['a.apply_type'] = 1;
        if ($role_id != '0') {
        $where_apply['ac.shop_id'] = $admin_id;
     	}
     	if ($order_sn) {
        	$where_apply['o.order_sn'] = $order_sn;
        }
        if ($user_id) {
            $where_apply['o.user_id'] = $user_id;
        }
        $where_apply['o.order_type'] = 2;//参与活动订单
        $field_apply = 'o.*, ac.*,a.*';
        $res_apply = M('order')->alias("o")->join($join_a)->join($join_b)->field($field_apply)->where($where_apply)->order($order)->select();
        $data_apply = array();
        foreach ($res_apply as $k => $v){
            $data_apply[$k]['order_id'] = $v['order_id'];//order_id
            $data_apply[$k]['tel'] = $v['tel'];//tel
            $data_apply[$k]['order_sn'] = $v['order_sn'];
            $data_apply[$k]['province'] = $v['province'];//province
            $data_apply[$k]['city'] = $v['city'];//city
            $data_apply[$k]['county'] = $v['county'];//county
            $data_apply[$k]['address'] = $v['address'];//address
            $data_apply[$k]['title'] = $v['activity_name'];//title
            $data_apply[$k]['product_name'] = $v['product_name'];
            $data_apply[$k]['images'] = $v['images'];//商品图片
            $data_apply[$k]['order_price'] = $v['order_money'];//金额
            $data_apply[$k]['product_num'] = $v['product_num'];//数量
            $data_apply[$k]['period_time'] = $v['period_time'];//活动期数
            $data_apply[$k]['order_status'] = $v['order_status'];//订单状态
            $data_apply[$k]['order_time'] = $v['order_time'];//订单时间
            $data_apply[$k]['order_type'] = $v['order_type'];//订单类型
        }
        //点赞抽奖订单apply order activity
        $join_a = "hyz_apply AS a ON a.apply_id = o.apply_id";
        $where_apply['o.order_type'] = 3;//参与点赞订单
        $field_apply = 'o.*, ac.*,a.*';
        $res_dz = M('order')->alias("o")->join($join_a)->join($join_b)->field($field_apply)->where($where_apply)->order($order)->select();
        $data_dz = array();
        foreach ($res_dz as $k => $v){
            $data_dz[$k]['order_id'] = $v['order_id'];//order_id
            $data_dz[$k]['tel'] = $v['tel'];//tel
            $data_dz[$k]['order_sn'] = $v['order_sn'];
            $data_dz[$k]['province'] = $v['province'];//province
            $data_dz[$k]['city'] = $v['city'];//city
            $data_dz[$k]['county'] = $v['county'];//county
            $data_dz[$k]['address'] = $v['address'];//address
            $data_dz[$k]['title'] = $v['title'];//title
            $data_dz[$k]['product_name'] = $v['product_name'];
            $data_dz[$k]['images'] = $v['images'];//商品图片
            $data_dz[$k]['order_price'] = $v['order_money'];//金额
            $data_dz[$k]['product_num'] = $v['product_num'];//数量
            $data_dz[$k]['period_time'] = $v['period_time'];//活动期数
            $data_dz[$k]['order_status'] = $v['order_status'];//订单状态
            $data_dz[$k]['order_time'] = $v['order_time'];//订单时间
            $data_dz[$k]['order_type'] = $v['order_type'];//订单类型
        }
        $order_list = array_merge($data,$data_apply,$data_dz);
        //时间排序
        $last_names = array_column($order_list, 'order_time');
        array_multisort($last_names,SORT_DESC,$order_list);
        foreach ($order_list as $key => $value) {
        	switch ($value['order_type']) {
        		case '1':
        			$order_list[$key]['order_type_name'] = '商品订单';
        			break;
        		case '2':
        			$order_list[$key]['order_type_name'] = '活动订单';
        			break;
        		case '3':
        			$order_list[$key]['order_type_name'] = '点赞订单';
        			break;
        	}
        }
        $this->assign('tel',$tel);
        $this->assign('order_sn',$order_sn);
        $this->assign('list',$order_list)->display();
       	// var_dump($order_list);die;
	}

    public function orderDetails(){
        $order_id = $_GET['id'];
        $order_type = $_GET['order_type'];
        //有三种类型的商品  商品抽奖订单（关联product period order）  活动抽奖订单（apply order activity）  点赞抽奖订单（apply order activity）
        $where['o.order_id'] = $order_id;
        switch ($order_type) {
            case '1':
                $join_a = "hyz_product AS p ON o.order_product_id = p.product_id";
                $join_b = "hyz_period AS pe ON o.order_product_id = pe.p_id";
                $order = "o.order_time desc";
                $field = 'o.*, pe.* , p.product_name ,p.price ,p.product_info,p.images';
                $where['pe.status_period'] = 1;
                $where['o.order_type'] = 1;//商品抽奖订单
                break;
            case '2':
                $join_a = "hyz_apply AS a ON a.order_id = o.order_id";
                $join_b = "hyz_activity AS ac ON ac.activity_id = o.activity_id";
                $order = "o.order_time desc";
                $where['a.apply_type'] = 1;
                $where['o.order_type'] = 2;//参与活动订单
                $field = 'o.*, ac.*,a.*';
                break;
            case '3':
                $join_a = "hyz_apply AS a ON a.apply_id = o.apply_id";
                $join_b = "hyz_activity AS ac ON ac.activity_id = o.activity_id";
                $where['a.apply_type'] = 1;
                $where['o.order_type'] = 3;//参与点赞订单
                $field = 'o.*, ac.*,a.*';
                $order = "o.order_time desc";
                break;
        }
        $res = M('order')->alias("o")->join($join_a)->join($join_b)->field($field)->where($where)->order($order)->select();
        // var_dump($res);
        $data = array();
        foreach ($res as $k => $v){
            $data[$k]['order_id'] = $v['order_id'];//order_id
            $data[$k]['order_sn'] = $v['order_sn'];//title
            $data[$k]['province'] = $v['province'];//province
            $data[$k]['city'] = $v['city'];//city
            $data[$k]['county'] = $v['county'];//county
            $data[$k]['address'] = $v['address'];//address
            $data[$k]['tel'] = $v['tel'];//tel
            $data[$k]['period_name'] = $v['period_name'];//title
            $data[$k]['title'] = $v['title'];//title
            $data[$k]['activity_name'] = $v['activity_name'];//title
            $data[$k]['product_name'] = $v['product_name'];
            $data[$k]['images'] = $v['images'];//商品图片
            $data[$k]['order_price'] = $v['order_money'];//金额
            $data[$k]['product_num'] = $v['product_num'];//数量
            $data[$k]['period_time'] = $v['period_time'];//活动期数
            $data[$k]['order_status'] = $v['order_status'];//订单状态
            $data[$k]['order_time'] = $v['order_time'];//订单时间
            $data[$k]['order_type'] = $v['order_type'];//订单类型
        }
        foreach ($data as $key => $value) {
            switch ($value['order_type']) {
                case '1':
                    $data[$key]['order_type_name'] = '商品订单';
                    break;
                case '2':
                    $data[$key]['order_type_name'] = '活动订单';
                    break;
                case '3':
                    $data[$key]['order_type_name'] = '点赞订单';
                    break;
            }
        }
        // var_dump($data);die;
        $this->assign('data',$data[0])->display();
    }

   	public function delOrder(){
   		$return_data =array();
        $return_data['status'] = 0;
        if(isset($_POST['id'])){
            $id = I('post.id');
            $rst = M('order')->where(array('order_id'=>$id))->save(array('order_status'=>3));
            if($rst){
                $return_data['status'] = 1;
                $return_data['msg'] = "删除成功";
                echo json_encode($return_data);die();
            }else{
                $return_data['msg'] = "删除失败";
                echo json_encode($return_data);die();
            }
        }else{
            $return_data['msg'] = "请选中订单";
            echo json_encode($return_data);die();
        }
   	}
}