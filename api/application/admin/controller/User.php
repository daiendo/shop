<?php

namespace app\admin\controller;

class User extends Error
{
	
	public function listApi()
	{
		$param = input('post.');
		$page = $param['page'] ?? 1;

		$where['is_delete'] =0;
		// $where['admin']= 0;

		if(!empty($param['username'])){
			$where['username'] = $param['username'];
		}

		$total = $this-> db('user')->where($where)->count();
		if($total == 0){
			return [200,['total' =>0,'data'=>[]]];
		}
		$data = $this-> db('user')->where($where)->page($page,10)->order('id desc')->select();
		foreach ($data as &$value) {
			$value['time'] = date('Y-m-d H:i:s',$value['time']);
			$value['admin_text'] = $value['admin'] == 1 ? '管理员' : '用户';
		}
		return [200,['total' =>$total,'data'=>$data]];
	}

	public function insertApi(){
		$param = input('post.');
		$error = $this->validate($param,'User.insert');

		if(true !==$error){
			return [500,$error];
		}
		$user = $this->db('user')->where('username',$param['username'])->where('is_delete',0)->find();
		if($user){
			return [501,'用户已存在'];
		}

		$row = $this->db('user')->insert([
			'username'=> $param['username'],
			'password'=>md5($param['password']),
			'head_url'=>$param['head_url'],
			'admin'=>$param['admin'],
			'status'=>$param['status'],
			'time'=>time(),
			'is_delete'=>0, 
		]);
		if($row < 1){
			return [500,'添加失败'];
		}else{
			return [200,'添加成功'];
		}
	}
	public function updateApi(){
		$param = input('post.');
		$error = $this->validate($param,'User.update');

		if(true !==$error){
			return [500,$error];
		}
		$user = $this->db('user')->where('username',$param['username'])->where('id','<>',$param['id'])->where('is_delete',0)->find();
		if($user){
			return [501,'用户已存在'];
		}

		$row = $this->db('user')->where('id',$param['id'])->update([
			'username'=> $param['username'],
			'password'=>md5($param['password']),
			'head_url'=>$param['head_url'],
			'admin'=>$param['admin'],
			'status'=>$param['status'],
			'is_delete'=>0, 
		]);
		if($row < 0){
			return [500,'添加失败'];
		}else{
			return [200,'添加成功'];
		}
	}
	public function infoApi(){
		$param=input('post.');

		$error = $this->validate($param,'User.info');

		if(true !== $error){
			return [500,$error];
		}
		$id=$param['id'];
		
		$info = $this->db('user')->where('id',$id)->field('id,username,head_url,admin,status,time')->find();
		if(!$info){
			return [501,'用户不存在'];
		}else{
			$info['time']=date('Y-m-d H:i:s',$info['time']);
			return [200,$info];
		}
	}
	public function deleteApi(){
		$id=input('post.id');
		$info = $this->db('user')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}else{
			$row = $this->db('user')->where('id',$id)->update(['is_delete'=>1]);
			if($row <1){
				return [501,'删除失败'];
			}else{
				return [200,true];
			}
		}
	}
	public function changeStatusApi(){
		$id=input('post.id');
		$info = $this->db('user')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}else{
			$status =$info['status'] == 1 ? 2 : 1;
			$row = $this->db('user')->where('id',$id)->update(['status'=>$status]);
			if($row <1){
				return [501,'删除失败'];
			}else{
				return [200,true];
			}
		}
	}
}