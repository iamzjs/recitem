<?php
namespace Module\Controller;
class ShowController extends HomeController {
     public function index(){
		$id = I('get.id');
		$one = M(strtolower(MODULE_NAME))->find($id);
		$this->assign('one',$one);
		$this->display();
    }
}