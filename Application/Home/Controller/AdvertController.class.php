<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-04-14
 * Time: 16:42
 */

namespace Home\Controller;


use Base\Controller\BaseController;
use Base\Logic\PubLogic;
use Home\Logic\AdvertLogic;
use Think\Exception;

class AdvertController extends BaseController
{

    /**
     * 广告位列表页面
     */
    public function index()
    {
        if (IS_GET) {
            $title = I('title/s', '');

            $map = array();

            if (!empty($name)) $map['$title'] = array('like', "%{$title}%");

            list($data['list'], $data['fpage']) = PubLogic::getListDataByPage(M('Advert'),$map);

            $data['title'] = $name;
           // var_dump($data);exit;

            $this->assign($data)->display();
        }

    }


    /**
     * 添加页面
     */
    public function add()
    {
        if (!$_POST) {
            $product_list = $this->getProductList();
            $this->assign('list',$product_list);
        }
        if (IS_GET) {
            $data['ref_url'] = $_SERVER['HTTP_REFERER'];

            $this->display();
        }

        if (IS_POST) {
            if ($_FILES['file1']['size']) {
            $images = D('Support')->upload();
            $config_path = C('UPLOAD_PATH');
            foreach ($images as $k => $v) {
               $path_all[]='http://'.$_SERVER['HTTP_HOST'].$config_path.$v;
            }
            $path = implode(',', $path_all);
        }
        if (!$_POST['title'] || !$_FILES['file1']['name']) {
            $this->error('新增失败缺少参数');
        }

            try {
                $model = M();

                $model->startTrans();

                //$ret = AdvertLogic::addCompany(I('post.title'));
                $ret = M('advert')->add(array('title' => $_POST['title'],'remark' => $_POST['remark']));
                if ($ret) {
                    $data_list['aid'] = $ret;
                    $data_list['imgurl'] = $path;
                    $data_list['linkurl'] = $_POST['linkurl'];
                    $data_list['product_id'] = $_POST['product_id'];
                    $r = M('advert_list')->add($data_list);
                    if (!$r) {
                        $this->error('新增失败');
                    }
                }else{
                    $this->error('新增失败');
                }

                $model->commit();

                $this->success('添加成功', 'index');
            } catch (Exception $e) {
                $model->rollback();
                $this->error($e->getMessage());
                // $this->ajaxReturn(returnData(0, $e->getMessage()));
            }
        }
    }

    //获取商品列表
    public function getProductList()
    {
        $where = array('status_period'=>1);
        $res = M('period')->alias("a")->field('period_id,images,period_time,product_info,target_num,now_num,p_id')->join("left join hyz_product as b on a.p_id = b.product_id")->where($where)->order($order_by)->select();
        foreach ($res as $key => $value) {
            $res[$key]['surplus_num']=$res[$key]['target_num']-$res[$key]['now_num'];
            $res[$key]['images'] = explode(',', $value['images']);
        }
        return $res;
    }


    /**
     * 编辑页面
     */
    public function edit()
    {
        if (IS_GET) {
            //1. 获取当前广告位信息
            $data['info'] = M('Advert')->find(I('get.id', 0));


            if (empty($data['info'])) throw new Exception('数据异常');

            $data['ref_url'] = $_SERVER['HTTP_REFERER'];

            $this->assign($data)->display();
        }

        if (IS_POST) {

            try {
                AdvertLogic::editAdvert(I('post.'));

                $this->ajaxReturn(returnData(1, '修改广告位成功！'));
            } catch (\Exception $e) {
                $this->ajaxReturn(returnData(0, $e->getMessage()));
            }
        }
    }


    public function start()
    {
        try
        {
            $data['status'] = AdvertLogic::userUse(I('post.aid/d', 0));

            $this->ajaxReturn(returnData(1, '操作成功', $data));
        }

        catch (Exception $e)
        {
            $this->ajaxReturn(returnData(0, $e->getMessage()));
        }
    }

    public function del(){
       $id = $_GET['id'];
       if (!$id) {
           $this->error('删除失败');
       }
        $ret = M('advert')->where(array('id' => $id))->save(array('status' => 0));
        $ret = M('advert_list')->where(array('aid' => $id))->save(array('status' => 0));
        if ($ret) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}