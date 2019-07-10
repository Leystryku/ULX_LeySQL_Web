<?php
require_once 'config.php';


$DB = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT);

if (mysqli_connect_errno())
{
	$DB = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, "", DB_PORT);
	if(mysqli_connect_errno())
	{
		die('Connection failed!<p></p> ' . mysqli_error());
	}else{
		$query = "CREATE DATABASE IF NOT EXISTS " . mysqli_real_escape_string($DB, DB_DATABASE);
		mysqli_query($DB, $query);
		if(!mysqli_select_db($DB, DB_DATABASE))
		{
			die('Error while selecting DB!<p></p> ' . mysqli_error());
		}
	}

}


function formattime($time)
{
	
	$days = floor($time / 86400);
	$hours = floor($time / 3600) % 24;
	$minutes = floor(($time/ 60) % 60);
	$seconds = $time % 60;

	$formattedtime = "";

	if($days>0)
	{
		$formattedtime = $formattedtime . $days . "d";
	}

	if($hours>0)
	{
		$formattedtime = $formattedtime . $hours . "h";
	}

	if($minutes>0)
	{
		$formattedtime = $formattedtime . $minutes . "m";
	}

	if($seconds>0)
	{
		$formattedtime = $formattedtime . $seconds . "s";
	}

	return $formattedtime;
}



?>