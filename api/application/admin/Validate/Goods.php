<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 
 */
class Goods extends Validate
{
	
	protected $rule = [
		'id' => 'require|number',
		'name' => 'require|chsDash',
		'desc' => 'require|chsDash',
		'imgs' => 'require|checkImg',
		'stock' => 'require|number|>:0',
		'sell_price' => 'require|float|>:0',
		'class_id' =>'require|number'
	];

	protected $scene = [
		'insert' => ['name','desc','imgs','stock','sell_price','class_id'],
		'update' => ['name','desc','imgs','stock','sell_price','class_id','id'],
	];

	protected function checkImg($imgs){

		if(!is_array($imgs)){
			return '请重新上传图片!';
		}
		foreach ($imgs as $img) {
			if(!in_array(pathinfo($img, PATHINFO_EXTENSION),['jpeg','jpg','png'])){
				return '请上传jpeg、jpg、png格式的图片';
			}
		}
		return true;
	}
}