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
		$res = M('period')->alias("a")->field('period_id,product_name,product_info,period_time,create_time')->join("left join hyz_product as b on a.p_id = b.product_id")->where('target_num=now_num and status_period=1')->select();
		$this->assign('list',$res);
        //$this->assign('page',$show);
		$this->display();
	}
}