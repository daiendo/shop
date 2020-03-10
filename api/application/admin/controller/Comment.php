<?php
namespace app\admin\controller;

/**
 * 
 */
class Comment extends Error
{
	
	public function listApi(){
		$param = input('post.');

		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;

		if(!empty($param['username'])){
			$user = $this->db('user')->field('id')->where('username',$param['username'])->where('is_delete',0)->find();
			if(!$user){
				return [200,['total'=> 0,'data'=> []]];
			}
			$where['user_id'] = $user['id'];
		}

		if(!empty($param['goods_name'])){
			$goods = $this->db('shop')->field('id')->where('name',$param['goods_name'])->where('is_delete',0)->find();
			if(!$goods){
				return [200,['total'=> 0,'data'=> []]];
			}
			$where['shop_id'] = $goods['id'];
		}

		$total = $this->db('comment')->where($where)->count();
		if($total == 0){
			return [200,['total'=> 0,'data'=> []]];
		}

		$data = $this->db('comment')->where($where)->page($page,10)->order('id desc')->select();

		$users = $this->db('user')->field('id,username')->where('id','in',array_unique(array_column($data, 'user_id')))->select();
		$users = array_column($users, 'username','id');
		$goods = $this->db('shop')->field('id,name')->where('id','in',array_unique(array_column($data, 'shop_id')))->select();
		$goods = array_column($goods, 'name','id');

		foreach ($data as &$value) {
			$value['goods_name'] = $goods[$value['shop_id']];
			$value['username'] = $users[$value['user_id']];


			// $value['username'] = $users[$value['user_id']];
			$value['time'] = date('Y-m-d H:i:s',$value['time']);
		}
		return [200,['total' => $total,'data' => $data]];
	}

	public function deleteApi(){

		$id = input('post.id');

		$data = $this->db('comment')->where('id',$id)->where('is_delete',0)->find();
		if(!$data){
			return [501,'数据错误'];

		}

		$row = $this->db('comment')->where('id',$id)->update(['is_delete'=>1]);
		if($row < 1){
			return [501,'删除失败'];

		}

		return [200,'删除成功'];
	}
}