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
namespace Adminmodule\Controller;
use _Admin\Controller\BaseadminController;
class BaseController extends BaseadminController {
	public function _initialize(){
		parent::_initialize();
		$this->moduleid =M('module')->where('name="'.MODULE_NAME.'"')->getField('id');	
		$this->assign('funclist',M('func')->where(array('vstate'=>1,'moduleid'=>$this->moduleid))->order('listorder desc,id asc')->select());
		$this->assign('mfunclist',M('func')->where(array('vstate'=>1,'moduleid'=>1))->order('listorder desc,id asc')->select());
		$this->isadmin = M('user_usergroup')->where(array('groupid'=>2,'userid'=>session('admin.id')))->count();
		$this->assign('isadmin',$this->isadmin);
	}
}