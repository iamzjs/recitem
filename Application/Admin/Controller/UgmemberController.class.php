<?php
// +----------------------------------------------------------------------
// | Amicool [ Dynamic Flexible Agile Devlepment]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2020 http://amicool.sinaapp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 郡笑笙 <iamzjs@126.com>
// +----------------------------------------------------------------------
	namespace Admin\Controller;
	use Think\Controller;
	class UgmemberController extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="user_usergroup";
			if(session("admin.id")!=1){
				$this->where=array("userid"=>session("admin.id"));
			}
			if(!$this->isadmin){
				$this->error('你没有权限');
			}
		}
		public function index(){
			$sql = 'select t1.*,t2.name username,t2.realname from user_usergroup t1 left join user t2 on t1.userid = t2.id where groupid='.I('get.groupid');
			$this->getListBySql($sql);
			$this->assign('groupid',I('get.groupid'));
			$this->display();
		}
		public function search($skey){
			parent::search($this->tbl,$skey,$this->where);
		}
		public function form(){
			//addsth
			parent::form($this->tbl);
		}
		
		public function add(){
			parent::add($this->tbl);
		}
		public function mod(){
			//addsth
			parent::mod($this->tbl);
		}
		public function update(){
			parent::update($this->tbl);
		}
		public function check(){
			parent::check($this->tbl);
		}
		public function del(){
			$this->model=D($this->tbl);
		$result = $this->model->delete(I('get.id'));
		if($result){
			$this->success('删除成功！',U('index',array('groupid'=>I('get.groupid'))));
		}
		else{
			$this->error('删除失败！');
		}		
		}
		public function bacts(){
			
		$action=I('post.action');
		switch($action){
			case 'dels':
			$this->model=D($this->tbl);		
			foreach(I('post.id') as $id){			
				$result = $this->model->delete($id);
			}
			$this->success('删除成功！',U('index',array('groupid'=>I('post.groupid'))));
			break;
			
			case 'listorder':
			$this->listorder($this->tbl);
			$this->success('排序成功！',U('index',array('groupid'=>I('post.groupid'))));
		}		
	}
		public function incform(){
			parent::incform($this->tbl);
		}
		public function inc($key){
			parent::inc($this->tbl,$key);
		}

}