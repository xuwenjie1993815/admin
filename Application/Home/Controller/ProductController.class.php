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
		$goods_name = I('post.product_name');
        //var_dump($goods_name);die;
        // $status = I('post.status');
        $goods = M('product');
        if($goods_name){ //按商品名称查询
            $where['product_name'] = array("like","%$goods_name%");
        }
        $role = session('adminInfo');
        if ($role['role_id'] !=0) {
            $where['shop_id']=$role['id'];
        }
        $count = $goods->where($where)->count();
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        // if($status !=-1 && $status !=''){ //按商品状态查询
        //     $where['status'] = $status;
        // }
        // foreach($where as $key=>$goods_name) {
        //       $Page->parameter[$key]=urlencode($goods_name);
        // }
        $show= $Page->show();// 分页显示输出
		$rst = $goods->where($where)->order('product_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($rst as $k => $v) {
            $one=explode(',', $v['images']);
            $rst[$k]['images'] = $one[0];
        }
        $this->assign('list',$rst);
        $this->assign('product_name',$goods_name);
        $this->assign('page',$show);
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
        //var_dump($_POST);die;
        if ($_FILES['file1']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            foreach ($images as $k => $v) {
               $path_all[]='http://'.$_SERVER['HTTP_HOST'].$config_path.$v;
            }
            $path = implode(',', $path_all);
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
                'shop_id'=>$admin_id['id'],
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
    //修改商品
    public function productEditor_run()
    {
        $name = I('name');
        $price = I('price');
        $info = I('info');
        $type = I('type');
        $product_id = I('product_id');
        if ($_FILES['file1']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            foreach ($images as $k => $v) {
               $path_all[]='http://'.$_SERVER['HTTP_HOST'].$config_path.$v;
            }
            $path = implode(',', $path_all);
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
        $attention = I('attention');
        $product_id = I('product_id');
        $product_id = addslashes($product_id);
        $res = M('period')->where(array('p_id'=>$product_id))->order('period_time desc')->find();
        if ($res['status_period'] ==1) {
            $this->error('新增失败,上期还没有完结');
            die;
        }
        $period_time = $res['period_time'];
        $period_time=$period_time+1;
        $admin_id= session('adminInfo');
        $data = array(
                'p_id'=>$product_id,
                'period_name'=>$name,
                'target_num'=>$info,
                'period_time'=>$period_time,
                'create_time'=>time(),
                'status_period'=>1,
                'period_price'=>$price,
                'shop_id'=>$admin_id['id'],
                'attention'=>$attention,
            );
        $rs = M('period')->add($data);
        if ($rs) {
            $this->success('新增成功', 'productList');
        }else{
            $this->error('新增失败');
        }
    }
}