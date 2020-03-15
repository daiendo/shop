<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\facade\Cache;
use think\facade\Config;

/**
 * 
 */
class Error extends Controller
{
	protected $s_user;
	protected $token;

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
		$this->validateToken();
	}

	protected function validateToken(){

		$rule = Config::get('rule.');

		if(!in_array($this->request->url(), $rule)){
				$token = input('post.token');
				if(!$token || !Cache::has($token)){
					throw new \Exception("请登录");
				}

				$this->token = $token;
				$this->s_user = Cache::get($token);
		}
	


		
	}

}