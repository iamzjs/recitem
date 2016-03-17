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
	class VideoController extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="video";
			if(session("admin.id")!=1){
				$this->where=array("userid"=>session("admin.id"));
			}
		}
		public function index(){
			M("")->query("update video t left join techno t1 on t.technoid=t1.id set t.technoname=t1.name");M("")->query("update video t left join course t1 on t.kcdm=t1.kcdm set t.kcmc=t1.kcmc");M("")->query("update video t left join section t1 on t.sectionid=t1.id set t.sectionname=t1.name");M("")->query("update video t left join special t1 on t.specialid=t1.id set t.specialname=t1.name");
			//推荐位
			$this->assign("rec_list",M("recpos")->where("moduleid=".$this->moduleid)->select());
			parent::index($this->tbl,$this->where);
		}
		public function search($skey){
			parent::search($this->tbl,$skey,$this->where);
		}
		public function form(){
			$this->assign("sectionlist",M("section")->select());
			$this->assign("speciallist",M("special")->select());
			parent::form($this->tbl);
		}
		
		public function add(){
			parent::add($this->tbl);
		}
		public function mod(){
			$this->assign("sectionlist",M("section")->select());
			$this->assign("speciallist",M("special")->select());
			parent::mod($this->tbl);
		}
		public function update(){
			parent::update($this->tbl);
		}
		public function check(){
			parent::check($this->tbl);
		}
		public function del(){
			$one = M($this->tbl)->find(I('get.id'));
			if($one['thumb']&&is_file('./Uploads/'.$one['thumb'])) unlink('./Uploads/'.$one['thumb']); 
			if($one['srcsc']&&is_file('./Uploads/'.$one['srcsc'])) unlink('./Uploads/'.$one['srcsc']); 
			if($one['donesc']&&is_file('./Uploads/'.$one['donesc'])) unlink('./Uploads/'.$one['donesc']); 
			if($one['videopath']&&is_file('./Uploads/video/video/'.$one['videopath'])) unlink('./Uploads/video/video/'.$one['videopath']); 
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