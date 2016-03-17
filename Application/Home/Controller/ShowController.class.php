<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class ShowController extends HomeController {
    public function index(){
		$product_id = I('get.id');
		$one = $this->product_model->find($product_id );
		$comment_model = M('comment');
		$where = array('resourseid'=>$product_id);
		$sql = "select c.*,u.name username from comment c left join user u on c.userid=u.id where c.vstate=1 and resourseid=".$product_id;
		$mypage = MyPageSql($sql);

		$this->assign('page',$mypage['show']);// 赋值分页输出
		$this->assign('list',$mypage['list']);
		$this->assign('one',$one);
		$this->display();
    }
	
	public function news(){
		$news_id = I('get.id');
		$one = $this->news_model->find($news_id );
		$this->assign('one',$one);
		$this->display();
	}
	
	public function contact(){
		$this->display();
	}
	
}