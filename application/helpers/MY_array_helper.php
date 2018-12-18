<?php
if(!function_exists("array_replace"))
{
	function array_replace()
	{
      $args = func_get_args();
      $ret = array_shift($args);
     foreach($args as $arg)
	  {
         foreach($arg as $k=>$v)
			{
             $ret[(string)$k] = $v;
         }
      }
      return $ret;
  }
}

function sort_assoc_array_by_label($a,$b)
{
   if ($a['label'] == $b['label']) {
          return 0;
      }
      return ($a['label'] < $b['label']) ? -1 : 1;
}

function sort_assoc_array_by_name($a,$b)
{
   if ($a['name'] == $b['name']) {
          return 0;
      }
      return ($a['name'] < $b['name']) ? -1 : 1;
}

?>
