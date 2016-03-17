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
	class ModuleController extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="module";
			if(!$this->isadmin){
				$this->error('你没有权限');
			}
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
			//parent::add($this->tbl);
			$name = I('post.name');
			if((!empty($name))&&(!M('module')->where(array('name'=>ucwords($name)))->count())){
				$cname = I('post.cname');
				$name = ucwords($name);
				$nickname = strtolower($name);
				$denymodule = array('home','admin','profile','adminprofile');
				if(!in_array($nickname,$denymodule)){
				$folder = APP_PATH.'/_module/';
				xCopy(APP_PATH.'_module/Adminmodule',APP_PATH.'Admin'.$nickname,1);
				xCopy(APP_PATH.'_module/Module',APP_PATH.$name,1);
				copy(APP_PATH.'_module/module.php',APP_PATH.$nickname.'.php');
				copy(APP_PATH.'_module/adminmodule.php',APP_PATH.'admin'.$nickname.'.php');
				namereplace(APP_PATH.'Admin'.$nickname.'/Controller/','Adminmodule','Admin'.$nickname);
				namereplace2(APP_PATH.'Admin'.$nickname.'/Model/','Adminmodule','Admin'.$nickname);
				namereplace(APP_PATH.$name.'/Controller/','Module',$name);
				bindreplace(APP_PATH.$nickname.'.php','Module',$name);
				bindreplace(APP_PATH.'admin'.$nickname.'.php','Adminmodule','Admin'.$nickname);
				sreplace(APP_PATH.'Admin'.$nickname.'/Controller/Buildit.class.php','namespace Adminmodule','namespace Admin'.$nickname);
				sreplace(APP_PATH.$nickname.'/View/Public/footer.html','adminmodule','admin'.$nickname);
				copyrkfile(__ROOT__.'/Application/admin'.$nickname.'.php');
				copyrkfile(__ROOT__.'/Application/'.$nickname.'.php');
				$this->model = D($this->tbl);
				$where_a = array('name'=>'Admin'.$nickname,'cname'=>$cname,'type'=>1);
				$where_f = array('name'=>$name,'cname'=>$cname,'type'=>2);
				if(!$this->model->where($where_a)->find())
				$this->model->add($where_a);
				if(!$this->model->where($where_f)->find())
				$this->model->add($where_f);
				
				$this->success('创建成功',U('index'));
				}
				else{
							$this->error('该名称被禁用！');
				}
			
			}
			else{
				$this->error('同名模块已存在');
			}
		}
		public function mod(){
			parent::mod($this->tbl);
			
		}
		public function update(){
			$sname = M($this->tbl)->where('id='.I('post.id'))->getField('name');
			$cname = I('post.cname');
			$name = I('post.name');
			if(stristr($sname,'Admin')){
				$sname = substr($sname,5);
			}
			$sname = ucwords($sname);
			$snickname = strtolower($sname);
			if(stristr($name,'Admin')){
				$name = substr($name,5);
			}
			$name = ucwords($name);
			$nickname = strtolower($name);
			$denymodule = array('home','admin','profile','adminprofile');
			if($sname!=$name){
				if(!in_array($sname,$denymodule)){
					if(!in_array($nickname,$denymodule)){
						rename(APP_PATH.'Admin'.$snickname,APP_PATH.'Admin'.$nickname);
						rename(APP_PATH.$sname,APP_PATH.$name);
						namereplace(APP_PATH.'Admin'.$nickname.'/Controller/','Admin'.$snickname,'Admin'.$nickname);
						namereplace2(APP_PATH.'Admin'.$nickname.'/Model/','Admin'.$snickname,'Admin'.$nickname);
						namereplace(APP_PATH.$name.'/Controller/',$sname,$name);
						
						sreplace(APP_PATH.'Admin'.$nickname.'/Controller/Buildit.class.php','namespace Admin'.$snickname,'namespace Admin'.$nickname);
						sreplace(APP_PATH.$nickname.'/View/Public/footer.html','admin'.$snickname,'admin'.$nickname);
						copy(APP_PATH.'_module/module.php',APP_PATH.$nickname.'.php');
						copy(APP_PATH.'_module/adminmodule.php',APP_PATH.'admin'.$nickname.'.php');
						
						bindreplace(APP_PATH.$nickname.'.php','Module',$name);
						bindreplace(APP_PATH.'admin'.$nickname.'.php','Adminmodule','Admin'.$nickname);
						del_file(__ROOT__.'/'.$snickname.'.php');
						del_file(__ROOT__.'/admin'.$snickname.'.php');
						copyrkfile(__ROOT__.'/Application/admin'.$nickname.'.php');
						copyrkfile(__ROOT__.'/Application/'.$nickname.'.php');						
					}		
					
					else{
						$this->error('该名称被禁用！');
					}
				}
				else{
					$this->error('该模块不允许修改！');
				}
				
			}

			$where_a = array('name'=>'Admin'.$snickname,'type'=>1);
			$where_f = array('name'=>$sname,'type'=>2);	
			$this->model=M('module');					
			$this->model->where($where_a)->save(array('name'=>'Admin'.$nickname,'cname'=>$cname));			
			$this->model->where($where_f)->save(array('name'=>$name,'cname'=>$cname));
			$this->success('修改成功',U('index'));
		
		}
		public function check(){
			parent::check($this->tbl);
		}
		public function del(){
			$sname = M($this->tbl)->where(array('id'=>I('get.id')))->getField('name');
			if(!in_array($sname,array('Home','Admin','profile','adminprofile'))){
			if(stristr($sname,'Admin')){
				$sname = ucwords(substr($sname,5));
			}
			$nickname = strtolower($sname);
			if((!empty($sname))&&(!empty($nickname))){
				set_time_limit(0);
				$dir = __ROOT__.'/Application/'.$sname;	
				del_dir($dir);
				$admindir =  __ROOT__.'/Application/'.'Admin'.$nickname;
				del_dir($admindir);
				del_file(__ROOT__.'/'.$nickname.'.php');
				del_file(__ROOT__.'/admin'.$nickname.'.php');
			}
			$sql = 'delete from module where name in("'.$sname.'","Admin'.$nickname.'")';
			M()->query($sql);
			$sqlfunc = 'delete from func where moduleid not in(select id from module)';
			M()->query($sqlfunc);
			$this->success('模块删除成功',U('index'));
			
			}
			else{
				$this->error('该模块不允许删除！');
			}
			
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