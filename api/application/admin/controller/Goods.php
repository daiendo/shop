<?php
namespace app\admin\controller;

/**
 * 
 */
class Goods extends Error
{
	
	public function listApi(){
		$param = input('post.');
		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;

		if(!empty($param['name'])){
			$where['name'] = $param['name'];
		}

		if(!empty($param['class_name'])){
			$class = $this->db('class')->field('id')->where('name',$param['class_name'])->where('is_delete',0)->find();
			if(!$class){
				return [200,['total'=>0,'data'=>[]]];
			}

			$where['class_id'] = $class['id'];
		}


		$total = $this->db('shop')->where($where)->count();
		if($total == 0){
			return [200,['total'=>0,'data'=>[]]];
		}

		$data = $this->db('shop')->field('id,name,imgs,stock,shelf,sell_price,class_id,create_time')->where($where)->order('id desc')->page($page,10)->select();

		$classId = array_unique(array_column($data, 'class_id'));

		$class = $this->db('class')->field('id,name')->where('id','in',$classId)->select();

		$className = array_column($class, 'name','id');

		foreach ($data as &$value) {
			
			$value['class_name'] = $className[$value['class_id']];
			$value['imgs'] = json_decode($value['imgs'],true);
			$value['thumb'] = $value['imgs'][0];

			$value["create_time"] = date('Y-m-d H:i:s',$value["create_time"]);
		}

		return [200,['total'=>$total,'data'=>$data]];
	}

	public function insertApi(){

		$param = input('post.');

		// $param['imgs'] = json_decode($param['imgs'],true);


		$error = $this->validate($param,'Goods.insert');

		if(true !== $error){
			return [501,$error];
		}

		$goods = $this->db('shop')->where('name',$param['name'])->where('is_delete',0)->find();
		if($goods){
			return [500,'该商品已经存在'];
		}

		$row = $this->db('shop')->insert([
			'name' => $param['name'],
			'imgs' => json_encode($param['imgs']),
			'stock' => $param['stock'],
			'sell_price' => $param['sell_price'],
			'class_id' => $param['class_id'],
			'desc' => $param['desc'],
			'shelf' => 0,
			'is_delete' =>0,
			'create_time'=> time(),
		]);

		if($row < 0){
			return [501,'添加失败'];
		 }

		 return [200,true];
	}

	public function detailApi(){

		$id = input('post.id');

		$info = $this->db('shop')->where('id',$id)->where('is_delete',0)->find();

		if(!$info){
			return [501,'数据错误'];
		}

		$info['imgs'] = json_decode($info['imgs'],true);

		$class = $this->db('class')->field('name')->where('id',$info['class_id'])->find();
		$info['class_name'] = $class['name'];

		return [200,$info];
	}

	public function updateApi(){

		$param = input('post.');


		$error = $this->validate($param,'Goods.update');

		if(true !== $error){
			return [501,$error];
		}

		$goods = $this->db('shop')->where('name',$param['name'])->where('id','<>',$param['id'])->where('is_delete',0)->find();
		if($goods){
			return [500,'该商品已经存在'];
		}

		$row = $this->db('shop')->where('id',$param['id'])->update([
			'name' => $param['name'],
			'imgs' => json_encode($param['imgs']),
			'stock' => $param['stock'],
			'sell_price' => $param['sell_price'],
			'class_id' => $param['class_id'],
			'desc' => $param['desc'],
			'shelf' => 0,
			'is_delete' =>0,
			'create_time'=> time(),
		]);

		if($row < 0){
			return [501,'添加失败'];
		 }

		 return [200,true];
	}

	public function deleteApi(){

		$id = input('post.id');
		$info = $this->db('shop')->where('id',$id)->where('is_delete',0)->find();

		if(!$info){
			return [501,'数据错误'];
		}

		$row = $this->db('shop')->where('id',$id)->update(['is_delete'=>1]);

		if($row < 1){
			return [501,'删除失败'];
		}

		return [200,true];
	}

	public function changeStatusApi(){

		$id = input('post.id');
		$info = $this->db('shop')->where('id',$id)->where('is_delete',0)->find();

		if(!$info){
			return [501,'数据错误'];
		}

		$shelf = $info['shelf'] == 1 ? 0 : 1;

		$row = $this->db('shop')->where('id',$id)->update(['shelf'=> $shelf]);

		if($row < 1){
			return [501,'删除失败'];
		}

		return [200,true];
	}
}