<?php
namespace Admin\Model;
use Think\Model;
class ProductModel extends Model {
	protected $_validate = array(
		array('name','','产品标题已经存在！',0,'unique',3), // 在新增（1代表新增2编辑3全部）的时候验证name字段是否唯一
	);
	protected $_auto = array ( 
         //array('author','admin',3),  // 新增的时候把status字段设置为1
         //array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         //array('insert_time','get_client_time',3,'callback'), // 对update_time字段在更新的时候写入当前时间戳
		 array('productdesc', 'jsescape', 3, 'callback'),
		 array('productdesc', 'htmlspecialchars_decode', 3, 'function'),		 
     );
	 
	 public function get_client_time(){
        return date("Y-m-d");
    }
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['productdesc'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}