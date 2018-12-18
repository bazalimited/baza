<?php
function is_on_demo_host()
{
	return $_SERVER['HTTP_HOST'] == 'demo.baza.rw' || $_SERVER['HTTP_HOST'] == 'demo.phppointofsalestaging.com';
}
?>