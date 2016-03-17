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
namespace Admin\Model;
use Think\Model;
class BaseModel extends Model {
	protected $_validate = array(
		array('name','','标题已经存在！',0,'unique',3), // 在新增（1代表新增2编辑3全部）的时候验证name字段是否唯一
		array('name','require','标题不能为空！'), //默认情况下用正则进行验证
	);
	protected $_auto = array ( 
         //array('author','admin',3),  // 新增的时候把status字段设置为1
         //array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         //array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         //array('insert_time','get_client_time',3,'callback'), // 对update_time字段在更新的时候写入当前时间戳
		 array('content', 'jsescape', 3, 'callback'),
		 array('content', 'htmlspecialchars_decode', 3, 'function'),		 
     );	
	 public function get_client_time(){
        return date("Y-m-d");
    }
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['content'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}