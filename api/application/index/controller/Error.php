<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

/**
 * 
 */
class Error extends Controller
{
	//在重写父类方法时必须传入相同参数
	public function index(Request $request)
	{
		return [404,'not found '. $request->controller() .' controller'];
	}
	public function _empty($fnc){
		return [400,'no such servier '.$fnc];
	}
}