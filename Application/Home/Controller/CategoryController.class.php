<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class CategoryController extends HomeController {
    public function index(){
	   $catid = I('get.catid');
	   if(empty($catid)){
		   $where=array('parentid=0');
	   }
	   else{
		   $where=array('parentid='.$catid);
		    $cat = $this->cat_model->find($catid);
			if(empty($cat[childids])){
				$this->redirect('home/list/index', array('catid' => $catid), 0, '页面跳转中...');
			}
	   }
	  
	   
	   //$childids = explode(',',$cat[childids]);
	   $cat_list = $this->cat_model->where($where)->order('id desc')->select();
		for($i=0;$i<count($cat_list);$i++){
			$cat = $cat_list[$i];
			//dump($cat);
			if($cat['childids']!=''){$where_p=array("categoryid in(".$cat['childids'].")","productthumb !=''");}
			else{$where_p=array("categoryid = ".$cat['id'],"productthumb !=''");}
			$pp = $this->product_model->where($where_p)->limit(6)->order('id desc')->select();
			$product['cat']= $cat;
			$product['products']= $pp;
			$product_list[]=$product;
		}
		$this->assign('product_list',$product_list);
		//dump($product_list);
	    $this->display();
    }
}