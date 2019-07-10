<?php
require_once 'core.php';

function dashboard_gettext()
{
	global $DB;

}


function dashboard_getservers()
{
	global $DB;

	$result = mysqli_query($DB, 'SELECT * FROM `lsql_serverslist`');

	$numrows = mysqli_num_rows($result);

	if ($numrows > 0)
	{
		$servers = array();

	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	    	$curserver = array();
	    	$curserver['ip']= $row['ip'];
	    	$curserver['port']= $row['port'];

	    	$curserver['hostname'] = $row['hostname'];
	    	$curserver['map'] = $row['map'];
	    	$curserver['players'] = $row['players'];
	    	$curserver['maxplayers'] = $row['maxplayers'];
	    	$curserver['bots'] = $row['bots'];
	    	$curserver['appid'] = $row['appid'];
	    	
	    	$servers[] = $curserver;

	    }

	}

	mysqli_free_result($result);

	return $servers;
}

$servers = dashboard_getservers();


?>