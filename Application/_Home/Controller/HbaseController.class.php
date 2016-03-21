<?php
namespace _Home\Controller;
use Think\Controller;
class HbaseController extends Controller {
	protected $corp_model;
	protected $modlist;
	protected $tlist;
	public function _initialize(){
		//基本信息
		$this->corp_model=M('corpinfo');
		$corp = $this->corp_model->find();
		$this->assign('corp',$corp);
		//模块列表
		$this->modlist = M('module')->where('id>2 and type=2')->order('listorder desc')->select();
		$this->assign('modlist',$this->modlist);
		
		
		//推荐资源
		$model = M();
		$reclist=array();
		foreach($this->modlist as $r){
			$rec = array();
			$rec['recname']=$r['cname'];
			$rec['rectbl']=strtolower($r['name']);
			$moduleid = M('module')->where(array('name'=>'Admin'.$rec['rectbl']))->getField('id');
			$rec['reclist']=$model->query('select distinct t3.* from itempos t1 left join recpos t2 on t1.posid = t2.id left join '.$rec['rectbl'].' t3 on t1.itemid = t3.id  where t2.moduleid = '.$moduleid.' order by t1.listorder desc,t1.id desc  limit 0,8');
			$reclist[]=$rec;
		}
		$this->assign('reclist',$reclist);
	}	
}