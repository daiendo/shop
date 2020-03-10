<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 
 */
class Error extends Controller
{
	//在重写父类方法时必须传入相同参数
	public function indexApi(Request $request)
	{
		return [404,'not found '. $request->controller() .' controller'];
	}
	public function _empty($fnc){
		return [400,'no such servier '.$fnc];
	}

	protected function db($table = ''){
		if(!$table){
			return null;
		}
		return Db::table($table);
	} 
	public function initialize(){
		header("Access-Control-Allow-Origin:*");
	}
}