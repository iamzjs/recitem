<?php
namespace Module\Controller;
class CategoryController extends HomeController {
     public function index(){
	    $tid = I('get.tid');
		$ctlist = M('techno')->where(array('parentid'=>$tid))->select();
		if(empty($ctlist)){
			$this->redirect('list/index', array('tid' => $tid), 0, '页面跳转中...');
		}
		$reslist=array();
		foreach($ctlist as $r){
			$res = array();
			$res['ctid']=$r['id'];
			$res['resname']=$r['name'];
			$res['restbl'] = strtolower(MODULE_NAME);
			$res['reslist']=$this->model->query('select t1.* from '.$res['restbl'].' t1 where thumb!=\'null\' and t1.technoid='.$r['id'].' order by listorder desc,id desc limit 0,8');
			$reslist[]=$res;
		}
		$this->assign('reslist',$reslist);
	    $this->display();
    }
}