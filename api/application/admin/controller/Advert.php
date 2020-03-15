<?php

namespace app\admin\controller;

/**
 * 
 */
class Advert extends Error
{
	
	public function listApi(){
		$param = input('post.');

		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;

		if(!empty($param['title'])){
			$where['title'] = $param['title'];
		}
		if(!empty($param['pos'])){
			$where['pos'] = $param['pos'];
		}

		$total = $this->db('advert')->where($where)->count();
		if($total == 0){
			return [200,['total'=>0,'data'=>[]]];
		}

		$data = $this->db('advert')->where($where)->page($page,10)->order('id desc')->select();

		return [200,['total'=>$total,'data'=>$data]];
	}

	public function insertApi(){
		$param = input('post.');

		$error =$this->validate($param,'Advert.insert');

		if(true !== $error){
			return [501,$error];
		}

		$advert = $this->db('advert')->where('pos',$param['pos'])->where('is_delete',0)->find();
		if($advert){
			return [501,'该位置已经存在广告'];
		}
		$row = $this->db('advert')->insert([
			'title' => $param['title'],
			'url' => $param['url'],
			'pos' => $param['pos'],
			'img' => $param['img'],
			'is_delete' => 0
		]);

		if($row < 1){
			return [501,'添加失败'];
		}
		return [200,true];
	}

	public function infoApi(){
		$id = input('post.id');

		$info = $this->db('advert')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}

		return [200,$info];
	}

	public function deleteApi(){
		$id = input('post.id');

		$info = $this->db('advert')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}
		$row = $this->db('advert')->where('id',$id)->update(['is_delete'=> 1]);
		if($row < 1){
			return [501,'删除失败'];
		}
		return [200,'删除成功'];
	}

	public function updateApi(){
		$param = input('post.');

		$error =$this->validate($param,'Advert.update');

		if(true !== $error){
			return [501,$error];
		}

		$advert = $this->db('advert')->where('pos',$param['pos'])->where('id','<>',$param['id'])->where('is_delete',0)->find();
		if($advert){
			return [501,'该位置已经存在广告'];
		}
		$row = $this->db('advert')->where('id',$param['id'])->update([
			'title' => $param['title'],
			'url' => $param['url'],
			'pos' => $param['pos'],
			'img' => $param['img'],
		]);

		if($row < 0){
			return [501,'修改失败'];
		}
		return [200,true];
	}
}