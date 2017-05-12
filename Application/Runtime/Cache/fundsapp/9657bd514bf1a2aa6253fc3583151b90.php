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
$(function(){
	$("input:text[class='daydata']").keyup(function(e) {  //keypress
		var keycode = (e.keyCode ? e.keyCode : e.which);
	        var inputs = $("body").find(":text[class='daydata']"); // 获取表单中的所有输入框  
	        var idx = inputs.index(this); // 获取当前焦点输入框所处的位置  
	    if (keycode == 13 || keycode==40) {
	        if (idx == inputs.length - 1) {// 判断是否是最后一个输入框  
	        	$("#endbutton").focus();
	             // if (confirm("最后一个输入框已经输入,是否提交?")); // 用户确认  
	        } else {  
	            inputs[idx + 1].focus(); // 设置焦点  
	            inputs[idx + 1].select(); // 选中文字  
	        }  
	        // return false;// 取消默认的提交行为  
	    }else if(keycode==38){
	            inputs[idx - 1].focus(); // 设置焦点  
	            inputs[idx - 1].select(); // 选中文字  
	    }
	})  
});
		$(function(){
		$(".daydata").bind('change',function(){
			var val=eval($(this).val());
			// alert(val);
			var id=$(this).attr('id');
			$.get("/funds/index.php/Index/ajaxdata/id/"+id+"/val/"+val+"/day/<?php echo ($day); ?>",function(result){
				// $("span[id="+result+"]").text(result);
				if (result>0){
					$("span[id="+result+"]").text("√");
				}
			});
		});
	});
</script>
<a href="/funds/index.php/Index/index">返回</a> | <b>操作日期：<?php echo ($yearmonth); ?>-<?php echo ($day==0?'预测':$day); ?></b> 
</div>
<hr>
<h3 style="color:red"><b>注意：可在录入框内使用计算式，系统会自动运算,如: 1+2+3-1；如录入数值已被记录，录入框旁会出现√；完成输入后，务必点击左下角的"保存并计算"按钮；每次计算操作都会对整个月的数据进行计算</b></h3>
<table class='datagrid'>
	<tr>
		<td>
			<table>
				<tr>
					<th><div class='div_category'>类别</div></th><th><div class='div_titlename'>项目</div></th><th>数值</th>
				</tr>
				<?php if(is_array($data1)): $i = 0; $__LIST__ = $data1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($vo["category"]); ?></td>
					<td><?php echo ($vo["titlename"]); echo ($vo["titleid"]); ?></td>
					<td><input class='daydata' type="text" value="<?php echo ($vo['day']==0?'':$vo['day']); ?>" id='<?php echo ($vo['id']); ?>' 
							<?php if (($day!=1 && $day!=0) && ($vo['titleid']==3 || $vo['titleid']==4) ) echo 'disabled="disabled"'; ?>  />
							<span id='<?php echo ($vo["id"]); ?>'>&nbsp;&nbsp;&nbsp;</span></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
		</td>
		<td>
			<table>
				<tr>
					<th><div class='div_category'>类别</div></th><th><div class='div_titlename'>项目</div></th><th>数值</th>
				</tr>
				<?php if(is_array($data2)): $i = 0; $__LIST__ = $data2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($vo['category']); ?></td>
					<td><?php echo ($vo['titlename']); echo ($vo['titleid']); ?></td>
					<td><input class='daydata' type="text" value="<?php echo ($vo['day']==0?'':$vo['day']); ?>" id='<?php echo ($vo['id']); ?>' />
						<span id='<?php echo ($vo['id']); ?>'>&nbsp;&nbsp;</span></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>			
		</td>
		<td>
			<table border='1' class='lasttable'>
				<tr>
					<th><div class='div_category'>类别</div></th><th><div class='div_titlename'>项目</div></th><th>数值</th>
				</tr>
				<?php if(is_array($data3)): $i = 0; $__LIST__ = $data3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><?php echo ($vo['category']); ?></td>
					<td><?php echo ($vo['titlename']); echo ($vo['titleid']); ?></td>
					<td><input class='daydata' type="text" value="<?php echo ($vo['day']==0?'':$vo['day']); ?>" id='<?php echo ($vo['id']); ?>' />
						<span id='<?php echo ($vo['id']); ?>'>&nbsp;&nbsp;</span></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</table>	
		</td>
	</tr>
	<form action='/funds/index.php/Index/domodifydata/yearmonth/<?php echo ($yearmonth); ?>/subcompany/<?php echo (session('subcompany')); ?>' method="get">
	<tr><td colspan="3"><span style="vertical-align:top;display:inline-block;font-weight:bold" >备注</span><textarea name="memo" cols="180" rows="10"><?php echo ($memo); ?></textarea></td></tr>
	<tr><td colspan="3"><input type="submit" value="保存并计算" /></form></td>
	</tr>
</table>