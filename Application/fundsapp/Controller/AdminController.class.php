<?php
namespace fundsapp\Controller;
use Think\Controller;
class AdminController extends Controller{

	public function _initialize(){
		if (I('session.userid')==null){
			$this->redirect('Index/index');
		}
	}	
	public function index(){
		$data=M('Fundsuser')->select();
		$data2=M('Fundstitle')->where('valid=1')->order('showorder asc,subcompany asc,titlename asc')->select();
        $this->data=$data;
        $this->data2=$data2;
        $this->display();
	}

	public function doadduser(){
		$d=D('Fundsuser');
		if ($d->create()){
			$result=$d->add();
			if ($result){
				$this->redirect("index");
			}else{
				$this->error("添加用户失败");
			}
		}else{
			$this->error("用户已存在，添加失败");
		}
	}

	public function modify(){
		$where=array(
			'id'=>I('get.id'),
		);
		$data=M('Fundsuser')->where($where)->select();
		$this->data=$data;
		$this->display('modifyuser');
	}

	public function domodify(){
		$d=D("Fundsuser");
		if ($d->create()){
			$result=$d->save();
			if ($result){
				$this->redirect("index");
			}else{
				$this->error("修改资料错误");	
			}	
		}else{
			$this->error("修改资料错误");
		}
	}

	public function dodelete(){
		$d=M("Fundsuser");
		$id=$_GET[id];
		$where=array(
			"id"=>$id,
		);
		$result=$d->where($where)->delete();
		if ($result){
			$this->redirect("index");
		}
		else{
			$this->error($d->getError());
		}
	}

	public function addtitle(){
		$this->display();
	}

	public function doaddtitle(){
		$d=D('Fundstitle');
		$d->create();
		if ($d->add()){
			$this->redirect("index");
		}

	}

	public function modifytitle(){
		$id=I('get.id');
		$data=M('Fundstitle')->find($id);
		$this->data=$data;
		$this->display();
	}

	public function domodifytitle(){
		$d=D("Fundstitle");
		if ($d->create()){
			$result=$d->save();
			if ($result){
				$this->redirect("index");
			}else{
				$this->error("修改资料错误");	
			}	
		}else{
			$this->error("修改资料错误");
		}
	}

	public function toinvalid(){
		$id=I("id");
		M("")->execute("update Fundstitle set valid=0 where id=$id");
		$this->redirect("index");
	}
}