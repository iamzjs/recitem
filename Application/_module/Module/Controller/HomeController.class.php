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
namespace Module\Controller;
use _Home\Controller\HbaseController;
class HomeController extends HbaseController {
	protected $moduleid;
	public function _initialize(){
		parent::_initialize();
		$this->moduleid =M('module')->where('name="Admin'.MODULE_NAME.'"')->getField('id');
		$this->assign('modname',strtolower(MODULE_NAME));
		$recposlist = M('recpos')->where(array('moduleid'=>$this->moduleid))->select();
		$this->model=M();
		$reclist=array();
		foreach($recposlist as $r){
			$rec = array();
			$rec['recname']=$r['name'];
			$rec['rectbl']=strtolower(MODULE_NAME);
			$rec['reclist']=$this->model->query('select t3.* from itempos t1 left join recpos t2 on t1.posid = t2.id left join '.$rec['rectbl'].' t3 on t1.itemid = t3.id  where t1.posid='.$r['id'].' and t2.moduleid = '.$this->moduleid.' order by t1.listorder desc,t1.id desc  limit 0,8');
			$reclist[]=$rec;
		}
		$this->assign('reclist',$reclist);
	}	
}