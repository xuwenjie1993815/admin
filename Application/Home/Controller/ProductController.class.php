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
        //$where['status']=1;
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
            switch ($v['product_type']) {
                case '2':
                    $rst[$k]['product_type']='旅游摄影';
                    break;
                case '3':
                    $rst[$k]['product_type']='旅游项目';
                    break;
                case '4':
                    $rst[$k]['product_type']='健身器材';
                    break;
                case '5':
                    $rst[$k]['product_type']='生活用品';
                    break;
                default:
                    # code...
                    break;
            }
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
                'status'=>2,
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
                'status'=>2,
            );
        }else{
            $data = array(
                'product_name'=>$name,
                'price'=>$price,
                'change_time'=>time(),
                'product_type'=>$type,
                'product_info'=>$info,
                'status'=>2,
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
       $check = M('product')->field('status')->where(array('product_id'=>$product_id))->find();
       if ($check['status'] !=1) {
            $this->error('新增失败,商品还没有通过审核');
            die;
        }
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
        $attention= implode(',', $attention);
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
    //商品审批
    public function review()
    {
       $type = I('type')?I('type'):2;
       cookie('type',$type);
       $goods = M('product');
       $count = $goods->where(array('status'=>$type))->count();
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
        $rst = $goods->where(array('status'=>$type))->order('product_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($rst as $k => $v) {
            $one=explode(',', $v['images']);
            $rst[$k]['images'] = $one[0];
            switch ($v['product_type']) {
                case '2':
                    $rst[$k]['product_type']='旅游摄影';
                    break;
                case '3':
                    $rst[$k]['product_type']='旅游项目';
                    break;
                case '4':
                    $rst[$k]['product_type']='健身器材';
                    break;
                case '5':
                    $rst[$k]['product_type']='生活用品';
                    break;
                default:
                    # code...
                    break;
            }
        }
       $this->assign('list',$rst);
       $this->assign('type',$type);
       $this->assign('page',$show);
       $this->display();
    }
    //查看商品详情
    public function detail()
    {
        $product_id = I('product_id');
        $type = I('type');
        $product_id = addslashes($product_id);
        $res = M('product')->where(array('product_id'=>$product_id))->find();
        switch ($res['product_type']) {
            case '2':
                $res['product_type']='旅游摄影';
                break;
            case '3':
                $res['product_type']='旅游项目';
                break;
            case '4':
                $res['product_type']='健身器材';
                break;
            case '5':
                $res['product_type']='生活用品';
                break;
            default:
                # code...
                break;
        }
        $img = explode(',', $res['images']);
        $this->assign('img',$img);
        $this->assign('info',$res);
        $this->assign('type',$type);
        $this->display();
    }
    //执行审核方法
    public function approval()
    {
       $style = I('style');
       $type = I('type');
       $product_id = I('product_id');
       switch ($style) {
           case '1':
               if ($type==2) {
                  $status = 3;
               }elseif ($type==3) {
                   $status = 4;
               }elseif ($type==4) {
                   $status = 1;
               }
               break;
           case '2':
               $status = 5;
               break;

           default:
               # code...
               break;
       }
       $res = M('product')->where(array('product_id'=>$product_id))->save(array('status'=>$status));
       if ($res) {
            $this->success('操作成功', 'review');
        }else{
            $this->error('操作失败');
        }
    }
    //商品期次列表
    public function periodList()
    {
       $product_id = I('product_id');
       $product_id = addslashes($product_id);
       $res = M('period')->where(array('p_id'=>$product_id))->select();
       $this->assign('list',$res);
       $this->display();
    }
    //查看中奖信息
    public function viewWin()
    {
      $period_id = I('period_id');
      $period_id = addslashes($period_id);
      $res = M('reward')->alias('a')->field('a.order_id,a.ctime,reward_number,b.tel')->join('left join hyz_user as b on a.user_id=b.user_id')->where(array('period_id'=>$period_id))->select();
      $this->assign('list',$res);
      $this->display();
    }
}