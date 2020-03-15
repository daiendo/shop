<?php
namespace app\admin\validate;


use think\Validate;

/**
 * 
 */
class Advert extends Validate
{
	
	protected $rule = [
		'id' => 'require|number|>:0',
		'title'=>'require|chsDash',
		'url' => 'require|url',
		'pos' => 'require|number|>:0',
		'img' => 'require|checkImg',
	];

	protected $scene = [
		'insert' => ['title','url','pos','img'],
		'update' => ['id','title','url','pos','img'],
	];

	protected function checkImg($value){
		if(!in_array(pathinfo($value,PATHINFO_EXTENSION), ['jpeg','jpg','png'])){
			return '请上传图片格式文件';
		}

		return true;
	}
}