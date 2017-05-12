<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>资金日报系统</title>
	<script type="text/javascript" src="/funds/Public/Js/jquery-1.7.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/funds/Public/Css/basic.css" />
	<link rel="stylesheet" type="text/css" href="/funds/Public/Css/<?php echo (CONTROLLER_NAME); ?>.css" />
</head>
<body>
当前用户:<?php echo (session('username')); ?>|
所属区域:<?php switch($_SESSION['subcompany']): case "1": ?>南部<?php break;?>
			<?php case "2": ?>北部<?php break;?>
			<?php case "3": ?>中部<?php break;?>
			<?php default: ?>未定义<?php endswitch;?>|
层级:<?php switch($_SESSION['level']): case "1": ?>管理员<?php break;?>
		<?php case "2": ?>用户<?php break;?>
		<?php default: ?>未定义<?php endswitch;?>|
<a href="/funds/index.php/Login/dologout">注销</a> 

修改
<form action="/funds/index.php/Admin/domodify" method="post">
<input type="hidden" name="id" value="<?php echo ($data[0]["id"]); ?>" />
<table>
	<tr>
		<td>用户名</td><td><input type="text" name="userid" value="<?php echo ($data[0]["userid"]); ?>" /></td>
	</tr>
	<tr>
		<td>姓名</td><td><input type="text" name="username" value="<?php echo ($data[0]["username"]); ?>" /></td>
	</tr>
	<tr>
		<td>子公司</td>
		<td>
			<input type="radio" name="subcompany" value="1" <?php echo ($data[0]['subcompany']==1?"checked":""); ?>/>南部
			<input type="radio" name="subcompany" value="2" <?php echo ($data[0]['subcompany']==2?"checked":""); ?>/>北部
			<input type="radio" name="subcompany" value="3" <?php echo ($data[0]['subcompany']==3?"checked":""); ?>/>中部
		</td>
	</tr>
	<tr><td>层级</td>
		<td>
			<input type="radio" name="level" value="2" <?php echo ($data[0]['level']==2?"checked":""); ?>/>普通
			<input type="radio" name="level" value="1" <?php echo ($data[0]['level']==1?"checked":""); ?>/>管理员
		</td>
	</tr>
	<tr><td colspan="2"><input type="submit" value="修改" /></td></tr>
</table>	
</form>