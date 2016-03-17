<?php
namespace Adminmodule\Model;
use Think\Model;
class CommentModel extends Model {
	protected $_validate = array(
		
	);
	protected $_auto = array ( 
         array('type','1',3),  // 新增的时候把status字段设置为1
         //array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('insert_time','get_client_time',3,'callback'), // 对update_time字段在更新的时候写入当前时间戳
		 array('comment', 'jsescape', 3, 'callback'),
		 array('comment', 'htmlspecialchars_decode', 3, 'function'),		 
     );
	 
	 public function get_client_time(){
        return date("Y-m-d");
    }
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['content'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}