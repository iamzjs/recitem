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
class IndexController extends BaseController {
	public function _initialize(){
		parent::_initialize();
		$this->model = D('corpinfo');
		
	}
	public function form(){
		if(!$this->isadmin){
				$this->error('你没有权限');
			}
		$this->display();
	}
    public function index(){
		if(!$this->isadmin){
				$this->error('你没有权限');
			}
		$data = I('post.');
	   $cname = ucwords($data['cname']);
	   $tbl = strtolower($cname);
	   $title = $data['title'];
	   $skey = $data['skey'];
	   $params = explode(',',$data['params']);
	   $paramstype = explode(',',$data['paramstype']);
	   Buildit::checkDir($cname,$tbl,$title,$params,$paramstype,$skey);
	   $moduleid = M('module')->where('name="'.MODULE_NAME.'"')->getField('id');
	   M('func')->add(array('name'=>$title.'维护','url'=>$cname.'/index','moduleid'=>$moduleid));
       $this->success($title.'模块创建成功',U($cname.'/index'));
    }
	public function modpass(){
		$this->display();
	}
	public function updatepass(){
		$data = I('post.');
		$model = M('user');
		$where = array('name'=>session('admin.name'),'password'=>md5($data['password']));
		if($model->where($where)->count()==0){
			$this->error('原始密码错误');
		}
		else{
			if($data['npassword']!=$data['cpassword']){
				$this->error('两次密码不一致');
			}
			$model->where('id=1')->setField('password',md5($data['npassword']));
			$this->success('密码更新成功！',U('mod'));
		}
	}
	public function mod(){
		if(!$this->isadmin){
				$this->error('你没有权限');
			}
		
		$corpinfo = $this->model->find();
		$this->assign('corpinfo',$corpinfo);
		$this->display();
	}
	public function update(){
		if(!$this->isadmin){
				$this->error('你没有权限');
			}
		$corpinfo = $this->model->create();
		$data['corpname'] = $_POST['corpname'];
		$data['contact'] = $_POST['contact'];
		//处理上传文件
		$config = array(
			'maxSize'    =>    3145728,
			'rootPath'   =>    './Uploads/',
			'savePath'   =>    '',
			'saveName'   =>    array('uniqid',''),
			//'saveName'   =>   date('Ymdhis').'_'.mt_rand(),
			//'saveName'   =>  'time',
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		if(!empty($_FILES['logo']['name'])){
			//$upload->saveName = date('Ymdhis').'_'.mt_rand();
			$info = $upload->uploadone($_FILES['logo']);
			if(!$info){
				$this->error($upload->getError());
			}
			else{
				$data['logo'] = $info['savepath'].$info['savename'];
			}
		}
		if(!empty($_FILES['banner']['name'])){
			//$upload->saveName = date('Ymdhis').'_'.mt_rand();
			$info2 = $upload->uploadone($_FILES['banner']);
			if(!$info2){
				$this->error($upload->getError());
			}
			else{
				$data['banner'] = $info2['savepath'].$info2['savename'];
			}
		}
		$result = $this->model->where('id=1')->save($data);
		if($result){
		$this->success('编辑成功!');
		}
		else{
			$this->error('编辑失败！');
		}
	}
	public function logout(){
		session('admin',null);
		cookie('username',null);
		cookie('password',null);
		$mod = substr(M('module')->where("id=".$this->moduleid)->getField('name'),5);
		if($mod){
			$this->success('注销成功，跳转到网站首页!',__ROOT__.'/'.$mod.'.php');
		}
		else{
			$this->success('注销成功，跳转到网站首页!',__ROOT__.'/index.php');
		}
	}
	
	
	public function slides(){
			$this->display();
	}
	public function updateslides(){
		$corpinfo = $this->model->create();
		$data['name'] = $_POST['name'];
		//$data['contact'] = $_POST['contact'];
		//处理上传文件
		$config = array(
			'maxSize'    =>    3145728,
			'rootPath'   =>    './Uploads/',
			'savePath'   =>    '',
			'saveName'   =>    array('uniqid',''),
			//'saveName'   =>   date('Ymdhis').'_'.mt_rand(),
			//'saveName'   =>  'time',
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		
		if(!empty($_FILES['thumb']['name'])){
			$upload->saveName = date('Ymdhis').'_'.mt_rand();
			$info2 = $upload->uploadone($_FILES['thumb']);
			if(!$info2){
				$this->error($upload->getError());
			}
			else{
				$data['thumb'] = $info2['savepath'].$info2['savename'];
			}
		
		$result = $this->model->add($data);
		if($result){
		$this->success('上传成功!');
		}
		else{
			$this->error('上传失败！');
		}
	}
	}
	
}