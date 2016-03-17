<?php
namespace Adminmodule\Controller;
use Think\BaseadminController;
class RecposController extends BaseController {
	 public function _initialize(){
			parent::_initialize();
			$this->tbl="recpos";
			$this->where=array("moduleid"=>$this->moduleid);
		}
	public function index(){
		parent::index($this->tbl,$this->where);
	}

	public function form(){
		$this->assign('moduleid',$this->moduleid);
		parent::form($this->tbl);
	}
	public function add(){
			parent::add($this->tbl);
	}
	public function mod(){
		$this->assign('moduleid',$this->moduleid);
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
	
	public function search(){
			parent::search($this->tbl,'name',$this->where);
	}
}