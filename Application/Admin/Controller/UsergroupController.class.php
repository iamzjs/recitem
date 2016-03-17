<?php
	namespace Admin\Controller;
	use _Admin\Controller\BaseadminController;;
	class UsergroupController extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="usergroup";
		}
		public function index(){
			parent::index($this->tbl);
		}
		public function search($skey){
			parent::search($this->tbl,$skey);
		}
		public function form(){
			parent::form($this->tbl);
		}
		
		public function add(){
			parent::add($this->tbl);
		}
		public function mod(){
			parent::mod($this->tbl);
		}
		public function update(){
			parent::update($this->tbl);
		}
		public function check(){
			parent::check($this->tbl);
		}
		public function del(){
			parent::del($this->tbl);
		}
		public function bacts(){
			$sql="";
			parent::bacts($this->tbl,$sql);
		}
		public function incform(){
			parent::incform($this->tbl);
		}
		public function inc($key){
			parent::inc($this->tbl,$key);
		}
		public function view(){
			$sql = 'select t1.*,t2.name,t2.realname from user_usergroup t1 left join user t2 on t1.userid = t2.id where groupid='.I('get.id');
			$this->getListBySql($sql);
			$this->assign('groupid',I('get.id'));
			$this->display();
		}
	}