<?php
namespace app\admin\validate;

use think\Validate;
/**
 * 
 */
class System extends Validate
{
	
	protected $rule = [
		'id' => 'require|number|>:0',
		'title'=>'require|chsDash',
		'keywords' => 'require|chsDash',
		'description' => 'require|chsDash',
		'logo' => 'require|checkImg',
	];

	protected $scene = [
		'insert' => ['title','keywords','description','logo'],
		'update' => ['id','title','keywords','description','logo'],
	];

	protected function checkImg($value){
		if(!in_array(pathinfo($value,PATHINFO_EXTENSION), ['jpeg','jpg','png'])){
			return '请上传图片格式文件';
		}

		return true;
	}
}