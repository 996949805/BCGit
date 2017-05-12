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

<body>
<b>月度<?php echo ($yearmonth); ?></b>
<form method="post" action="index" style="display:inline;" id="selectym">
	选择时间区间<select name="yearmonth" onchange="toym();">
		<?php if(is_array($yearmonthlist)): foreach($yearmonthlist as $key=>$vo): ?><option value="<?php echo ($vo['yearmonth']); ?>" <?php if ($vo['yearmonth']==$yearmonth) echo "selected='selected'"; ?>><?php echo ($vo['yearmonth']); ?></option><?php endforeach; endif; ?>
	</select>
	<!-- <input type="submit" value="确定" /> -->
</form>
<a href="/funds/index.php/summary/exportexcel/yearmonth/<?php echo ($yearmonth); ?>">输出EXCEL</a>
<a href="/funds/index.php/summary/<?php echo ($_SESSION['mainshowall']==1?'chgmainnotshowall':'chgmainshowall'); ?>">
<?php echo ($_SESSION['mainshowall']==1?"部分显示":"全部显示"); ?>
</a> <span style="color:red;font-weight:bolder">单位：万元</span>
</div>
<script type="text/javascript">
$(function(){
    $("div.dataview").text(function(i,v){
        return v.replace(/(?!^)(?=(\d{3})+($|\.))/g,",");
    });
});

	function toym(){
		$("#selectym").submit();
	}

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
		if ($(this).text()=="0.00"){
			return "";
		}else{
        	return  Convert($(this).text());
		}
    
    });
});
	</script>

<div class='maindiv'>
<table class='maintable' id='listtable'>
	<tr>
		<th rowspan="2" ><div class="div_category">类型</div></th>
		<th rowspan="2"><div class="div_titlename">项目</div></th>
		<th colspan="5">本月(<?php echo ($yearmonth); ?>)累计</th>
		<th colspan="5"><?php echo ($lastday); ?></th>
		<th colspan="5">预测数</th>
		<th colspan="5">本年累计</th>
		<th colspan="5"> 去年同月实际</th>
	</tr>
	<tr>
		<td class='titlehead'><b>合计</b></td>
		<td class='titlehead'><b>南部</b></td>
		<td class='titlehead'><b>北部</b></td>
		<td class='titlehead'><b>中部</b></td>
		<td class='titlehead'><b>玫瑰</b></td>
		<td class='titlehead'><b>合计</b></td>
		<td class='titlehead'><b>南部</b></td>
		<td class='titlehead'><b>北部</b></td>
		<td class='titlehead'><b>中部</b></td>
		<td class='titlehead'><b>玫瑰</b></td>
		<td class='titlehead'><b>合计</b></td>
		<td class='titlehead'><b>南部</b></td>
		<td class='titlehead'><b>北部</b></td>
		<td class='titlehead'><b>中部</b></td>	
		<td class='titlehead'><b>玫瑰</b></td>	
		<td class='titlehead'><b>合计</b></td>
		<td class='titlehead'><b>南部</b></td>
		<td class='titlehead'><b>北部</b></td>
		<td class='titlehead'><b>中部</b></td>	
		<td class='titlehead'><b>玫瑰</b></td>	
		<td class='titlehead'><b>合计</b></td>
		<td class='titlehead'><b>南部</b></td>
		<td class='titlehead'><b>北部</b></td>
		<td class='titlehead'><b>中部</b></td>	
		<td class='titlehead'><b>玫瑰</b></td>	
	</tr>
	<?php if(is_array($datalist)): foreach($datalist as $key=>$vo): ?><tr>
				<td ><?php echo ($vo['category']); ?></div></td>
				<td><?php echo ($vo['titlename']); ?></td>
				<td class=' color1'><div class='dataview hj'><?php echo ($vo['sum_month']); ?></div></td>
				<td class=' color1'><div class='dataview'><?php echo ($vo['a1_sum_month']); ?></div></td>
				<td class=' color1'><div class='dataview'><?php echo ($vo['a2_sum_month']); ?></div></td>
				<td class=' color1'><div class='dataview'><?php echo ($vo['a3_sum_month']); ?></div></td>
				<td class=' color1'><div class='dataview'><?php echo ($vo['a4_sum_month']); ?></div></td>

				<td class='color2'><div class='dataview hj'><?php echo ($vo['sum_yestoday']); ?></div></td>
				<td class='color2'><div class='dataview'><?php echo ($vo['a1_yestoday']); ?></div></td>
				<td class='color2'><div class='dataview'><?php echo ($vo['a2_yestoday']); ?></div></td>
				<td class='color2'><div class='dataview'><?php echo ($vo['a3_yestoday']); ?></div></td>
				<td class='color2'><div class='dataview'><?php echo ($vo['a4_yestoday']); ?></div></td>

				<td class='color3'><div class='dataview hj'><?php echo ($vo['sum_day0']); ?></div></td>
				<td class='color3'><div class='dataview'><?php echo ($vo['a1_day0']); ?></div></td>
				<td class='color3'><div class='dataview'><?php echo ($vo['a2_day0']); ?></div></td>
				<td class='color3'><div class='dataview'><?php echo ($vo['a3_day0']); ?></div></td>
				<td class='color3'><div class='dataview'><?php echo ($vo['a4_day0']); ?></div></td>

				<td class='color5'><div class='dataview hj'><?php echo ($vo['sum_y']); ?></div></td>
				<td class='color5'><div class='dataview'><?php echo ($vo['y1']); ?></div></td>
				<td class='color5'><div class='dataview'><?php echo ($vo['y2']); ?></div></td>
				<td class='color5'><div class='dataview'><?php echo ($vo['y3']); ?></div></td>
				<td class='color5'><div class='dataview'><?php echo ($vo['y4']); ?></div></td>

				<td class='color4'><div class='dataview hj'><?php echo ($vo['sum_day99']); ?></div></td>
				<td class='color4'><div class='dataview'><?php echo ($vo['a1_day99']); ?></div></td>
				<td class='color4'><div class='dataview'><?php echo ($vo['a2_day99']); ?></div></td>
				<td class='color4'><div class='dataview'><?php echo ($vo['a3_day99']); ?></div></td>
				<td class='color4'><div class='dataview'><?php echo ($vo['a4_day99']); ?></div></td>

			</tr><?php endforeach; endif; ?>
</table>
</div>