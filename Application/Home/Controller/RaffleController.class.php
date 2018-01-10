<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 1:28
 */

namespace Home\Controller;


use Base\Controller\BaseController;
use Base\Logic\PubLogic;


class RaffleController extends \Think\Controller
{
	//商品抽奖
	public function index()
	{
		$admin_id= session('adminInfo');
		$shop_id= $admin_id['id'];
		if ($admin_id['role_id']==0) {
			$where = "target_num=now_num and status_period=1";
			$where1 ="target_num=now_num and status_period=1";
		}else{
			$where = "target_num=now_num and status_period=1 and shop_id=$shop_id";
			$where1 ="target_num=now_num and status_period=1 and a.shop_id=$shop_id";
		}
		$count = M('period')->where($where)->count();
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $show= $Page->show();// 分页显示输出
		$res = M('period')->alias("a")->field('period_id,product_name,product_info,period_time,create_time')->join("left join hyz_product as b on a.p_id = b.product_id")->where($where1)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$res);
        $this->assign('page',$show);
		$this->display();
	}
	//商品开奖
	public function prize()
	{
		$period_id = I('period_id');
		$period_id = addslashes($period_id);
		$info = M('period')->where(array('period_id'=>$period_id))->find();
		$product_id = $info['p_id'];
		$period_time = $info['period_time'];
		$order_begin = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id,'order_status'=>1))->order('order_id')->limit(5)->select();
		$order_end = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id,'order_status'=>1))->order('order_id desc')->limit(5)->select();
		foreach ($order_begin as $k => $v) {
			$begin .= substr($order_begin[$k]['tel'], -2);
		}
		foreach ($order_end as $k => $v) {
			$end .= substr($order_end[$k]['tel'], -2);
		}
		$like = ($begin+$end)%$info['target_num'];
		$winCode = 10000001+$like;//中奖码 10000025
		$winUser = M('order')->field('user_id,order_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id,'lottery_code'=>array("like","%$winCode%")))->select();
		foreach ($winUser as $k => $v) {
			$winUser[$k]['period_id'] = $period_id;
			$winUser[$k]['ctime'] = time();
			$winUser[$k]['reward_number'] = $winCode;
			$winUser[$k]['shop_name'] = '永筹科技';
			$winUser[$k]['shop_address'] = '永川文理学院北门';
			$winUser[$k]['win_type'] = '1';
			$order_id[] = $v['order_id'];
		}
		if ($winUser) {
			$win = M('reward')->addAll($winUser);
		}
		//修改期数的状态
		$res =M('period')->where(array('period_id'=>$period_id))->save(array('status_period'=>2));
		if ($res) {
			$map['order_id']  = array('in',$order_id);
			$update= M('order')->where($map)->save(array('order_status'=>2));
			$map1['order_id']  = array('not in',$order_id);
			$update1= M('order')->where($map1)->where(array('period_time'=>$period_time,'order_product_id'=>$product_id,'order_status'=>1))->save(array('order_status'=>5));
		}
		if ($res) {
			$data = array(
                'code'=>0,
                'msg'=>'抽奖成功',
                );
           $this->ajaxReturn($data);
		}else{
			$data = array(
                'code'=>1,
                'msg'=>'抽奖失败',
                );
           $this->ajaxReturn($data);
		}
	}
	//活动开奖
	public function activitiesDraw()
	{
		$admin_id= session('adminInfo');
		$shop_id= $admin_id['id'];
		if ($admin_id['role_id']==0) {
			$where = "target_num=now_num and status=1";
		}else{
			$where = "target_num=now_num and status=1 and shop_id=$shop_id";
		}
		$count = M('activity')->where($where)->count();
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $show= $Page->show();// 分页显示输出
		$res = M('activity')->where($where)->order('ctime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$res);
        $this->assign('page',$show);
		$this->display();
	}
	public function activitiesDraw_run()
	{
		$activity_id = I('activity_id');
		$activity_id = addslashes($activity_id);
		$info = M('activity')->where(array('activity_id'=>$activity_id))->find();
		// $product_id = $info['p_id'];
		// $period_time = $info['period_time'];
		$order_begin = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('activity_id'=>$activity_id,'order_type'=>2,'order_status'=>1))->order('order_id')->limit(5)->select();
		$order_end = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('activity_id'=>$activity_id,'order_type'=>2,'order_status'=>1))->order('order_id desc')->limit(5)->select();
		foreach ($order_begin as $k => $v) {
			$begin .= substr($order_begin[$k]['tel'], -2);
		}
		foreach ($order_end as $k => $v) {
			$end .= substr($order_end[$k]['tel'], -2);
		}
		$like = ($begin+$end)%$info['target_num'];
		$winCode = 10000001+$like;//中奖码 10000025
		$winUser = M('order')->field('user_id,order_id')->where(array('activity_id'=>$activity_id,'order_type'=>2,'lottery_code'=>array("like","%$winCode%")))->select();
		foreach ($winUser as $k => $v) {
			$winUser[$k]['period_id'] = $activity_id;
			$winUser[$k]['ctime'] = time();
			$winUser[$k]['reward_number'] = $winCode;
			$winUser[$k]['shop_name'] = '永筹科技';
			$winUser[$k]['shop_address'] = '永川文理学院北门';
			$winUser[$k]['win_type'] = '3';
			$order_id[] = $v['order_id'];
		}
		if ($winUser) {
			$win = M('reward')->addAll($winUser);
		}
		//if ($win) {
			//修改期数的状态
			$res =M('activity')->where(array('activity_id'=>$activity_id))->save(array('status'=>2));
		//}
		if ($res) {
			$map['order_id']  = array('in',$order_id);
			$update= M('order')->where($map)->save(array('order_status'=>2));
			$map1['order_id']  = array('not in',$order_id);
			$update1= M('order')->where($map1)->where(array('activity_id'=>$activity_id,'order_type'=>2,'order_status'=>1))->save(array('order_status'=>5));
		}
		if ($res) {
			$data = array(
                'code'=>0,
                'msg'=>'抽奖成功',
                );
           $this->ajaxReturn($data);
		}else{
			$data = array(
                'code'=>1,
                'msg'=>'抽奖失败',
                );
           $this->ajaxReturn($data);
		}
	}

	//点赞开奖
	public function likeDraw()
	{
		$admin_id= session('adminInfo');
		$shop_id= $admin_id['id'];
		$count = M('apply')->where("like_num>=500 and is_draw!=2")->count();
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('prev','');
		$Page->setConfig('next','');
		$show= $Page->show();// 分页显示输出
		$res = M('apply')->alias("a")->field('a.apply_id,b.activity_name,a.other_info,a.like_num,a.ctime')->join("left join hyz_activity as b on a.activity_id = b.activity_id")->where("like_num>=500 and is_draw!=2")->order('a.ctime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($res as $k=> $v) {
			if (!$v['like_num']) {
				$res[$k]['like_num']=0;
			}
			$res[$k]['performance'] = json_decode($v['other_info'],1);
			$res[$k]['performance'] = $res[$k]['performance']['qumu'];
			unset($res[$k]['other_info']);
		}
		//var_dump($res);die;
		$this->assign('list',$res);
		$this->assign('page',$show);
		$this->display();
	}
	public function likeDraw_run()
	{
		$apply_id = I('apply_id');
		$res = M('apply')->field('like_userid')->where(array('apply_id'=>$apply_id))->find();
		$len = strlen($res['like_userid'])-2;
		$user_id=  substr($res['like_userid'], 1,$len);
		$arr_userid = explode(',', $user_id);
		$rand = array_rand($arr_userid);
		$win_user = $arr_userid[$rand];
		//var_dump($win_user);
		$winCode = 10000001+rand(10,99);//没用
		$order_id = M('order')->field('order_id')->where(array('user_id'=>$win_user,'apply_id'=>$apply_id))->find();
		$winUser['user_id'] = $win_user;
		$winUser['order_id'] = $order_id;
		$winUser['period_id'] = $apply_id;
		$winUser['ctime'] = time();
		$winUser['reward_number'] = $winCode;
		$winUser['shop_name'] = '永筹科技';
		$winUser['shop_address'] = '永川文理学院北门';
		$winUser['win_type'] = '2';
		$win = M('reward')->add($winUser);
		if ($win) {
			//修改期数的状态
			$res =M('apply')->where(array('apply_id'=>$apply_id))->save(array('is_draw'=>2));
		}
		if ($res) {
			$data = array(
                'code'=>0,
                'msg'=>'抽奖成功',
                );
           $this->ajaxReturn($data);
		}else{
			$data = array(
                'code'=>1,
                'msg'=>'抽奖失败',
                );
           $this->ajaxReturn($data);
		}
	}
}