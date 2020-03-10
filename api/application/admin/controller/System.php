<?php
namespace app\admin\controller;

/**
 * 
 */
class System extends Error
{
	public function uploadApi(){
		$file = request()->file('file');
		if(!$file){
			return [501,'文件不存在'];
		}else{
			$info = $file->move('uploads');
			if($info){
				$name = $info->getSavename();
				
				return [200,'http://localhost/api/public/uploads/'.$name];
			}else{
				return [501,$file->getError()];
			}
		}	
	}
}