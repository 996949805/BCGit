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