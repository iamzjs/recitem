<?php
namespace Profile\Model;
use Think\Model;
class CommentModel extends Model {
	protected $_validate = array(
		//array('id,userid','','已经评论过！',0,'unique',1), // 在字段非空的时候验证name字段是否唯一
	);
	protected $_auto = array ( 
         array('type','1',1),  // 自动把type字段设置为1
         array('insert_time','get_client_time',1,'callback'), // 对update_time字段在更新的时候写入当前时间戳
		 array('comment', 'jsescape', 3, 'callback'),
		 array('comment', 'htmlspecialchars', 3, 'function'),		 
     );
	 
	 public function get_client_time(){
        return date("Y-m-d");
    }
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['comment'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}