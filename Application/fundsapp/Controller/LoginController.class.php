<?php
namespace fundsapp\Controller;
use Think\Controller;
class LoginController extends Controller{
	public function verifycode(){
    	$config =    array(
    	'fontSize'    =>    15,    // 验证码字体大小
    	'length'      =>    4,     // 验证码位数
    	'useNoise'    =>    false, // 关闭验证码杂点
    	'codeSet' 	  =>	'1234567890', 
    	'imageH'	  =>	30,
    	'imageW'      =>	0,
		);
    	$Verify = new \Think\Verify($config);
		$Verify->entry();
    }


    public function dologin(){
    	// $verifycode=I('post.verifycode');
    	// $verify = new \Think\Verify();
   	 // 	$result=$verify->check($verifycode,'');
   	 // 	if (!$result){
   	 // 		$this->error("验证码错误！");
   	 // 	}

    	$userid=I('post.userid');
    	$password=I('post.password');
    	$where=array(
    		'userid'=>$userid,
    		'password'=>$password,
    	);
    	$data=M('Fundsuser')->where($where)->select();
    	if ($data){
    		$_SESSION['userid']=$data[0]['userid'];
    		$_SESSION['username']=$data[0]['username'];
    		$_SESSION['level']=$data[0]['level'];
    		$_SESSION['subcompany']=$data[0]['subcompany'];
            //mainform是否显示全部日期
            $_SESSION['mainshowall']=0;

			if ($_SESSION['subcompany']==0 && $_SESSION['level']==3){
				$this->redirect('Summary/index');
			}

    		if ($data[0]['level']==1){
    			$this->redirect('Admin/index');
    		}else{
    			$this->redirect('Index/index');	
    		}
    		
    	}else{
    		$this->error("用户名或密码错误！");
    	}
    	
    }

    public function dologout(){
		$_SESSION=array();
			if(isset($_COOKIE[session_name()])){
				setcookie(session_name(),'',time()-1,'/');
			}
		session_destroy();
		$this->redirect('Index/index');
    }

    public function changepassword(){
        $this->display();
    }

    public function dochangepassword(){
        $p1=$_POST['password'];
        $p2=$_POST['repassword'];
        if ($p1!=$p2){
            $this->error("密码确认错误");
        }else if ($p1==null || $p1==''){
            $this->error("密码不能为空");
        }else{
            $m=M('Fundsuser');
            $where=array("userid"=>$_SESSION['userid'],);
            $data=array("password"=>$_POST['password']);
            $m->data($data)->where($where)->save();

            // echo $m->getLastSql();
            $L=A("Login");
            $L->dologout();
        }
    }
}