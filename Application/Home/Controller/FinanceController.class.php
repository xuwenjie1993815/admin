<?php

namespace Home\Controller;

use Think\Exception;

class FinanceController extends \Base\Controller\BaseController
{
    public function financeList(){
        //期次财务统计
        // $join = 'hyz_order as o on o.period_time = p.period_time AND o.order_product_id = p.p_id';
        // $list = M('period')->alias('p')->join($join)->where(array('status_period' => 2))->select();
        // var_dump($list);die;
        //已完成的期次统计
        //获取用户信息
        $admin_id = $_SESSION['adminInfo']['id'];
        $role_id = $_SESSION['adminInfo']['role_id'];
        //如果是超级管理员
        if ($role_id != '0') {
            $where_r['shop_id'] = $admin_id;
        }
        $where_r['status_period'] = 2;
        $period_list = M('period')->where($where_r)->select();
        //项目总收入
        foreach ($period_list as $key => $value) {
            $order_money = 0;
            $where['order_product_id'] = $value['p_id'];
            $where['period_time'] = $value['period_time'];
            $where['order_status'] = array(array('neq',0),array('neq',3),'and'); 
            $order_money = $o_p = M('order')->where($where)->sum('order_money');
            $o_p_s += $o_p;
            $period_list[$key]['price'] = M('product')->where(array('product_id' => $value['p_id']))->getfield('price');
            switch ($value['status_period']) {
                case '0':
                    $period_list[$key]['status_period'] = '已删除';
                    break;
                case '1':
                    $period_list[$key]['status_period'] = '正常';
                    break;
                case '2':
                    $period_list[$key]['status_period'] = '已结束';
                    break;
            }
            $period_list[$key]['order_money'] = $order_money;
        }
        $this->assign('o_p_s',$o_p_s);
        $this->assign('list',$period_list);
        $this->display();
    }
}