<?php

namespace Home\Controller;

use Think\Exception;

class NoticeController extends \Base\Controller\BaseController
{
	public function noticeList(){
		import('Org.Util.PubLogic');
		$Verify = new \PubLogic();
		// list($data['list'], $data['fpage']) = PubLogic::getListDataByPage(M('User'),$map);
		// list($data['list'], $data['fpage']) = PubLogic::getListDataByPage(M('notice'),array('notice_status' => 1),'add_time desc');
		// var_dump($data);die;
		// M('notice')->where(array('' => , ))
    	$this->display();
	}
}