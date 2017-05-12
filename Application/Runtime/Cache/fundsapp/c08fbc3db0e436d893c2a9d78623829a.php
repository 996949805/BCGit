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

修改
<form action="/funds/index.php/Admin/domodifytitle" method="post">
<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
<table>
	<tr>
		<td>分类</td><td><input type="text" name="category" value="<?php echo ($data['category']); ?>" /></td>
	</tr>	
	<tr>
		<td>项目名称</td><td><input type="text" name="titlename" value="<?php echo ($data['titlename']); ?>" /></td>
	</tr>
	<tr>
		<td>分配子公司</td>
		<td>
			<input type="radio" name="subcompany" value="0" <?php echo ($data['subcompany']==0?"checked":""); ?>/>全部
			<input type="radio" name="subcompany" value="1" <?php echo ($data['subcompany']==1?"checked":""); ?>/>南部
			<input type="radio" name="subcompany" value="2" <?php echo ($data['subcompany']==2?"checked":""); ?>/>北部
			<input type="radio" name="subcompany" value="3" <?php echo ($data['subcompany']==3?"checked":""); ?>/>中部
			<input type="radio" name="subcompany" value="3" <?php echo ($data['subcompany']==4?"checked":""); ?>/>玫瑰
		</td>
	</tr>
	<tr><td>显示顺序</td>
		<td><input type="text" name="showorder" value="<?php echo ($data['showorder']); ?>" /></td>
	</tr>	
	<tr><td>数据项</td>
		<td><input type="radio" name="isdata" value='1' <?php echo ($data['isdata']==1?'checked':''); ?> />数据项
		<input type="radio" name="isdata" value='0' <?php echo ($data['isdata']==0?'checked':''); ?> />统计项</td>
	</tr>
	<tr><td colspan="2"><input type="submit" value="修改" /></td></tr>
</table>	
</form>