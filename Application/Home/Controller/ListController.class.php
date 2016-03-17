<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class ListController extends HomeController {
    public function index(){
	   $catid = I('get.catid');
	   $cat = $this->cat_model->find($catid);
	   $this->assign('cat',$cat);
		$where = array('categoryid'=>$catid,"productthumb !=''");
		$my_pages = MyPage($this->product_model,$where,$per=6);
		$this->assign('page',$my_pages['show']);// 赋值分页输出
		$this->assign('list',$my_pages['list']);
		$this->display();
    }
	public function news(){				
		//$model = M('news');		
		$type = I('get.type');
		/*
		$count      = $this->news_model->where(array('type'=>$type))->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $$this->news_model->where(array('type'=>$type))->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('list',$list);
		*/
		$where = array('type'=>$type);
		$my_pages = MyPage($this->news_model,$where,$per=6);
		$this->assign('page',$my_pages['show']);// 赋值分页输出
		$this->assign('list',$my_pages['list']);
		$this->assign('type',$type);
		$this->display();
	}
	
}