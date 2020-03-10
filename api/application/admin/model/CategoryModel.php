<?php

namespace app\admin\model;

use think\Model;

/**
 * 
 */
class CategoryModel extends Model
{
	
	protected $table = 'class';

	public function getHtmlList(&$result = [],$pid = 0,$prefx = ''){
		$data = $this->where(['is_delete'=>0])->select()->toarray();
		// return $data;

		foreach ($data as  $value) {
			if($value['parent_id'] == $pid){
				$value['name'] = $prefx.$value['name'];
				$result[] = $value;
				$this->getHtmlList($result,$value['id'],$prefx.'|--');
			}
		}
		return $result;
	}
}