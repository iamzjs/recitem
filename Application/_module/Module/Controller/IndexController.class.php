<?php
namespace Module\Controller;
class IndexController extends HomeController {
	public function _initialize(){
		parent::_initialize();
	}
  public function index(){		
		$reslist=array();			
	    $where='1=1';
	    $sql = 'select t1.* from '.strtolower(MODULE_NAME).' t1 where '.$where.' and thumb!=\'null\' ';
		$mypage = MyPageSql($sql,'id desc',16);
	   $this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
		$this->assign('modcname',M('module')->where(array('name'=>MODULE_NAME))->getField('cname'));
		$this->display();
    }
}