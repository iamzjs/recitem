<?php
namespace Admin\Model;
use Think\Model;
class CorpinfoModel extends Model {
	protected $_validate = array(
		//array('name','','帐号名称已经存在！',0,'unique',3), // 在新增（1代表新增2编辑3全部）的时候验证name字段是否唯一
	);
	protected $_auto = array ( 
		 array('contact', 'jsescape', 3, 'callback'),
		 array('contact', 'htmlspecialchars_decode', 3, 'function'),		 
     );
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['contact'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}