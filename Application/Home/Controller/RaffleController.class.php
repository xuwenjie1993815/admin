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
		$count = M('period')->where('target_num=now_num and status_period=1')->count();
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $show= $Page->show();// 分页显示输出
		$res = M('period')->alias("a")->field('period_id,product_name,product_info,period_time,create_time')->join("left join hyz_product as b on a.p_id = b.product_id")->where('target_num=now_num and status_period=1')->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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
		$order_begin = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id))->order('order_id')->limit(5)->select();
		$order_end = M('order')->alias('o')->field('u.tel')->join('left join hyz_user as u on o.user_id=u.user_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id))->order('order_id desc')->limit(5)->select();
		foreach ($order_begin as $k => $v) {
			$begin .= substr($order_begin[$k]['tel'], -2);
		}
		foreach ($order_end as $k => $v) {
			$end .= substr($order_end[$k]['tel'], -2);
		}
		$like = ($begin+$end)%$info['target_num'];
		$winCode = 10000001+$like;//中奖码 10000025
		$winUser = M('order')->field('user_id')->where(array('period_time'=>$period_time,'order_product_id'=>$product_id,'lottery_code'=>array("like","%$winCode%")))->select();
		foreach ($winUser as $k => $v) {
			$user_id[]=$v['user_id'];
		}
		$user_id= implode(',', $user_id);//中奖的人
		//中奖信息加入数据库
		$indata = array(
				'win_code'=>$winCode,
				'win_type'=>1,
				'user_id'=>$user_id,
				'period_id'=>$period_id,
				'create_time'=>time()
			);
		$win = M('win')->add($indata);
		if ($win) {
			//修改期数的状态
			$res =M('period')->where(array('period_id'=>$period_id))->save(array('status_period'=>2));
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
		
	}
	//点赞开奖
	public function likeDraw()
	{
		
	}
}