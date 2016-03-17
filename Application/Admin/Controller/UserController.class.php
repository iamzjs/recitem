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
class UserController extends BaseController {
	public function _initialize(){
		parent::_initialize();
		$this->model=D('user');
		$this->tbl='user';
		if(!$this->isadmin){
				$this->error('你没有权限');
			}
	}
	public function index(){
		//$list = $user->select();
		//$this->assign('list',$list);
		//分页
		/*$count      = $model->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('list',$list);// 赋值数据集
		*/
		$where='1=1';
		$mypage = MyPage($this->model,$where,20);
		$this->assign('uglist',M('usergroup')->select());
		$this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
		$this->display();
	}
	public function form(){
		$this->display();
	}
	public function add(){
		if(!$this->model->create()){
			$this->error($this->model->getError());
		}
		else{		
			$result = $this->model->add();
			if($result){
				$this->success('添加成功',U('index'));
			}
			else{
				$this->error('添加失败');
			}
		}
	}
	public function mod(){
		$id = $_GET['id'];
		$data = $this->model->where('id='.$id)->find();
		//dump($data);
		$this->assign('oneuser',$data);
		$this->display();
		
	}
	public function update(){
		if(!$this->model->create()){
			$this->error($this->model->getError());
		}
		else{
			//dump($_POST);
			//dump($this->model->data());
			$result = $this->model->save();
			if($result){
				$this->success('用户编辑成功！',U('index'));
			}
			else{
				$this->error('用户编辑失败！');
			}
		}
	
		
	}
	
	public function del(){
		$id = $_GET['id'];
		//$this->model->delete($id);
		$result = $this->model->where('id='.$id)->delete();
		if($result){
			$this->success('用户删除成功！',U('index'));
		}
		else{
			$this->error('用户删除失败！');
		}
		
	}
	public function search(){
		$map['name'] = array('like','%'.I('post.search_key').'%');
		$list = $this->model->where($map)->select();
		$this->assign('list',$list);
		$this->display();
		
	}
	public function bacts(){
		$action=I('post.action');
		$ids = I('post.id');
		if($action=='ug'){
			$groupid = I("post.groupid");
			$model = D('user_usergroup');
			for($i=0;$i<count($ids);$i++){
				$data = array('groupid'=>$groupid,'userid'=>$ids[$i]);
				if($model->where($data)->count()==0){
					$result = $model->add($data);			
				}							
			}		
			$this->success('加入用户组成功！',U('user/index'));
		}
		else{
			parent::bacts($this->tbl);
		}
	}
}