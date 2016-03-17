<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model {
	protected $_validate = array(
		array('name','require','用户名必须！'), //默认情况下用正则进行验证
		array('name','','帐号名称已经存在！',0,'unique',1), // 在字段非空的时候验证name字段是否唯一
		array('sex',array('男','女'),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
		array('pwdCheck','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
		array('email','email','邮箱格式不正确',2), // 验证邮箱
		array('zipcode','/^\\d{6}$/','邮编格式不正确',2), // 验证邮编
		array('sfz','/^^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/','身份证格式不正确',2), // 验证身份证
		array('academic','require','学历必选',1), // 验证学历
		array('tel','/^1\\d{10}$/','手机格式不正确',2,'regex'), // 验证手机
		array('hobby','require','爱好必选',1), // 验证爱好
		array('url','url','url格式不正确',2), // 验证url
		// array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
	);
	
	protected $_auto = array ( 
         array('intro', 'jsescape', 3, 'callback'),
		 array('intro', 'htmlspecialchars', 3, 'function'),		 
     );
	 
	 public function get_client_time(){
        return date("Y-m-d");
    }
	
	public function jsescape(){
		$preg = "/<script[\s\S]*?<\/script>/i";
		return preg_replace($preg,"",$_POST['intro'],3);    //第四个参数中的3表示替换3次，默认是-1，替换全部
    }
}