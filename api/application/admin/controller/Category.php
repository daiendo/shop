<?php
namespace app\admin\controller;

use app\admin\model\CategoryModel;
/**
 * 
 */
class Category extends Error
{
	
	public function listApi(){
		$param = input('post.');
		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;
		$where['parent_id'] = 0;

		if(!empty($param['name'])){
			$where['name'] = $param['name'];
			// echo $where['name'] ;
		}

		if(!empty($param['id'])){
			$where['parent_id'] = $param['id'];
			// echo "1";
		}

		$total = $this->db('class')->where($where)->count();
		if($total == 0){
			return [200,['total' => 0,'data' => []]];
		}

		$data = $this->db('class')->where($where)->page($page,10)->order('id desc')->select();

		return [200,['total'=>$total,'data'=>$data]];
	}

	public function treeListApi(){
		$list = $this->db('class')->field('id,name as title,parent_id')->where('is_delete',0)->select();

		$data = array_combine(array_column($list, 'id'), $list);

		foreach ($data as $value) {
			$data[$value['parent_id']]['children'][] = &$data[$value['id']];
		}

		return [200,$data[0]['children']];
	}

	public function insertApi(){

		$param = input('post.');

		if(empty($param['name'])){
			return [500,'分类名不能为空'];
		}

		$parentId = 0;
		if(!empty($param['parent_id'])){
			$parentId = $param['parent_id'];
		}

		$cate = $this->db('class')->where('is_delete',0)->where('name',$param['name'])->find();
		if($cate){
			return [501,'该分类已经存在'];
		}

		$row = $this->db('class')->insert([
			'name' => $param['name'],
			'parent_id' => $parentId,
			'create_time' => time(),
			'is_delete' => 0,
		]);

		if($row < 1){
			return [501,'插入失败'];
		}

		return [200,'操作成功'];
	}

	public function infoApi(){

		$id = input('post.id');

		$cate = $this->db('class')->where('id',$id)->where('is_delete',0)->find();
		if(!$cate){
			return [500,'信息错误'];
		}

		return [200,$cate];
	}

	public function updateApi(){

		$param = input('post.');

		if(empty($param['name'] ) || empty($param['id'])){
			return [500,'分类名不能为空或ID不能为空'];
		}

		$parentId = 0;
		if(!empty($param['parent_id'])){
			$parentId = $param['parent_id'];
		}

		if($parentId == $param['id']){
			return [501,'与上级分类冲突']	;
		}

		$cate = $this->db('class')->where('is_delete',0)->where('name',$param['name'])->where('id','<>',$param['id'])->find();

		if($cate){
			return [500,'该分类已经存在'];
		}

		$row = $this->db('class')->where('id',$param['id'])->update([
			'name' => $param['name'],
			'parent_id' => $parentId ,
		]);

		if($row < 0){
			return [500,'修改失败'];
		}

		return [200,'修改成功'];
	}

	public function htmlListApi(){
		$cate = new CategoryModel;

		$data = $cate->getHtmlList();

		return [200,$data];
	}

	public function deleteApi(){
		$id = input('post.id');

		$cate = $this->db('class')->where('id',$id)->where('is_delete',0)->find();
		if(!$cate){
			return [500,'数据错误或没有参数'];
		}

		$child = $this->db('class')->where('parent_id',$id)->where('is_delete',0)->find();
		if($child){
			return [500,'请删除子类'];
		}

		// $goods = $this->db('shop')->where('class_id',$id)->where('is_delete',0)->find();
		// if($goods){
		// 	return [500,'该分类下有商品请谨慎删除'];
		// }

		$row = $this->db('class')->where('id',$id)->update(['is_delete' =>1]);
		if($row < 1){
			return [501,'删除失败'];
		}

		return [200,'删除成功'];
	}
}