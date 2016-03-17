<?php
namespace Module\Controller;
class ListController extends HomeController {
   public function index(){
	    $tid = I('get.tid');
		$where = array('technoid'=>$tid,"thumb!=''");
		$this->assign('tbl',strtolower(MODULE_NAME));
		$my_pages = MyPage(M(strtolower(MODULE_NAME)),$where,$per=12);
		$this->assign('page',$my_pages['show']);// 赋值分页输出
		$this->assign('list',$my_pages['list']);
		$this->assign('onetech',M('techno')->find($tid));
		$this->display();
    }

}