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


||<b>当前月度：<?php echo ($yearmonth); ?></b>
<form method="post" action="index" style="display:inline;" id="selectym">
	选择时间区间<select name="yearmonth" onchange="toym();">
		<?php if(is_array($yearmonthlist)): foreach($yearmonthlist as $key=>$vo): ?><option value="<?php echo ($vo['yearmonth']); ?>" <?php if ($vo['yearmonth']==$yearmonth) echo "selected='selected'"; ?>><?php echo ($vo['yearmonth']); ?></option><?php endforeach; endif; ?>
	</select>
	<!-- <input type="submit" value="确定" /> -->
</form>
|<a href="/funds/index.php/index/exportexcel/yearmonth/<?php echo ($yearmonth); ?>">输出EXCEL</a>
<!-- |<a href='/funds/index.php/index/<?php echo ($_SESSION['mainshowall']==1?"chgmainnotshowall":"chgmainshowall"); ?>'><?php echo ($_SESSION['mainshowall']==1?"部分显示":"全部显示"); ?></a> -->
</div>
<script type="text/javascript">
function toym(){
	$("#selectym").submit();
}

function Convert(amtStr) {
       var a, renum = '';
       var j = 0;
       var a1 = '', a2 = '', a3 = '';
       var tes = /^-/;
       a = String(amtStr).replace(/,/g, "");
       a = a.replace(/[^-\.,0-9]/g, ""); //删除无效字符
       a = a.replace(/(^\s*)|(\s*$)/g, ""); //trim
       if (tes.test(a)) a1 = '-';
       else a1 = '';
       a = a.replace(/-/g, "");
       if (a != "0" && a.substr(0, 2) != "0.") a = a.replace(/^0*/g, "");
       j = a.indexOf('.'); if (j < 0) j = a.length; a2 = a.substr(0, j); a3 = a.substr(j); j = 0;
       for (i = a2.length; i > 3; i = i - 3) {
           renum = "," + a2.substr(i - 3, 3) + renum;
           j++;
       }
       renum = a1 + a2.substr(0, a2.length - j * 3) + renum + a3;
 
       return renum;
   }

$(function(){
    $('.dataview').text(function(){
        return  Convert($(this).text());
    
    });
});
</script>
<script type="text/javascript">
		$(document).ready(function(){
			var leng = $("#listtable tr").length;  
    		var newstr="";
    		var oldstr=$("#listtable tr").eq(0).find("td:first").html();;
    		var oldi=0;
    		var newstr="";
    		var ii=1;
			    for(var i=0; i<leng; i++){
			        newstr=$("#listtable tr").eq(i).find("td:first").html();  
			        if (oldstr!=newstr){
			        	for (var a=oldi+1;a<i;a++){
			        		$("#listtable tr").eq(a).find("td:first").remove();
			        	}
			        	$("#listtable tr").eq(oldi).find("td:first").attr("rowspan",i-oldi);
			        	oldstr=newstr;
			        	oldi=i;
			        	ii=0;

			        }else{
			        	ii++;
			        }
			      }
			      if (newstr==oldstr){
						for (var a=oldi+1;a<i;a++){
			        		$("#listtable tr").eq(a).find("td:first").remove();
			        	}
			        	$("#listtable tr").eq(oldi).find("td:first").attr("rowspan",i-oldi);
			      }
			      
		});
	</script>

<div class='maindiv'>
<table class='maintable' id='listtable'>
	<tr>
		<th><div class="div_category">类别</div></th>
		<th><div class="div_titlename">项目</div></th>
		<th><a href="/funds/index.php/index/modifydata/yearmonth/<?php echo ($yearmonth); ?>/day/99">同期</a></th>
		<th><a href="/funds/index.php/index/modifydata/yearmonth/<?php echo ($yearmonth); ?>/day/0">预测</a></th>
		<th>月累计</a></th>
		<?php $startday=$_SESSION['mainshowall']==1?31:(int)date("d"); ?>
		<?php $__FOR_START_18949__=$hideday;$__FOR_END_18949__=0;for($i=$__FOR_START_18949__;$i > $__FOR_END_18949__;$i+=-1){ if($_SESSION['level'] == 2 ): ?><th><a href="/funds/index.php/index/modifydata/yearmonth/<?php echo ($yearmonth); ?>/day/<?php echo ($i); ?>"><div><?php echo ($i); ?></a></th>
			<?php else: ?> 
					<th><?php echo ($i); ?></th><?php endif; } ?>
	</tr>
	<?php if(is_array($data)): foreach($data as $key=>$vo): ?><tr class="<?php echo ($vo['isdata']==1?'':'sum_month'); ?> data_grid">
		<td><div><?php echo ($vo['category']); ?></div></td>
		<td><div><?php echo ($vo['titlename']); echo ($vo['titleid']); ?></div></td>
		<td><div class='dataview'><?php echo ($vo['day99']==0?'':$vo['day99']); ?></div></td>
		<td><div class='dataview'><?php echo ($vo['day0']==0?'':$vo['day0']); ?></div></td>
		<td><div class='dataview month_sum'><?php echo ($vo['sum_month']==0?'':$vo['sum_month']); ?></div></td>

		<?php $__FOR_START_29052__=$startday;$__FOR_END_29052__=0;for($i=$__FOR_START_29052__;$i > $__FOR_END_29052__;$i+=-1){ $day='day'.$i; ?>
			<td><div class='dataview'><?php echo ($vo[$day]==0?'':$vo[$day]); ?></div></td><?php } ?>
	</tr><?php endforeach; endif; ?>
</table>

<table style="margin-top: 3px;">
		<tr>
			<th style="border-width:0px"><h3 style="float:left;padding:0;margin:0;">备注</h3></th>
		</tr>
		<tr>
		<td class="memo">
			<textarea name="" id="" cols="180" rows="10"><?php echo ($memo); ?></textarea>
		</td>
	</tr>

</table>
</div>