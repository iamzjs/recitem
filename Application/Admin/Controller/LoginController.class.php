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
class LoginController extends Controller {
	public function form(){
		$this->display();
	}
	
	public function login(){
		if(session('?admin')){
			$this->success('登录成功',U('index/modpass'));
		}
		else{
			$modal = M('user');
			$admin['username']=I('post.name');
			$admin['password']=I('post.password');		
			$where = array('name'=>$admin['username'],
						   'password'=>md5($admin['password'])
						   ); 
						   
			$result = $modal->where($where)->find();
			if($result){//POST验证成功
				//session_deal($admin['username']);
				session('admin',$result);
				//判断用户是否勾选自动登录
				if(!empty($_POST['remember'])){
					//如果用户勾选，则将值存到Cookie中
					cookie('username',$admin['username'],3600*12);
					cookie('password',$admin['password'],3600*12);
				}
				else{
					//如果用户未勾选，则清空Cookie
					cookie('username',null);
					cookie('password',null);
				}
				
				$this->success('登录成功',U('index/modpass'));
			}	
			else{//POST验证失败
				$this->redirect('login/form');
			}
		}
		
    }
}