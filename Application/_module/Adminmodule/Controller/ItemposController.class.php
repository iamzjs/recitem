<?php
namespace Adminmodule\Controller;
use Think\BaseadminController;
class ItemposController extends BaseController {
	
	public function _initialize(){
			parent::_initialize();
			$this->tbl="itempos";
	}
	public function index(){
		$sql = 'select t1.*,t2.name itemname from itempos t1 left join '.substr(MODULE_NAME,5).' t2 on t1.itemid = t2.id where posid='.I('get.id');
		$this->getListBySql($sql);
		$this->assign('posid',$_GET['id']);
		$this->display();
	}
	public function search(){
		
		$sql = "select p1.*,p2.name itemname from itempos p1 left join ".substr(MODULE_NAME,5)." p2 on p1.itemid = p2.id where posid=".I('get.posid')." and p2.name like '%".I('post.search_key')."%'";
		$this->getListBySql($sql);
		$this->assign('posid',I('get.posid'));
		$this->display();
		
	}
	
	public function delprod(){
		$this->model=D($this->tbl);
		$id = I('get.id');
		$one = $this->model->find($id);
		if(!empty($one['userid'])){
			$userid = $this->model->where(array('id'=>$id))->getField('userid');
			if($userid!=session('admin.id')){
				$this->error('你没有权限！');
			}
		}
		$result = $this->model->delete(I('get.id'));
		if($result){
			$this->success('删除成功！',U('index',array('id'=>I('get.posid'))));
		}
		else{
			$this->error('删除失败！');
		}		
	}
	
	public function order(){
		$this->model=D('itempos');
		$id = $_GET['id'];
		//$one = $this->model->find($id);
		$listorder =  $_GET['listorder']+1;
		$result = $this->model->save(array('id'=>$id,'listorder'=>$listorder));	
		$this->redirect('index',array('id'=>$_GET['posid']));	

     }
	public function bacts(){
		$action=I('post.action');
		switch($action){
			case 'dels':
			$this->dels($this->tbl);
			$this->success('删除成功！',U('index',array('id'=>I('get.posid'))));
			break;
			
			case 'listorder':
			$this->listorder($this->tbl);
			$this->success('排序成功！',U('index',array('id'=>I('get.posid'))));
		}		
	}
 }