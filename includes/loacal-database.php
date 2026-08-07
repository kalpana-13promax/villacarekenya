<?php

class db
{
	function __construct()
	{
		$this->mysqli = mysqli_connect(HOSTS,USERNAME,PASSWORD,DATABASE);
	}
}
?>