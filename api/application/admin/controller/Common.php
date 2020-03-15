<?php
namespace app\admin\controller;

use think\facade\Cache;
/**
 * 
 */
class Common extends Error
{
	
	public function loginApi(){
		$param = input('post.');

		if(empty($param['username']) || empty($param['password'])){
			return [500,'请输入用户名和密码'];
		}

		$user = $this->db('user')->where('is_delete',0)->where('username',$param['username'])->find();

		if(!$user || $user['status'] != 1 || $user['admin'] !=1){
			return [500,'该账号未开通，请联系管理员'];
		}
		if(strcmp(md5($param['password']), $user['password']) !=0){
			return [501,'密码错误'];
		}

		$token = md5(time().$user['id']);

		Cache::set($token,[
			'id'=>$user['id'],
			'username' => $user['username'],
			'head_url' => $user['head_url'],
			'login_time'=>date("Y-m-d H:i:s")
		],3600*2);

		return [200,['token'=>$token]];
	}

	public function loginInfoApi(){
		return [200,$this->s_user];
	}

	public function outApi(){
		Cache::rm($this->token);
		return [300,'退出成功'];
	}
	public function changePassApi(){
		$param = input('post.');

		$user = $this->db('user')->where('is_delete',0)->where('status',1)->where('admin',1)->where('id',$this->s_user['id'])->find();
		if(!$user){
			return [300,'用户信息错错误'];
		}

		$error = $this->validate($param,'User.changePass');

		if(true !== $error){
			return [501,$error];
		}

		if(strcmp(md5($param['oldpassword']), $user['password'])){
			return [501,'原密码错误'];
		}

		
		$row = $this->db('user')->where('id',$this->s_user['id'])->update([
			'password' => md5($param['password']),
		]);

		if($row < 0){
			return [500,'修改失败'];
		}

		return [300,'修改成功，重新登陆'];
	}
}