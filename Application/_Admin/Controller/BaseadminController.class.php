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
namespace _Admin\Controller;
use Think\Controller;
class BaseadminController extends Controller {
	protected $model;
	protected $where;
	protected $per;
	protected $tbl;
	protected $moduleid;
	protected $isadmin;
	public function _initialize(){
		$this->checkAdmin();
		$this->per = 20;
		$this->where='1=1';
		$this->isadmin = M('user_usergroup')->where(array('groupid'=>2,'userid'=>session('admin.id')))->count();
		$this->assign('isadmin',$this->isadmin);
	}
    private function checkAdmin(){
	   if(!session('?admin')){
		   //$this->error('无权限',U('home/index/index'));
		   $this->redirect('login/form');
	   }
    }
	public function index($tbl,$where='1=1'){
		$this->model=D($tbl);
		$this->getList($this->model,$tbl,$where);
		$this->display();
	}
	public function search($tbl,$skey='name',$where='1=1'){
		$this->model=D($tbl);
		if(!stristr($skey,',')){
		$map[$skey]=array('like','%'.I('post.search_key').'%');
		}
		else{
			$skey = explode(',',$skey);
			if(count($skey)==1){
				$map[$skey[0]] = array('like','%'.I('post.search_key').'%');
			}
			else{
				$str = '';
				foreach($skey as $k=>$s){
					if($k==0){
						$str='('.$s.' like "%'.I('post.search_key').'%")';
					}
					else{
						$str .= ' or ('.$s.' like "%'.I('post.search_key').'%")';
					}				
				}
			}
			$map['_string']=$str;
		}
		
		$map[]=$where;
		$this->getList($this->model,$tbl,$map);
		$this->display();
		
	}
	public function form(){
		$this->display();
	}
	public function add($tbl,$arr=array()){
		//$filename =$this->mupfile('file','./Uploads/csv/');
		$this->model=D($tbl);
		$rinfo = $this->mupfiles('./Uploads/'.strtolower(CONTROLLER_NAME).'/');
		foreach($rinfo as $k=>$v){
			$_POST[$k]=strtolower(CONTROLLER_NAME).'/'.$k.'/'.$v['filename'];
			$_POST[$k.'size']=$v['filesize'];
		}
		if(!$this->model->create()){
			$this->error($this->model->getError());
		}
		else{		
			$result = $this->model->add();
			if($result){
				$this->success('添加成功',U('index',$arr));
			}
			else{
				$this->error('添加失败');
			}
		}
	}
	public function mod($tbl){
		$this->model=D($tbl);
		$data = $this->model->find(I('get.id'));
		$this->assign('one',$data);
		$this->display();
		
	}
	
	public function update($tbl,$arr=array()){
		$this->model=D($tbl);
		$id = I('post.id');
		$one = $this->model->find($id );
		if(!empty($one['userid'])){
			$userid = $one['userid'];
			if($userid!=session('admin.id')){
			$this->error('你没有权限！');
			}
		}
		
		//file是文件域的name
		$rinfo = $this->mupfiles('./Uploads/'.strtolower(CONTROLLER_NAME).'/',$tbl,$id);
		foreach($rinfo as $k=>$v){
			$_POST[$k]=strtolower(CONTROLLER_NAME).'/'.$k.'/'.$v['filename'];
			$_POST[$k.'size']=$v['filesize'];
		}
		if(!$this->model->create()){
			$this->error($this->model->getError());
		}
		else{
			$result = $this->model->save();
			if($result){
				$this->success('编辑成功！',U('index',$arr));
			}
			else{
				$this->error('编辑失败！');
			}
		}
	
		
	}
	public function check($tbl,$arr=array()){
		$this->model=D($tbl);
		$id = I('get.id');
		$check = I('get.check');
		$this->model->where('id='.$id)->setField('vstate',$check==0?1:0);
		$this->success('操作成功！',U('index',$arr));
	}
	public function view($tbl){
		$this->model=D($tbl);
		$data = $this->model->find(I('get.id'));
		$this->assign('one',$data);
		$this->display();
	}
	public function del($tbl,$arr=array()){
		$this->model=D($tbl);
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
			$this->success('删除成功！',U('index',$arr));
		}
		else{
			$this->error('删除失败！');
		}
		
	}
	public function bacts($tbl,$arr=array(),$sql=""){
		$action=I('post.action');
		switch($action){
			case 'dels':
			$this->dels($tbl);
			$this->success('删除成功！',U('index',$arr));
			break;
			case 'checks':
			$this->checks($tbl);
			$this->success('批量审核成功！',U('index',$arr));
			break;
			case 'listorder':
			$this->listorder($tbl);
			$this->success('排序成功！',U('index',$arr));
			break;
			case 'rec_pos':
			$posid = I("post.posid");
			$rec_model = D('itempos');
			$ids = I('post.id');
			for($i=0;$i<count($ids);$i++){
				$data = array('posid'=>$posid,'itemid'=>$ids[$i]);
				if($rec_model->where($data)->count()>0){
					$rec_model->where($data)->delete();				
				}
				$result = $rec_model->add($data);			
			}
			$this->success('推荐成功！',U('index',$arr));
			break;
			default:
			$model=M();
			/*
			foreach(I('post.id') as $id){
				$vsql = $sql.$id;
				$result = $model->query($id);
			}
			$this->success('批量操作成功！',U('index'));
			*/
			$this->error('不知道你要干什么！');
		}		
	}
	public function dels($tbl){
		$this->model=D($tbl);
		
		foreach(I('post.id') as $id){
			$one = $this->model->find($id);
			if(!empty($one['userid'])){
				$userid = $this->model->where(array('id'=>$id))->getField('userid');
				if($userid!=session('admin.id')){
					$this->error('你没有权限！');
				}
			}
			$result = $this->model->delete($id);
		}
	}
	public function checks($tbl){
		$this->model=D($tbl);
		foreach(I('post.id') as $id){
			$vstate = $this->model->where('id='.$id)->getField('vstate');	
			$result = $this->model->where('id='.$id)->setField('vstate',$vstate==0?1:0);;
		}
	}
	public function listorder($tbl){
		$this->model=D($tbl);
		$listorder = I('post.listorder');
		$i=0;
		foreach(I('post.id') as $id){
			$result = $this->model->where('id='.$id)->setField('listorder',$listorder[$i]);
			$i++;			
		}		
	}
	protected function getList($model,$tbl,$where="1=1"){
		//$map['name'] = array('like','%'.I('post.search_key').'%');
		$mypage = MyPage($model,$where,$this->per);
		$this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
		$this->assign('tbl',$tbl);
	}
	protected function getListBySql($sql,$order='listorder desc,id desc'){
		//$map['name'] = array('like','%'.I('post.search_key').'%');
		$mypage = MyPageSql($sql,$order,$this->per);
		$this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
	}
	
	protected function mupfiles($rootPath='./Uploads/',$tbl='video',$id='0'){
		//$data['contact'] = $_POST['contact'];
		$rinfo=array();
		//处理上传文件
		$config = array(
			'maxSize'    =>    31457280,//30M
			'rootPath'   =>    $rootPath,
			'savePath'   =>    '',
			'saveName'   =>    array('uniqid',''),
			//'saveName'   =>   date('Ymdhis').'_'.mt_rand(),
			//'saveName'   =>  'time',
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','xls','pdf','swf'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		foreach($_FILES as $key=>$f){
			if(!empty($f['name'])){
				$upload->saveName = date('Ymdhis').'_'.mt_rand();
				$upload->rootPath = $rootPath.$key.'/';
				$info2 = $upload->uploadone($f);
				if(!$info2){
					$this->error($upload->getError());
				}
				else{
					$filesize = intval(intval($f['size'])/1024);
					if($filesize<1024){
						$filesize.='K';
					}
					else{
						$filesize = round($filesize/1024,2).'M';
					}
						$rinfo[$key]=array('filename'=>$info2['savepath'].$info2['savename'],'filesize'=>$filesize);
						if($id!=0){
							$delsrc = M($tbl)->where('id='.$id)->getField($key);
							if($delsrc){
								if(is_file('./Uploads/'.$delsrc)) unlink('./Uploads/'.$delsrc);
							}
						}
						
					}
			}
			
		}
		return $rinfo;
		
	}
	
	protected function mupfile($filename='file',$rootPath='./Uploads/'){
		//$data['contact'] = $_POST['contact'];
		//处理上传文件
		$config = array(
			'maxSize'    =>    3145728,
			'rootPath'   =>    $rootPath,
			'savePath'   =>    '',
			'saveName'   =>    array('uniqid',''),
			//'saveName'   =>   date('Ymdhis').'_'.mt_rand(),
			//'saveName'   =>  'time',
			'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','xls'),
			'autoSub'    =>    true,
			'subName'    =>    array('date','Ymd'),
		);
		$upload = new \Think\Upload($config);// 实例化上传类
		if(!empty($_FILES[$filename]['name'])){
			$upload->saveName = date('Ymdhis').'_'.mt_rand();
			$info2 = $upload->uploadone($_FILES[$filename]);
			if(!$info2){
				$this->error($upload->getError());
			}
			else{
				return $info2['savepath'].$info2['savename'];
			}
		}
	}
	public function incform(){
		$this->display();
	}
	public function inc($tbl,$key,$arr=array()){
		$filename =$this->mupfile('file','./Uploads/csv/');
		 set_time_limit(0);
		$list = importExcel('./Uploads/csv/'.$filename);

		$this->model=D($tbl);
		foreach($list as $one){
			if(!$this->model->where(array($key=>$one[$key]))->find()){
				$this->model->add($one);
			}
		}
		$this->success('导入完毕！',U('index',$arr));
	}
	
	
}