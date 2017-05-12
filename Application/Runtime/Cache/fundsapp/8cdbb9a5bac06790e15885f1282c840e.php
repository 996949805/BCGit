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
			<?php case "0": ?>集团<?php break;?>
			<?php default: ?>未定义<?php endswitch;?>|
层级:<?php switch($_SESSION['level']): case "1": ?>管理员<?php break;?>
		<?php case "2": ?>普通用户<?php break;?>
		<?php case "3": ?>浏览<?php break;?>
		<?php default: ?>未定义<?php endswitch;?>|
<a href="/funds/index.php/Login/dologout">注销</a> 
|<a href="/funds/index.php/Login/changepassword">修改密码</a>

<hr>
<a href="/funds/index.php/Admin/adduser">添加用户</a>
<table>
	<tr>
		<th>用户名</th>
		<th>姓名</th>
		<th>子公司</th>
		<th>层级</th>
		<th>操作</th>

	</tr>
<?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr>
		<td><?php echo ($vo["userid"]); ?></td>
		<td><?php echo ($vo["username"]); ?></td>
		<td>
			<?php switch($vo["subcompany"]): case "0": ?>南部<?php break;?>
				<?php case "1": ?>南部<?php break;?>
				<?php case "2": ?>北部<?php break;?>
				<?php case "3": ?>中部<?php break;?>
				<?php case "4": ?>玫瑰<?php break;?>
				<?php default: ?>未定义<?php endswitch;?>
			
		</td>
		<td>
			<?php switch($vo["level"]): case "1": ?>管理员<?php break;?>
				<?php case "2": ?>普通用户<?php break;?>
				<?php case "0": ?>浏览<?php break;?>
				<?php default: ?>未定义<?php endswitch;?>
		</td>
		<td><a href="/funds/index.php/Admin/modify/id/<?php echo ($vo["id"]); ?>">修改</a>|<a href="/funds/index.php/Admin/dodelete/id/<?php echo ($vo["id"]); ?>">删除</a></td>
	</tr><?php endforeach; endif; ?>
</table>
<hr>
<a href="/funds/index.php/Admin/addtitle">添加数据项</a>
<table>
	<tr>
		<th>ID</th>
		<th>分类</th>
		<th>项目名称</th>
		<th>分配</th>
		<th>显示顺序</th>
		<th>类型</th>
		<th>操作</th>
	</tr>
	<?php if(is_array($data2)): foreach($data2 as $key=>$vo): ?><tr>
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($vo["category"]); ?></td>
			<td><?php echo ($vo["titlename"]); ?></td>
			<td>
				<?php switch($vo["subcompany"]): case "0": ?>集团<?php break;?>
					<?php case "1": ?>南部<?php break;?>
					<?php case "2": ?>北部<?php break;?>
					<?php case "3": ?>中部<?php break;?>
					<?php case "4": ?>玫瑰<?php break;?>
					<?php default: ?>未定义<?php endswitch;?>
			</td>
			<td><?php echo ($vo["showorder"]); ?></td>
			<td><?php echo ($vo['isdata']==1?'':'<b>统计项</b>'); ?></td>
			<td><a href="/funds/index.php/Admin/modifytitle/id/<?php echo ($vo["id"]); ?>">修改</a>|<a href="/funds/index.php/Admin/toinvalid/id/<?php echo ($vo["id"]); ?>">失效</a></td>
		</tr><?php endforeach; endif; ?>
</table>