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
    public function goodsAdd()
    {
        $this->display();
    }
    public function goodsAdd_run()
    {
        $name = I('name');
        $price = I('price');
        $info = I('info');
        $type = I('type');
        if (!$name) {
           $this->error('新增失败缺少参数');
        }
        if ($_FILES['file']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            $path = 'http://'.$_SERVER['HTTP_HOST'].$config_path.$images[0];
        }
        $admin_id= session('adminInfo');
        $data = array(
                'product_name'=>$name,
                'price'=>$price,
                'images'=>$path,
                'status'=>1,
                'ctime'=>time(),
                'product_type'=>$type,
                'product_info'=>$info,
                'admin_id'=>$admin_id['id'],
            );
        $res = M('product')->add($data);
        if ($res) {
            $this->success('新增成功', 'productList');
           //$this->redirect('product/productList');
        }else{
            $this->error('新增失败');
        }
    }
     //删除产品
    public function delProduct()
    {
        $product_id = I('product_id');
        $product_id = addslashes($product_id);
        $row = M('product')->where(array('product_id'=>$product_id))->delete();
        if ($row) {
            $data = array(
                'code'=>0,
                'msg'=>'删除成功',
                );
           $this->ajaxReturn($data);
        }else{
            $data = array(
                'code'=>1,
                'msg'=>'删除失败',
                );
           $this->ajaxReturn($data);
        }
    }
    //商品修改展示页面
    public function productEditor()
    {
        $product_id = I('product_id');
        $product_id = addslashes($product_id);
        $res = M('product')->where(array('product_id'=>$product_id))->find();
        $this->assign('info',$res);
        $this->display();
    }
    //修改图片
    public function productEditor_run()
    {
        $name = I('name');
        $price = I('price');
        $info = I('info');
        $type = I('type');
        $product_id = I('product_id');
        if ($_FILES['file']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            $path = 'http://'.$_SERVER['HTTP_HOST'].$config_path.$images[0];
        }
        if ($path) {
            $data = array(
                'product_name'=>$name,
                'price'=>$price,
                'images'=>$path,
                'change_time'=>time(),
                'product_type'=>$type,
                'product_info'=>$info,
            );
        }else{
            $data = array(
                'product_name'=>$name,
                'price'=>$price,
                'change_time'=>time(),
                'product_type'=>$type,
                'product_info'=>$info,
                );
        }
        $res = M('product')->where(array('product_id'=>$product_id))->save($data);
        if ($res) {
            $this->success('修改成功', 'productList');
        }else{
            $this->error('修改失败');
        }
    }
    //添加期次商品
    public function productPeriod()
    {
       $product_id = I('product_id');
       $this->assign('product_id',$product_id);
       $this->display();
    }
    public function productPeriod_run()
    {
        $name = I('name');
        $price = I('price');
        $info = I('info');
        $product_id = I('product_id');
        $product_id = addslashes($product_id);
        $res = M('period')->where(array('p_id'=>$product_id))->order('period_time desc')->find();
        $period_time = $res['period_time'];
        $period_time=$period_time+1;
        $data = array(
                'p_id'=>$product_id,
                'period_name'=>$name,
                'target_num'=>$info,
                'period_time'=>$period_time,
                'create_time'=>time(),
                'status_period'=>1,
                'period_price'=>$price,
            );
        $rs = M('period')->add($data);
        if ($rs) {
            $this->success('新增成功', 'productList');
        }else{
            $this->error('新增失败');
        }
    }
}