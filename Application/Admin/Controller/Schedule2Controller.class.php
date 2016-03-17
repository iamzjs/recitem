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
	class Schedule2Controller extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="schedule2";
			if(!$this->isadmin){
				$this->where=array("userid"=>session("admin.id"));
			}
		}
		public function index(){
			//addcode
			//推荐位
			$this->assign("rec_list",M("recpos")->where("moduleid=".$this->moduleid)->select());
			parent::index($this->tbl,$this->where);
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
			parent::del($this->tbl);
		}
		public function bacts(){
			$sql="";
			parent::bacts($this->tbl,$sql);
		}
		public function incform(){
			parent::incform($this->tbl);
		}
		public function view(){
			parent::view($this->tbl);
		}
		public function inc($key){
			parent::inc($this->tbl,$key);
		}
		public function getCourse(){
			$id = I("get.technoid");
			$sql = "select c.* from course c left join techno t1 on c.technoid=t1.id left join techno t2 on t1.parentid = t2.id where c.technoid='".$id."' or t1.parentid='".$id."'";
			getAssocBySql($sql,array("kcdm","kcmc"));
		}
		public function getSection(){
			$id = I("get.kcdm");
			$sql = "select * from section where kcdm='".$id."'";
			getAssocBySql($sql,array("id","name"));
		}
	}