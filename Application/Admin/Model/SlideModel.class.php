<?php
namespace Admin\Model;
use Think\Model;
class SlideModel extends Model {
	protected $_validate = array(
		array('name','','帐号名称已经存在！',0,'unique',3), // 在新增（1代表新增2编辑3全部）的时候验证name字段是否唯一
	);
	
}