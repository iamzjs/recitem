<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class IndexController extends HomeController {
	public function _initialize(){
		parent::_initialize();
	}
    public function index(){		
		$reslist=array();
		$where='1=1';
		foreach($this->modlist as $r){
			$res = array();
			$res['resname']=$r['cname'];
			$res['restbl']=strtolower($r['name']);
			$res['id']=strtolower($r['id']);
			$sql = 'select t1.* from '.strtolower($r['name']).' t1  where '.$where.' and thumb!=\'null\' ';
			$mypage = MyPageSql($sql,'id desc',16);
			$res['list']=$mypage['list'];
			$res['page']=$mypage['show'];
			$reslist[]=$res;
		}
		$this->assign('reslist',$reslist);
		$this->display();
	
    }
	
	public function add(){
		$user_model = D('Admin/user');	
		$data = I('post.');
		$data['hobby']=implode(',',$data['hobby']);
		if(!$user_model->create($data)){
			$this->error($user_model->getError());
		}
		
		else{		
			$result = $user_model->add();
			if($result){
				$this->success('添加成功',U('index'));
			}
			else{
				$this->error('添加失败');
			}
		}
	}
	
}