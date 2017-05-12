<?php
namespace fundsapp\Model;
use Think\Model;
class FundstitleModel extends Model{
	protected $_auto=array(
		array('showorder',0),
	);

	protected $_validate = array(
		// array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])

		// 验证用户名是否已经存在
		array('titlename',		'',			'项目名称已经存在！',	1,'unique',1),
	);
}