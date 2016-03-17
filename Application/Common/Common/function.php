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
function tree_set($index){
			
			global $menu,$str;
			M()->query('select * from techno where parentid='.$index.' order by listorder desc,id');
			if(M('techno')->where(array('parentid'=>$index))->count()==0){
				return;
			}
			$techlist =M('techno')->where(array('parentid'=>$index))->order('listorder desc,id')->select();
			foreach($techlist as $arr){
				for($i=0;$i<(int)$arr['layer']-1;++$i){
					$str.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
				for($i=0;$i<(int)$arr['layer'];++$i){
					$str.='|';
				}
				$str.='--';
				$menu.='<option value="'.$arr['id'].'">'.$str.$arr['name'].'</option>';
				$str='';
				tree_set($arr['id']);
		}
}
function write_html(){
	global $menu;
	tree_set(0);
	return $menu;
}
function del_dir($dir) 
{ 
$docroot =  $_SERVER['DOCUMENT_ROOT'];
$dir = $_SERVER['DOCUMENT_ROOT'].$dir;

$dir = str_replace('//','/',$dir);

if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { 
$dir = str_replace('/','\\',$dir);
    $str = "rmdir /s/q " . $dir; 
} else { 
    $str = "rm -Rf " . $dir; 
}
exec($str);
}
function del_file($file) 
{ 
$docroot =  $_SERVER['DOCUMENT_ROOT'];
$file = $_SERVER['DOCUMENT_ROOT'].$file;

$file = str_replace('//','/',$file);
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { 

$file = str_replace('/','\\',$file);
    $str = "del /f/s/q " . $file; 
} else { 
    $str = "rm -Rf " . $file; 
}
exec($str);
}

function copyrkfile($file){
	
	$file = $_SERVER['DOCUMENT_ROOT'].$file;
    $docroot =  $_SERVER['DOCUMENT_ROOT'].__ROOT__;
	$file = str_replace('//','/',$file);
	$docroot = str_replace('//','/',$docroot);
	if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { 
	$docroot = str_replace('/','\\',$docroot);
	$file = str_replace('/','\\',$file);
		$str = "move /y ".$file.' '.$docroot; 
	} else { 
		$str = "mv -f ".$file.' '.$docroot; 
	}
	exec($str);
}
function xCopy($source,$destination,$child){
	//child 0不拷贝子目录1拷贝子目录
	if(!is_dir($source)){
		echo("Error:the $source is not a direction!");
		return 0;
	}
	if(!is_dir($destination)){
		mkdir($destination,0777);
	}
	$handle=dir($source);
	while($entry=$handle->read()){
		if(($entry!=".")&&($entry!="..")){
			if(is_dir($source."/".$entry)){
				if($child)
					xCopy($source."/".$entry,$destination."/".$entry,$child);
			}
			else{
				copy($source."/".$entry,$destination."/".$entry);
			}
		}
	}
	return 1;
}
function session_deal($name){
				session('admin',$name);
				$modal2 = M('category');
				$parentlist = $modal2->where('parentid=0')->select();
				$catlist=array();
				for($i=0;$i<count($parentlist);$i++){
					$cat = array();
					$cat['catid'] = $parentlist[$i]['id'];
					$cat['name'] = $parentlist[$i]['name'];
					$cat['subcat'] = $modal2->where('parentid='.$parentlist[$i]['id'])->select();
					$catlist[]=$cat;
					
				}
				session('catlist',$catlist);
	}

function MyPageSql($sql,$order='listorder desc,id desc',$per=6){
		//$count      = $model->where($where)->count();// 查询满足要求的总记录数
		$count = count(M()->query($sql));
		$Page       = new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$sql = $sql.' order by '.$order.' limit '.$Page->firstRow.','.$Page->listRows;
		$list = M()->query($sql);
		
		return array('show'=>$show,'list'=>$list);
}

function MyPage($model,$where,$per=20){
		$count      = $model->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,$per);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $model->where($where)->order('listorder desc,id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		return array('show'=>$show,'list'=>$list);
}
 function checkstr($needle,$str){
   //判断$str是否包含$needle这个字符串
    $tmparray = explode($needle,$str);
    if(count($tmparray)>1){
    return 1;
    } else{
    return 0;
    }
}

function vscandir($dir){
	$model_cat=M('videocat');
	$model=M('video');
	 set_time_limit(0);
	 foreach(scandir($dir) as $v){
		 if(is_dir($dir.'/'.$v)){
			 if($v == '.' ||$v=='..') continue;
			 //echo $dir.'/'.$v.'<br/>';
			 echo iconv('GBK','UTF-8',$v);
			 $strc = iconv('GBK','UTF-8',$v);
			 $where_cat = array('name'=>$strc);
			 if(!$model_cat->where($where_cat)->find())
			 $model_cat->add($where_cat);
			 echo $model_cat->getLastSQL();
			$cid = $model_cat->where($where_cat)->getField('id');
				 foreach(scandir($dir.'/'.$v) as $vv){
					 if($vv == '.' ||$vv=='..') continue;
					 $strv = iconv('GBK','UTF-8',$vv);
					 $names = explode('.',$strv);
					 $where_v = array('categoryid'=>$cid,'name'=>$names[0],'vaddress'=>'video/'.$strc.'/'.$strv,'desc'=>$names[0]);
					 if(!$model->where(array('categoryid'=>$cid,'name'=>$names[0]))->find())
					 $model->add($where_v);
					 //echo $dir.'/'.$v.'/'.$vv.'<br/>';
				 }
			 
		 }
	 }
 }  
 function u2u($str){
	 return iconv('utf-8','utf-8',$str);
}
function g2u($str){
	 return iconv('gbk','utf-8',$str);
}

function insertTable($table,$data){
	$model=M($table);	
	if(!$model->where(array('name'=>$data))->find()){
		//不存在则创建
		$model->add(array('name'=>$data));
	}
	return $model->where(array('name'=>$data))->getField('id');
}
/** 
 +---------------------------------------------------------- 
 * 字符串截取，支持中文和其他编码 
 +---------------------------------------------------------- 
 * @static 
 * @access public 
 +---------------------------------------------------------- 
 * @param string $str 需要转换的字符串 
 * @param string $start 开始位置 
 * @param string $length 截取长度 
 * @param string $charset 编码格式 
 * @param string $suffix 截断显示字符 
 +---------------------------------------------------------- 
 * @return string 
 +---------------------------------------------------------- 
 */  
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)  
{  
    if(function_exists("mb_substr")){   
    if($suffix)   
      return mb_substr($str, $start, $length, $charset)."...";  
      else  
      return mb_substr($str, $start, $length, $charset);   
       }  
    elseif(function_exists('iconv_substr')) {  
        if($suffix)   
       return iconv_substr($str,$start,$length,$charset)."...";  
       else  
       return iconv_substr($str,$start,$length,$charset);  
    }  
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";  
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";  
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";  
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";  
    preg_match_all($re[$charset], $str, $match);  
    $slice = join("",array_slice($match[0], $start, $length));  
    if($suffix) return $slice."…";  
    return $slice;  
}
function getCSVdata($filename)
{   
	setlocale(LC_ALL,array('zh_CN.gbk','zh_CN.gb2312','zh_CN.gb18030'));
    $row = 1;//第一行开始
    if(($handle = fopen($filename, "r")) !== false) 
    {
        while(($dataSrc = fgetcsv($handle)) !== false) 
        {
            $num = count($dataSrc);
            for ($c=0; $c < $num; $c++)//列 column 
            {
                if($row === 1)//第一行作为字段 
                {
                    $dataName[] = g2u($dataSrc[$c]);//字段名称
					
                }
                else
                {
					
                    foreach ($dataName as $k=>$v)
					{
						//$k为序号012，$v为序号对应的字段名称					
						if($k == $c)//对应的字段
						{
							$data[$v] = g2u($dataSrc[$c]);
						}
						
                    }
					
                }
            }
            if(!empty($data))
            {
                 $dataRtn[] = $data;
                 unset($data);
            }
            $row++;
        }
        fclose($handle);
        return $dataRtn;
    }
}
/**
     +----------------------------------------------------------
     * Export Excel | 2013.08.23
     * Author:HongPing <hongping626@qq.com>
     +----------------------------------------------------------
     * @param $expTitle     string File name
     +----------------------------------------------------------
     * @param $expCellName  array  Column name
     +----------------------------------------------------------
     * @param $expTableData array  Table data
     +----------------------------------------------------------
     */
    function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $_SESSION['loginAccount'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
          // Miscellaneous glyphs, UTF-8   
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
     
  function importExcel($filePath){
	/*导入phpExcel核心类 */
	Vendor("PHPExcel.PHPExcel");
    $PHPExcel = new PHPExcel(); 

  /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
  $PHPReader = new PHPExcel_Reader_Excel2007(); 
	if(!$PHPReader->canRead($filePath)){ 
	  $PHPReader = new PHPExcel_Reader_Excel5(); 
	  if(!$PHPReader->canRead($filePath)){ 
		echo 'no Excel'; 
		return; 
	  } 
	} 
  
  $PHPExcel = $PHPReader->load($filePath); 
  $currentSheet = $PHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
  $excelarray = $currentSheet->toArray();
  return toAssocArray($excelarray);
 
}
function toAssocArray($array){
	foreach($array as $row=>$dataSrc){
		$num = count($dataSrc);
		for ($c=0; $c < $num; $c++)//列 column 
		{
			if($row === 0)//第一行作为字段 
			{
				$dataName[] = g2u($dataSrc[$c]);//字段名称
				
			}
			else
			{
				foreach ($dataName as $k=>$v)
				{
					//$k为序号012，$v为序号对应的字段名称					
					if($k == $c)//对应的字段
					{
						$data[$v] = $dataSrc[$c];
					}
					
				}
				
			}
		}
		if(!empty($data))
            {
                 $dataRtn[] = $data;
                 unset($data);
            }
	}
	return $dataRtn;
}
function expUser(){//导出Excel
        $xlsName  = "User";
        $xlsCell  = array(
            array('id','账号序列'),
            array('account','登录账户'),
            array('nickname','账户昵称')
        );
        $xlsModel = M('Post');
        $xlsData  = $xlsModel->Field('id,account,nickname')->select();
        $this->exportExcel($xlsName,$xlsCell,$xlsData);
    }

function namereplace($source,$sname,$nickname){
			$handle=dir($source);
			while($entry=$handle->read()){
				if(($entry!=".")&&($entry!="..")){
					
					$str=file_get_contents($source.'/'.$entry);
					$str=str_replace('namespace '.$sname.'\Controller','namespace '.$nickname.'\Controller',$str);
				 file_put_contents($source.'/'.$entry,$str);
					
				}
			}
}

function namereplace2($source,$sname,$nickname){
			$handle=dir($source);
			while($entry=$handle->read()){
				if(($entry!=".")&&($entry!="..")){
					
					$str=file_get_contents($source.'/'.$entry);
					$str=str_replace('namespace '.$sname.'\Model','namespace '.$nickname.'\Model',$str);
				 file_put_contents($source.'/'.$entry,$str);
					
				}
			}
}
function bindreplace($source,$sname,$nickname){
		
	$str=file_get_contents($source);
	$str=str_replace("define('BIND_MODULE','$sname')","define('BIND_MODULE','$nickname')",$str);
	file_put_contents($source,$str);

}

function sreplace($source,$sname,$nickname){
		
	$str=file_get_contents($source);
	$str=str_replace($sname,$nickname,$str);
	file_put_contents($source,$str);

}
function getAssocBySql($sql,$fields=array('id','name')){
			$id = $fields[0];
			$name = $fields[1];
			$slist = M()->query($sql);
			 $string = '<option value="">==请选择==</option>';
			 for($i = 0; $i < count ( $slist ); $i++) {
				 $string = $string. "<option value='" . $slist [$i] [$id] . "'>" . $slist [$i] [$name] . "</option>";
			 }
			 echo $string;
}
?>