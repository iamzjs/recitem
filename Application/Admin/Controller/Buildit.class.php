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
/**
 * 用于管理模块的自动生成
 */
class Buildit {

    static protected $controller   =  '<?php
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
	use Think\Controller;
	class [CONTROLLER]Controller extends BaseController {
	   public function _initialize(){
			parent::_initialize();
			$this->tbl="[TABLE]";
			if(session("admin.id")!=1){
				$this->where=array("userid"=>session("admin.id"));
			}
		}
		public function index(){
			//addcode
			parent::index($this->tbl,$this->where);
		}
		public function search($skey){
			parent::search($this->tbl,$skey,$this->where);
		}
		public function form(){
			//addsth
			parent::form($this->tbl);
		}
		
		public function add(){
			parent::add($this->tbl);
		}
		public function mod(){
			//addsth
			parent::mod($this->tbl);
		}
		public function update(){
			parent::update($this->tbl);
		}
		public function check(){
			parent::check($this->tbl);
		}
		public function del(){
			parent::del($this->tbl);
		}
		public function bacts(){
			$sql="";
			parent::bacts($this->tbl,$sql);
		}
		public function incform(){
			parent::incform($this->tbl);
		}
		public function inc($key){
			parent::inc($this->tbl,$key);
		}
	}';

    static protected $model         =   '<?php
	namespace Admin\Model;
	use Think\Model;
	class [MODEL]Model extends BaseModel {

	}';
    // 检测应用目录是否需要自动创建
    static public function checkDir($ctrl,$tbl,$aname='**',$params=array('name'),$paramstype=array('varchar(30) not null'),$skey='name'){
	// 生成控制器
	self::buildController(MODULE_NAME,$ctrl,$tbl);
	// 生成模型
	self::buildModel(MODULE_NAME,$ctrl);
	// 创建模板页面
	$folder = APP_PATH.'/'.MODULE_NAME.'/View/';
	if(is_writeable($folder)) {
		if(!is_dir($folder.$ctrl)){
			mkdir($folder.$ctrl,0755,true);
		 xCopy($folder.'_template',$folder.$ctrl,1);
		  file_put_contents($folder.$ctrl.'/title.html','<h2 class="text-center">'.$aname.'信息维护</h2>');
		  $html = '<load href="__PUBLIC__/ueditor143/ueditor.config.js"/>
<load href="__PUBLIC__/ueditor143/ueditor.all.min.js"/>';
		  $th = '';
		  $thv = '';
		  $colsnum=0;
		  $tbls = array();
		  $indexsql = array();
		  foreach($params as $k=>$param){
			  $colsnum++;
			  $p = explode('|',$param);
			  $pt = $paramstype[$k];
			  if(count($p)==6){
				  $colsnum++;
				  $tbls[]=$k;
				  //course|课程|kcdm|课程代码|kcmc|课程名称
				  //$tblname = $p[0];
				 // $tid = $p[2];
				  //$tidname = $p[3];
				  //$tname = $p[4];
				  //$tcname = $p[5];	
				  $name = $p[2].'xllb';
			  }
			  else{
			  $name=$p[0];
			  }
			  $isrequired = stristr($paramstype[$k],'not null');
			  $isrequired = empty($isrequired)?'':'required';
			  if(stristr($name,'xllb')){
			   $html.='<div class="form-group">
					<label for="'.$p[2].'" class="col-sm-2 control-label">'.$p[1].'</label>
					<div class="col-sm-10">';
			  }
			  else{
				  $html.='<div class="form-group">
					<label for="'.$name.'" class="col-sm-2 control-label">'.$p[1].'</label>
					<div class="col-sm-10">';
			  }
			  if(stristr($name,'xllb')){
				  //$name=str_replace('xllb','',$name);
				  if(stristr($p[2],'id')) {$id1 = 'id';$name1 = 'name';}
				  else {$id1 = $p[2];$name1 = $p[4];}
				  
				  $html.='<select class="form-control" name="'.$p[2].'" id="'.$p[2].'"><empty name="one.'.$p[2].'"><option value=""></option><else/><option value="{$one.'.$p[2].'}">{$one.'.$p[4].'}</option></empty><volist name="'.$p[0].'list" id="vo"><option value="{$vo.'.$id1.'}">{$vo.'.$name1.'}</option></volist></select>';
				  $indexsql[] ='update '.$tbl.' t left join '.$p[0].' t1 on t.'.$p[2].'=t1.'.$id1.' set t.'.$p[4].'=t1.'.$name1;
			  }
			  elseif(stristr($name,'thumb')||stristr($name,'addr')||stristr($name,'src')||stristr($name,'done')){
				  $html.='<input type="file" id="'.$name.'" name="'.$name.'" placeholder="" '.$isrequired.'>';
			  }
			  else{
				  if(stristr($pt,'char')){
					  $ppp = explode('(',$pt);
					  $pp=$ppp[1];
					  $ppp=explode(')',$pp);
					  $pp=$ppp[0];
					  if((intval($pp))<=50){
						  $html.='<input type="text" class="form-control" id="'.$name.'" name="'.$name.'" value="{$one["'.$name.'"]}"  placeholder="" '.$isrequired.'>';
					  }
					  elseif((intval($pp))<=300){
						  $html.='<textarea class="form-control" id="'.$name.'" name="'.$name.'" placeholder="" '.$isrequired.'>{$one["'.$name.'"]}</textarea>';
					  }
					  else{
						  $html.='<div class="form-group">
					    <script id="'.$name.'" name="'.$name.'" type="text/plain">{$one["'.$name.'"]}</script>       
					<script type="text/javascript">
						UE.getEditor("'.$name.'", {
						theme:"default", //皮肤
						lang:"zh-cn", //语言
						initialFrameWidth:800,  //初始化编辑器宽度,默认800
						initialFrameHeight:320,
						toolbars: [
							[
								"source",
								"undo",
								"redo",
								"bold",
								"italic",
								"underline",
								"selectall",
								"preview",
								"horizontal",
								"cleardoc",
								 "fontfamily",
								"fontsize",
								"paragraph",
								"simpleupload",
								"insertimage",
								 "link",
								"emotion",
								"justifyleft",
								"justifyright",
								"justifycenter",
								"justifyjustify",
								"forecolor",
								"backcolor",
								"insertorderedlist",
								"insertunorderedlist",
								"fullscreen",
								"imagenone",
								"imageleft",
								"imageright",
								"imagecenter",
								"lineheight", 

							]
						]
											});
					</script>

					</div>';
					  }
				  }
				   elseif(stristr($pt,'text')){
					   $html.='<div class="form-group">
					    <script id="'.$name.'" name="'.$name.'" type="text/plain">{$one["'.$name.'"]}</script>       
					<script type="text/javascript">
						UE.getEditor("'.$name.'", {
						theme:"default", //皮肤
						lang:"zh-cn", //语言
						initialFrameWidth:800,  //初始化编辑器宽度,默认800
						initialFrameHeight:320,
						toolbars: [
							[
								"source",
								"undo",
								"redo",
								"bold",
								"italic",
								"underline",
								"selectall",
								"preview",
								"horizontal",
								"cleardoc",
								 "fontfamily",
								"fontsize",
								"paragraph",
								"simpleupload",
								"insertimage",
								 "link",
								"emotion",
								"justifyleft",
								"justifyright",
								"justifycenter",
								"justifyjustify",
								"forecolor",
								"backcolor",
								"insertorderedlist",
								"insertunorderedlist",
								"fullscreen",
								"imagenone",
								"imageleft",
								"imageright",
								"imagecenter",
								"lineheight", 

							]
						]
											});
					</script>

					</div>';
				  }
				  else{
					  $html.='<input type="text" class="form-control" id="'.$name.'" name="'.$name.'" value="{$one["'.$name.'"]}"  placeholder="" '.$isrequired.'>';
				  }
			  }
			  $html.='</div></div>';
			  if(count($p)==6){
				  $th.='<th>'.$p[3].'</th><th>'.$p[5].'</th>';
				$thv.='<td>{$vo["'.$p[2].'"]}</td><td>{$vo["'.$p[4].'"]}</td>';
			  }
			  else{
				  $th.='<th>'.$p[1].'</th>';
				$thv.='<td>{$vo["'.$name.'"]}</td>';
			  }
				
		  }
		  if(!empty($tbls)){
			  $formstr='';
			  foreach($tbls as $tt){
				  $tempparam = explode('|',$params[$tt]);
				  $formstr.='$this->assign("'.$tempparam[0].'list",M("'.$tempparam[0].'")->select());';
			  }
			  sreplace(APP_PATH.MODULE_NAME.'/Controller/'.$ctrl.'Controller.class.php','//addsth',$formstr);
			  $indexstr='';
			  foreach($indexsql as $sql){
				  $indexstr.='M("")->query("'.$sql.'");';
			  }
			  sreplace(APP_PATH.MODULE_NAME.'/Controller/'.$ctrl.'Controller.class.php','//addcode',$indexstr);
		  }
		  $html .= '<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" class="btn btn-default">提交</button>
				</div>
			  </div>';
		$action = "{:U('search',array('skey'=>'".$skey."'))}";
		$formsearch = '<form class="form-horizontal" action="'.$action.'" method="post">';
		file_put_contents($folder.$ctrl.'/inputs.html',$html);
		file_put_contents($folder.$ctrl.'/th.html',$th);
		file_put_contents($folder.$ctrl.'/thv.html',$thv);
		file_put_contents($folder.$ctrl.'/formsearch.html',$formsearch);
		$colsnum += 4;
		file_put_contents($folder.$ctrl.'/colspan.html','<td colspan="'.$colsnum.'">');
		}
		//创建数据表
		$sql = 'create table if not exists '.$tbl.'(id int not null auto_increment';
		foreach($params as $k=>$param){
			$p = explode('|',$param);
			if(count($p)==6){
				$ptk = explode('|',$paramstype[$k]);
				$sql.=','.$p[2].' '.$ptk[0];
				$sql.=','.$p[4].' '.$ptk[1];
			}
			else{
				$sql.=','.$p[0].' '.$paramstype[$k];
			}
			
	}
	$sql .=',userid int not null default 1,hits int not null default 0,insert_time timestamp not null default CURRENT_TIMESTAMP,listorder int not null default 1,vstate int not null default 1,primary key(id))';
	M()->query($sql);
		
	}else{
		header('Content-Type:text/html; charset=utf-8');
		exit('应用目录['.$folder.$ctrl.']不可写，目录无法自动生成！<BR>请手动拷贝_template目录');
	}
	
	
    }
    
    // 创建控制器类
    static public function buildController($module,$controller='Index',$tbl) {
        $file   =   APP_PATH.$module.'/Controller/'.$controller.'Controller'.EXT;
        if(!is_file($file)){
            $content = str_replace(array('[MODULE]','[CONTROLLER]','[TABLE]'),array($module,$controller,$tbl),self::$controller);
            if(!C('APP_USE_NAMESPACE')){
                $content    =   preg_replace('/namespace\s(.*?);/','',$content,1);
            }
            $dir = dirname($file);
            if(!is_dir($dir)){
                mkdir($dir, 0755, true);
            }
            file_put_contents($file,$content);
        }
    }

    // 创建模型类
    static public function buildModel($module,$model) {
        $file   =   APP_PATH.$module.'/Model/'.$model.'Model'.EXT;
        if(!is_file($file)){
            $content = str_replace(array('[MODULE]','[MODEL]'),array($module,$model),self::$model);
            if(!C('APP_USE_NAMESPACE')){
                $content    =   preg_replace('/namespace\s(.*?);/','',$content,1);
            }
            $dir = dirname($file);
            if(!is_dir($dir)){
                mkdir($dir, 0755, true);
            }
            file_put_contents($file,$content);
        }
    }
}
