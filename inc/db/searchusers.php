<?php
require_once 'core.php';

function searchusers_useramount()
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT COUNT(*) AS `total` FROM `lsql_users`');

	$numrows = mysqli_num_rows($result);

	$total = 0;

	if ($numrows > 0)
	{
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	        $total = $row['total'];

	    }
	}

	mysqli_free_result($result);
	
	return $total;
}

function searchusers_getusers()
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT * FROM `lsql_users` WHERE 1=1 ');

	$numrows = mysqli_num_rows($result);

	$users = array();

	if ($numrows > 0)
	{
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	        $users[] = $row;
	    }
	}

	mysqli_free_result($result);
	
	return $users;
}

function searchusers_search($limit, $offset, $group = 0, $steamid = 0, $justcount = 0)
{
	global $DB;

	if($steamid)
	{
		if(preg_match('/^STEAM_0:[01]:[0-9]+/', $steamid))
		{
			echo($steamid);

			$steamid = steamid_to64($steamid);
			echo("<br></br>");
			echo($steamid);
			echo("<br></br>");

		}

	}

	$countstr = 'SELECT COUNT(*) AS `total` FROM `lsql_users` WHERE 1=1';
	$fetchquery = 'SELECT * FROM `lsql_users` WHERE 1=1 ';

	if($group)
	{
		$countstr = $countstr . ' AND `group`=\'' . mysqli_real_escape_string($DB, $group) . '\' ';
		$fetchquery = $fetchquery . ' AND `group`=\'' . mysqli_real_escape_string($DB, $group) . '\' ';
	}

	if($steamid)
	{
		$countstr = $countstr . ' AND steamid = \'' . mysqli_real_escape_string($DB, strtolower($steamid)) . '\' ';
		$fetchquery = $fetchquery . ' AND steamid = \'' . mysqli_real_escape_string($DB, strtolower($steamid)) . '\' ';
	}


	$fetchquery = $fetchquery . 'ORDER BY steamid DESC  LIMIT ' . mysqli_real_escape_string($DB, $offset) . ',' . mysqli_real_escape_string($DB, $limit);

	$countquery = mysqli_query($DB, $countstr);

	if(!$countquery)
	{
		return array();
	}
	$countrows = mysqli_num_rows($countquery);
	$countrow = mysqli_fetch_assoc($countquery);
	$foundcount = $countrow['total'];


	mysqli_free_result($countquery);

	if($justcount)
	{
		return $foundcount;
	}

	$result = mysqli_query($DB, $fetchquery);
	

	if(!$result)
	{
		$amountfound = array();
		$amountfound['amount'] = $foundcount;
		$amountfound['skip'] = true;

		$bans['totalbans'] = $amountfound;

		return $bans;
	}

	$numrows = mysqli_num_rows($result);


	$users = array();

	if ($numrows > 0)
	{


	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {

	    	$users[] = $row;

	    }
	}

	mysqli_free_result($result);
	
	$amountfound = array();
	$amountfound['amount'] = $foundcount;
	$amountfound['skip'] = true;

	$users['totalusers'] = $amountfound;

	return $users;
}




?>