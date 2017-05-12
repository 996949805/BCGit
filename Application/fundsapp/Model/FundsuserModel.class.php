<?php
namespace fundsapp\Model;
use Think\Model;
class FundsuserModel extends Model{
	protected $_auto=array(
		array('password','123'),
	);

	protected $_validate = array(
		// array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])

		// 验证用户名是否已经存在
		array('userid',		'require',	'编号不能为空！'),
		array('userid',		'',			'帐号名称已经存在！',	1,'unique',1),
		array('username',	'require',	'姓名不能为空！'),
		array('subcompany',	'require',	'选择所属子公司！'),
		array('level',		'require',	'选择用户层级！'),
	);
}