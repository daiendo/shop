<?php
namespace app\admin\controller;

/**
 * 
 */
class System extends Error
{
	public function uploadApi(){
		$file = request()->file('file');
		if(!$file){
			return [501,'文件不存在'];
		}else{
			$info = $file->move('uploads');
			if($info){
				$name = $info->getSavename();
				
				return [200,'http://localhost/api/public/uploads/'.$name];
			}else{
				return [501,$file->getError()];
			}
		}	
	}

	public function listApi(){
		$param = input('post.');

		$page = $param['page'] ?? 1;

		$where['is_delete'] = 0;

		if(!empty($param['title'])){
			$where['title'] = $param['title'];
		}
		

		$total = $this->db('system')->where($where)->count();
		if($total == 0){
			return [200,['total'=>0,'data'=>[]]];
		}

		$data = $this->db('system')->where($where)->page($page,10)->order('id desc')->select();

		foreach ($data as $key => $value) {
			$data[$key]['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
		}

		return [200,['total'=>$total,'data'=>$data]];
	}

	public function insertApi(){
		$param = input('post.');

		$error =$this->validate($param,'System.insert');

		if(true !== $error){
			return [501,$error];
		}

		
		$row = $this->db('system')->insert([
			'title' => $param['title'],
			'keywords' => $param['keywords'],
			'description' => $param['description'],
			'logo' => $param['logo'],
			'is_delete' => 0,
			'create_time' => time(),
			'status' => 0
		]);

		if($row < 1){
			return [501,'添加失败'];
		}
		return [200,true];
	}

	public function infoApi(){
		$id = input('post.id');

		$info = $this->db('system')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}

		return [200,$info];
	}

	public function deleteApi(){
		$id = input('post.id');

		$info = $this->db('system')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}

		if($info['status'] == 1){
			return [501,'该数据正在使用'];
		}
		$row = $this->db('system')->where('id',$id)->update([
			'is_delete'=> 1,
			'status' => 0,
		]);
		if($row < 1){
			return [501,'删除失败'];
		}
		return [200,'删除成功'];
	}

	public function updateApi(){
		$param = input('post.');

		$error =$this->validate($param,'System.update');

		if(true !== $error){
			return [501,$error];
		}

		
		$row = $this->db('system')->where('id',$param['id'])->update([
			'title' => $param['title'],
			'keywords' => $param['keywords'],
			'description' => $param['description'],
			'logo' => $param['logo'],
			'create_time' => time(),
		]);

		if($row < 0){
			return [501,'修改失败'];
		}
		return [200,true];
	}

	public function showApi(){
		$id = input('post.id');

		$info = $this->db('system')->where('id',$id)->where('is_delete',0)->find();
		if(!$info){
			return [501,'数据错误'];
		}

		$count = $this->db('system')->where('is_delete',0)->count();
		if($count == 1){
			return [501 ,'该数据正在使用'];
		}

		$this->db('system')->where('is_delete',0)->update(['status'=>0]);
		if($info['status'] == 0){
			$this->db('system')->where('id',$id)->update(['status'=>1]);
		}
		return [200,true];
	}

	public function getHeaderApi(){
		$data = $this->db('system')->where('is_delete',0)->where('status',1)->find();
		if(!$data){
			return [501,'数据错误'];
		}
		return [200,$data];
	}
}