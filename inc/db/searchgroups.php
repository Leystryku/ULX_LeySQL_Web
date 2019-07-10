<?php
require_once 'core.php';

function searchgroups_groupamount()
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT COUNT(*) AS `total` FROM `lsql_groups`');

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

function searchgroups_getgroups()
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT * FROM `lsql_groups` WHERE 1=1 ');

	$numrows = mysqli_num_rows($result);

	$groups = array();

	if ($numrows > 0)
	{
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	        $groups[] = $row;
	    }
	}

	mysqli_free_result($result);
	
	return $groups;
}

function searchgroups_search( $limit, $offset, $name=0, $inheritsfrom=0, $cantarget=0, $justcount = 0)
{
	global $DB;


	$countstr = 'SELECT COUNT(*) AS `total` FROM `lsql_groups` WHERE 1=1';
	$fetchquery = 'SELECT * FROM `lsql_groups` WHERE 1=1 ';

	if($name)
	{
		$countstr = $countstr . ' AND name LIKE \'%' . mysqli_real_escape_string($DB, strtolower($name)) . '%\' ';
		$fetchquery = $fetchquery . ' AND name LIKE \'%' . mysqli_real_escape_string($DB, strtolower($name)) . '%\' ';
	}

	if($inheritsfrom)
	{
		$countstr = $countstr . ' AND inheritsfrom LIKE \'%' . mysqli_real_escape_string($DB, strtolower($inheritsfrom)) . '%\' ';
		$fetchquery = $fetchquery . ' AND inheritsfrom LIKE \'%' . mysqli_real_escape_string($DB, strtolower($inheritsfrom)) . '%\' ';
	}

	if($cantarget)
	{
		$countstr = $countstr . ' AND cantarget LIKE \'%' . mysqli_real_escape_string($DB, strtolower($cantarget)) . '%\' ';
		$fetchquery = $fetchquery . ' AND cantarget LIKE \'%' . mysqli_real_escape_string($DB, strtolower($cantarget)) . '%\' ';
	}


	$fetchquery = $fetchquery . 'ORDER BY name DESC  LIMIT ' . mysqli_real_escape_string($DB, $offset) . ',' . mysqli_real_escape_string($DB, $limit);


	$countquery = mysqli_query($DB, $countstr);

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


	$groups = array();

	if ($numrows > 0)
	{


	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	    	$groups[] = $row;

	    }
	}

	mysqli_free_result($result);
	
	$amountfound = array();
	$amountfound['amount'] = $foundcount;
	$amountfound['skip'] = true;

	$groups['totalgroups'] = $amountfound;

	return $groups;
}




?>