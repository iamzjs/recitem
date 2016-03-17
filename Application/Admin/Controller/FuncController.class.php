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
	use Think\BaseadminController;
	class FuncController extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="func";
			if(!$this->isadmin){
				$this->error('你没有权限');
			}
		}
		public function index(){
			//parent::index($this->tbl);			
			$this->model=D($this->tbl);
			$where = array('moduleid'=>$this->moduleid);
			$this->getList($this->model,$this->tbl,$where);
			$this->display();
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
	}