<?php
namespace Profile\Controller;
class IndexController extends ProfileController {
    public function index(){
		$uid = session('user.id');
		$where = array('userid'=>$uid,'vstate'=>1);
		$mypage = MyPage($this->comment_model,$where,8);
		$this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('comment_list',$mypage['list']);
	   //$one = $this->user_model->find($uid);
	   $one = session('user');
	   $hobbies = array('音乐','影视','游戏','运动健身');
	   for($i=0;$i<count($hobbies);$i++){
		   $hobby_checked[]=checkstr($hobbies[$i],$one['hobby']);
	   }
	   $this->assign('one',$one);
	   $this->assign('hobby_checked',$hobby_checked);
       $this->display();
    }
	public function login(){
		//dump(I('post.'));
		//$model = M('user');
		$user['name']=I('post.name');
		$user['password']=I('post.password');		
		$where = array('name'=>$user['name'],
					   'password'=>md5($user['password'])
					   );	   
		$result = $this->user_model->where($where)->find();
		if($result){//POST验证成功
			session('user',$result);
			//dump($result);
			$result['success']=1;
			$this->ajaxReturn($result,"json");
		}
		else{
			$this->ajaxReturn(array('success'=>0),"json");
		}
    }
	public function logout(){
		session('user',null);
		//$this->redirect(__ROOT__);
		$this->success('注销成功，跳转到网站首页!',__ROOT__.'/index.php');
	}
	public function add(){
		$data = I('post.');
		$data['hobby']=implode(',',$data['hobby']);
		$data['password'] = md5($data['password']);
		if(!$this->user_model->create($data)){
			$this->error($this->user_model->getError());
		}
		
		else{		
			$result = $this->user_model->add();
			if($result){
				$this->success('注册成功',U('index'));
			}
			else{
				$this->error('注册失败');
			}
		}
	}
	
	public function update(){
		$data = I('post.');
		$data['hobby']=implode(',',$data['hobby']);
		$data['password'] = md5($data['password']);
		if(!$this->user_model->create($data)){
			$this->error($this->user_model->getError());
		}
		
		else{		
			$result = $this->user_model->save();
			if($result){
				session('user',$data);
				$this->success('编辑成功',U('index'));
			}
			else{
				$this->error('编辑失败');
			}
		}
	}
	public function comment(){
		$data = I('post.');
		if(!$this->comment_model->create($data)){
			$this->error($this->comment_model->getError());
		}
		
		else{
			$result = $this->comment_model->add();			
			if($result){//添加成功
				$data['success']=1;
				$this->ajaxReturn($data,"json");
			}
			else{
				$this->ajaxReturn(array('success'=>'0'),"json");
			}
		}
	}
   
	
}