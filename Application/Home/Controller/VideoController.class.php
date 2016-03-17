<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class VideoController extends HomeController {
    public function index(){
		$model=M();
		$id = I('get.id');
		if(!$id){
			$where='1=1';
		}
		else{
			$where = 'syearid='.I('get.id');
		}
	   
	   $sql1 = "select v.* from videos v where ".$where;
	    $sql2 = "select v.* from videos v where ".$where." order by hits desc limit 0,12";
	   $mypage = MyPageSql($sql1,12);
	   $this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);	   
	   $list_hit = $model->query($sql2);
	   $this->assign('list_hit',$list_hit);
	   $syear_list = M('syear')->order('name desc')->select();
	   $major_list = M('major')->order('id desc')->select();
	   $course_list = M('course')->order('id desc')->select();
	   $this->assign('syear_list',$syear_list);
	   $this->assign('major_list',$major_list);
	   $this->assign('course_list',$course_list);
	   $this->display();
    }
	public function course(){
	   $video_model = M('video');
	   $id = I('get.id');
	   $where = array('courseid'=>$id);
	   $list = $video_model->where($where)->order('insert_time')->select();
	   $one = $list[0];
	   $video_model->where('id='.$one['id'])->setField('hits',$one['hits']+1);
	   $this->assign('list',$list);
	   
	   $syear_list = M('syear')->order('name desc')->select();
	   $this->assign('syear_list',$syear_list);
		$this->display();
	}
	 public function search(){
		$model = M();
		$data = I('post.');
		$sql = "select v.* from videos v where 1=1";
		if($data['syear']){
			$sql .=" and syearid=".$data['syear'];
		}
		if($data['major']){
			$sql .=" and majorid=".$data['major'];
		}
		if($data['teacher']){
			$sql .=" and teacher like '%".$data['teacher']."%'";
		}
		if($data['course']){
			$sql .=" and course like '%".$data['course']."%'";
		}
		
       $mypage = MyPageSql($sql,12);
	   $this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
	   $list = $model->query($sql);
	   $video_model = M('video');
	   $sql2 = "select v.* from videos v order by hits desc limit 0,12";
	   $list_hit = $model->query($sql2);
	   $this->assign('list_hit',$list_hit);
	   $syear_list = M('syear')->order('name desc')->select();
	   $major_list = M('major')->order('id desc')->select();
	   $course_list = M('course')->order('id desc')->select();
	   $this->assign('syear_list',$syear_list);
	   $this->assign('major_list',$major_list);
	   $this->assign('course_list',$course_list);
	   $this->display();
    }
	public function hits(){
		$video_model = M('video');
		$id = I('post.id');
		$hits = I('post.hits');
		$video_model->where('id='.$id)->setField('hits',$hits+1);
	}
	
	
}