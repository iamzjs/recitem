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
namespace Admin\Controller;
use _Admin\Controller\BaseadminController;
class BaseController extends BaseadminController {
	public function _initialize(){
		parent::_initialize();
		$this->moduleid =M('module')->where('name="'.MODULE_NAME.'"')->getField('id');
		$this->assign('funclist',M('func')->where(array('vstate'=>1,'moduleid'=>$this->moduleid))->order('listorder desc,id asc')->select());
		$this->assign('modlist',M('module')->where(array('vstate'=>1,'type'=>1))->order('listorder desc,id asc')->select());
		$this->assign('mod2list',M('module')->where(array('vstate'=>1,'type'=>2,'id>6'))->order('listorder desc,id asc')->select());
	}
}