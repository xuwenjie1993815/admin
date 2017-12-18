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


class ProductController extends \Think\Controller
{
	public function productList(){
		$goods_name = I('get.product_name');
        $status = I('get.status');
        $goods = M('product');
        if($goods_name){ //按商品名称查询
            $where['product_name'] = array("like","$goods_name%");
        }
        if($status !=-1 && $status !=''){ //按商品状态查询
            $where['status'] = $status;
        }
        //$rst = $goods->where($where)->order('product_id desc')->select(); //未分页
		

		$rst = $goods->where($where)->order('product_id desc')->select();
		
        $this->assign('gname',$goods_name);
        $this->assign('gstatus',$status);
        $this->assign('parent_title','商品管理');
        $this->assign('menu_title','商品列表');
        $this->assign('list',$rst);
        $this->display();
	}
}