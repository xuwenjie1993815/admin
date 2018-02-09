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
        $period_list = M('period')->where(array('status_period' => 2))->select();
        //项目总收入
        foreach ($period_list as $key => $value) {
            $order_money = 0;
            $where['order_product_id'] = $value['p_id'];
            $where['period_time'] = $value['period_time'];
            $where['order_status'] = array(array('neq',0),array('neq',3),'and'); 
            $order_money = M('order')->where($where)->sum('order_money');
            // var_dump($value['period_time'],$value['p_id'],$order_money);
            $period_list[$key]['order_money'] = $order_money;
        }

        // var_dump($period_list);die;
        $this->assign('list',$period_list);
        $this->display();
    }
}