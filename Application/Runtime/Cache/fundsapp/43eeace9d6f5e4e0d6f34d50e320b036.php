<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>资金日报系统</title>
	<script type="text/javascript" src="/funds/Public/Js/jquery-1.7.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/funds/Public/Css/basic.css" />
	<link rel="stylesheet" type="text/css" href="/funds/Public/Css/<?php echo (CONTROLLER_NAME); ?>.css" />
</head>
<body>
<div style="font-size:15px;">
当前用户:<?php echo (session('username')); ?>|
所属区域:<?php switch($_SESSION['subcompany']): case "1": ?>南部<?php break;?>
			<?php case "2": ?>北部<?php break;?>
			<?php case "3": ?>中部<?php break;?>
			<?php case "4": ?>玫瑰<?php break;?>
			<?php case "0": ?>集团<?php break;?>
			<?php default: ?>未定义<?php endswitch;?>|
层级:<?php switch($_SESSION['level']): case "1": ?>管理员<?php break;?>
		<?php case "2": ?>普通用户<?php break;?>
		<?php case "3": ?>浏览<?php break;?>
		<?php default: ?>未定义<?php endswitch;?>|
<a href="/funds/index.php/Login/dologout">注销</a> 
|<a href="/funds/index.php/Login/changepassword">修改密码</a>

<script type="text/javascript">
	function tosubmit(){
			$('form[name="loginform"]').submit();
	}
</script>


<form action='/funds/index.php/Login/dochangepassword' method="post" name='loginform'>
<table>
	<tr><td colspan="2">修改密码</td></tr>
	<tr><td>用　户</td><td><input type="text" name="userid" value='<?php echo ($_SESSION[userid]); ?>' disabled="disabled" /></td></tr>
	<tr><td>密　码</td><td><input type="password" name="password" value='123456'/></td></tr>
	<tr><td>密　码</td><td><input type="password" name="repassword" value='123456'/></td></tr>
	<tr><td>验证码</td><td><input type="text" name="verifycode" value="11" /></td></tr>
	<tr><td></td><td><img src="<?php echo U('login/verifycode');?>" onclick="this.src=this.src+'?'+Math.random()"></td></tr>
	<tr><td colspan="2">
		<div class='submitbutton'>
			<button onclick="tosubmit()" class='submitbutton'>确定</button>
		</div>
	</td></tr>
</table>
</form>