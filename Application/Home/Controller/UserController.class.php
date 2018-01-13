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

class UserController extends BaseController
{
    //用户列表
    public function index()
    {
        //if (IS_GET) {
            $tel = I('tel/s', '');
            //var_dump($tel);die;
            $map = array();

            if (!empty($tel)) $map['tel'] = array('like', "%{$tel}%");

            list($data['list'], $data['fpage']) = PubLogic::getListDataByPage(M('User'),$map);

            $data['tel'] = $tel;
//            var_dump($data);exit;

            $this->assign($data)->display();
        //}
    }

}