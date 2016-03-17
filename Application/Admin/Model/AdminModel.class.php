<?php
namespace Admin\Model;
use Think\Model;
class SlideModel extends Model {
	protected $_validate = array(
		array('username','require','用户名必须！'), //默认情况下用正则进行验证
		array('password','require','密码必须！'), //默认情况下用正则进行验证
	);
	
}