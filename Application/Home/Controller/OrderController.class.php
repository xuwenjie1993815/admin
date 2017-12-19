<?php

namespace Home\Controller;

use Think\Exception;

class OrderController extends \Base\Controller\BaseController
{
	public function orderList(){
		//获取用户信息
		$admin_id = $_SESSION['adminInfo']['id'];
		$order_status = $_POST['order_status'];
		$order_sn = $_POST['order_sn'];
       
        //有三种类型的商品  商品抽奖订单（关联product period order）  活动抽奖订单（apply order activity）  点赞抽奖订单（apply order activity）
        //商品抽奖订单
        $join_a = "hyz_product AS p ON o.order_product_id = p.product_id";
        $join_b = "hyz_period AS pe ON o.order_product_id = pe.p_id";
        $order = "o.order_time desc";
        if ($admin_id != '1') {
        	$where['p.shop_id'] = $admin_id;
        }
        if ($order_sn) {
        	$where['o.order_sn'] = $order_sn;
        }
        $where['pe.status_period'] = 1;
        $where['o.order_type'] = 1;//商品抽奖订单
        $res = M('order')->alias("o")->join($join_a)->join($join_b)->field('o.*, pe.* , p.product_name ,p.price ,p.product_info,p.images')->where($where)->order($order)->select();
        //列表需要数据 订单title order_info(类型  数量  金额  图片  时间  状态)   order_id order_img  order_price order_sn order_time 
        foreach ($res as $k => $v){
            $data[$k]['order_id'] = $v['order_id'];//order_id
            $data[$k]['order_sn'] = $v['order_sn'];//title
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
        if ($admin_id != '1') {
        $where_apply['ac.shop_id'] = $admin_id;
     	}
     	if ($order_sn) {
        	$where_apply['o.order_sn'] = $order_sn;
        }
        $where_apply['o.order_type'] = 2;//参与活动订单
        $field_apply = 'o.*, ac.*,a.*';
        $res_apply = M('order')->alias("o")->join($join_a)->join($join_b)->field($field_apply)->where($where_apply)->order($order)->select();
        $data_apply = array();
        foreach ($res_apply as $k => $v){
            $data_apply[$k]['order_id'] = $v['order_id'];//order_id
            $data_apply[$k]['order_sn'] = $v['order_sn'];
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
            $data_dz[$k]['order_sn'] = $v['order_sn'];
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
        foreach ($order_list as $key => $value) {
        	switch ($value['order_type']) {
        		case '1':
        			$order_list[$key]['order_type'] = '商品订单';
        			break;
        		case '2':
        			$order_list[$key]['order_type'] = '活动订单';
        			break;
        		case '3':
        			$order_list[$key]['order_type'] = '点赞订单';
        			break;
        	}
        }
        // var_dump($order_list);die;
        $this->assign('list',$order_list)->display();
       	// var_dump($order_list);die;
	}
}