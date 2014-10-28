<?php

/**
 * 为防止sql注入，对字符串进行转义
 * @param  [type]  $str  [需要转义的字符]
 * @param  boolean $like [是否用于数据库 like 查询]
 * @return [type]        [返回经过转义的字符]
 */
function escape_str($str, $like = FALSE)
{	
	if (is_array($str))
	{
		foreach ($str as $key => $val)
		{
			$str[$key] = escape_str($val, $like);
		}
		return $str;
	}
	if (is_string($str))
	{
		$str = addslashes($str);
	}
	elseif (is_bool($str))
	{
		$str = ($str === FALSE) ? 0 : 1;
	}
	elseif (is_null($str))
	{
		$str = 'NULL';
	}	

	// escape LIKE condition wildcards
	if ($like === TRUE)
	{
		$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
	}

	return $str;
}

?>