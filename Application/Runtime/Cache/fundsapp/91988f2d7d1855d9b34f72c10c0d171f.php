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
			<?php case "4": ?>玫瑰<?php break;?>
			<?php default: ?>未定义<?php endswitch;?>|
层级:<?php switch($_SESSION['level']): case "1": ?>管理员<?php break;?>
		<?php case "2": ?>用户<?php break;?>
		<?php default: ?>未定义<?php endswitch;?>|
<a href="/funds/index.php/Login/dologout">注销</a> 

<form action="doaddtitle" method="post">
<table>
	<tr><td>项目名称</td><td><input type="text" name="titlename" /></td></tr>
	<tr><td>分配子公司</td>
		<td>
			<input type="radio" name="subcompany" value="0" />全部
			<input type="radio" name="subcompany" value="1" />南部
			<input type="radio" name="subcompany" value="2" />北部
			<input type="radio" name="subcompany" value="3" />中部
			<input type="radio" name="subcompany" value="4" />玫瑰
		</td>
	</tr>
	<tr><td colspan="2"><input type="submit" value="添加" /></td></tr>
</table>	
</form>