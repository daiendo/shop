<?php
namespace app\admin\controller;

use think\facade\Config;

/**
 * 
 */
class Order extends Error
{
	
	public function listApi(){
		$param = input('post.');
		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;

		if(!empty($param['code'])){
			$where['code'] = $param['code'];
		}

		if(!empty($param['username'])){
			$user = $this->db('user')->field('id')->where('is_delete',0)->where('username',$param['username'])->find();
			if(!$user){
				return [200,['total'=>0,'data'=>[]]];
			}
			$where['user_id'] = $user['id'];
		}
		$total = $this->db('order')->where($where)->count();

		if($total == 0){
			return [200,['total'=>0,'data'=>[]]];
		}

		$data = $this->db('order')->where($where)->page($page,10)->order('id desc')->select();

		$user = $this->db('user')->where('is_delete',0)->where('id','in',array_unique(array_column($data, 'user_id')))->select();

		$user = array_column($user, 'username','id');

		$config = Config::get('constant.');

		foreach ($data as &$value) {
			$value['contact'] = json_decode($value['contact'],true);
			$value['affirm'] = $value['affirm'] == 1 ? '已确认' : '未确认';
			$value['username'] = $user[$value['user_id']];
			$value['time'] = date('Y-m-d H:i:s',$value['time']);

			$goods = json_decode($value['order_goods'],true);
			foreach ($goods as  $g) {
				$title[] = $g['cate'].'-'.$g['name'];
			}

			$value['title'] = join('、',$title);

			$title = [];

			if(in_array($value['status'], [1,2,4,5,6])){
				$value['status_text'] = $config['order_status'][$value['status']];
			}elseif ($value['status'] == 3) {
				$value['status_text'] = $config['is_pay'][$value['is_pay']];
				
			}

			
		}

		return [200,['total' => $total,'data' =>$data]];
	}

	public function infoApi(){

		$id = input('post.id');

		$info = $this->db('order')->where('is_delete',0)->where('id',$id)->find();
		if(!$info){
			return [501,'数据错误'];
		}

		$user = $this->db('user')->where('is_delete',0)->where('id',$info['user_id'])->find();
		if(!$info){
			return [501,'用户不存在'];
		}
		$info['username'] = $user['username'];


		$config = Config::get('constant.');

		$info['affirm'] = $info['affirm'] == 1 ? '已确认' : '未确认';
		$info['time'] = date('Y-m-d H:i:s',$info['time']);
		$info['contact'] = json_decode($info['contact'],true);
		$info['order_goods'] = json_decode($info['order_goods'],true);

		foreach ($info['order_goods']  as  $v) {
				$title[] = $v['cate'].':'.$v['name'];
			}
		$info['title'] = join('、',$title);

		if(in_array($info['status'], [1,2,4,5,6])){
			$info['status_text'] = $config['order_status'][$info['status']];
		}elseif ($info['status'] == 3) {
			$info['status_text'] = $config['is_pay'][$info['is_pay']];
			
		}

		return [200,$info];
	}

	public function changeStatusApi(){

		$param = input('post.');

		if(empty($param['id']) || empty($param['status']) || !in_array($param['status'], [2,6])){
			return [500,'缺少参数'];
		}

		$info = $this->db('order')->where('is_delete',0)->where('id',$param['id'])->find();
		if(!$info){
			return [500,'数据错误'];
		}

		
		$config = Config::get('constant.');

		if($param['status'] == 6 && (in_array($info['status'], [4,5,6]) || $info['is_pay'] == 2)){
			$status = $config['order_status'][$info['status']];
			if($info['is_pay'] == 2 && $info['status'] == 4){
				$status = $config['is_pay'][$info['is_pay']];
			}

			return [501,'订单状态为'.$status.'不能取消'];
		}
		
		if($param['status'] == 2 && (in_array($info['status'], [4,5,6]) || $info['is_pay'] == 2)){
			return [501,'非法操作'];
		}

		$row = $this->db('order')->where('id',$param['id'])->update(['status'=> $param['status']]);
		if($row < 1){
			return [501,'操作失败'];
		}

		return [200,true];
		
	}

	public function deleteApi(){

		$id = input('post.id');

		$data = $this->db('order')->where('id',$id)->where('is_delete',0)->find();
		if(!$data){
			return [501,'数据错误'];

		}

		if(!in_array($data['status'], [5,6])){
			return [501,'非法操作'];
		}

		$row = $this->db('order')->where('id',$id)->update(['is_delete'=>1]);
		if($row < 1){
			return [501,'删除失败'];

		}

		return [200,'删除成功'];
	}
	

}