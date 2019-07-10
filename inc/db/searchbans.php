<?php
require_once 'core.php';

function searchbans_banamount()
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT COUNT(*) AS `total` FROM `lsql_bans`');

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

function searchbans_latest($count)
{
	global $DB;


	$result = mysqli_query($DB, 'SELECT * FROM `lsql_bans` ORDER BY time DESC  LIMIT ' . mysqli_real_escape_string($DB, $count));

	$numrows = mysqli_num_rows($result);

	$total = 0;

	$bans = array();

	if ($numrows > 0)
	{
	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	    	$row['unbanned'] = false;

	    	if(!is_null($row['unbannedby']))
	    	{
	    		$row['unbanned'] = true;

	    	}

	    	if(!$row['unbanned'] && $row['duration'] != 0 && ($row['time'] + ($row['duration']*60)) < time())
	    	{
	    		$row['unbanned'] = true;
	    		$row['unbannedby_nick'] = 'Time';

	    	}

	    	$bans[] = $row;

	    }
	}

	mysqli_free_result($result);
	
	return $bans;
}

if(!function_exists('bcmul'))
{
	function bcmul($_ro, $_lo, $_scale=0) {
	    return round($_ro*$_lo, $_scale);
	  }
	  
	function bcdiv($_ro, $_lo, $_scale=0) {
	    return round($_ro/$_lo, $_scale);
	  }

	function bcadd($num1, $num2)
	{
		return $num1 + $num2;
	}
}

function steamid_to64( $input )
{
	$CommunityID = preg_replace('/^STEAM_[0-5]:/', '', $input);
	$CommunityID = explode(':', $CommunityID);
	$CommunityID[1] = bcmul($CommunityID[1], '2');
	$CommunityID = $CommunityID[1] + $CommunityID[0];
	$CommunityID = bcadd($CommunityID, '76561197960265728');
	return $CommunityID;
}  

function searchbans_getbanbyid($banid)
{
	global $DB;

	$fetchquery = 'SELECT * FROM `lsql_bans` WHERE `banid`= ' . intval($banid);

	$result = mysqli_query($DB, $fetchquery);

	if(!$result)
	{
		return false;
	}

	$numrows = mysqli_num_rows($result);


	$bans = array();

	if ($numrows <= 0)
	{
		mysqli_free_result($result);
		return false;
	}

	$row = mysqli_fetch_assoc($result);
	$row['unbanned'] = false;
	
	if(!is_null($row['unbannedby']))
	{
		$row['unbanned'] = true;
	}


	if(!$row['unbanned'] && $row['duration'] != 0 && ($row['time'] + ($row['duration']*60)) < time())
	{
		$row['unbanned'] = true;
		$row['unbannedby_nick'] = 'Time';

	}

	$theban = $row;

	mysqli_free_result($result);


	return $theban;

}

function searchbans_search($displayinactive, $limit, $offset, $nick = 0, $steamid = 0, $reason = 0, $duration = 0, $admin = 0, $adminid = 0, $admin_ub = 0, $admin_ubid = 0, $justcount = 0)
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

	if($adminid)
	{
		if(preg_match('/^STEAM_0:[01]:[0-9]+/', $adminid))
		{
			echo($adminid);

			$adminid = steamid_to64($adminid);
			echo("<br></br>");
			echo($adminid);
			echo("<br></br>");

		}

	}

	if($duration)
	{
		$duration = strtolower($duration);
		if($duration == "perm" or $duration == "permanent")
		{
			$duration = 0;
		}

	}

	$countstr = 'SELECT COUNT(*) AS `total` FROM `lsql_bans` WHERE 1=1';
	$fetchquery = 'SELECT * FROM `lsql_bans` WHERE 1=1 ';

	if($nick)
	{
		$countstr = $countstr . ' AND nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($nick)) . '%\' ';
		$fetchquery = $fetchquery . ' AND nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($nick)) . '%\' ';
	}

	if($steamid)
	{
		$countstr = $countstr . ' AND steamid = \'' . mysqli_real_escape_string($DB, strtolower($steamid)) . '\' ';
		$fetchquery = $fetchquery . ' AND steamid = \'' . mysqli_real_escape_string($DB, strtolower($steamid)) . '\' ';
	}

	if($reason)
	{
		$countstr = $countstr . ' AND reason LIKE \'%' . mysqli_real_escape_string($DB, strtolower($reason)) . '%\' ';
		$fetchquery = $fetchquery . ' AND reason LIKE \'%' . mysqli_real_escape_string($DB, strtolower($reason)) . '%\' ';
	}

	if($duration)
	{
		$countstr = $countstr . ' AND duration = \'' . mysqli_real_escape_string($DB, strtolower($duration)) . '\' ';
		$fetchquery = $fetchquery . ' AND duration = \'' . mysqli_real_escape_string($DB, strtolower($duration)) . '\' ';
	}


	if($admin)
	{
		$countstr = $countstr . ' AND bannedby_nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($admin)) . '%\' ';
		$fetchquery = $fetchquery . ' AND bannedby_nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($admin)) . '%\' ';
	}

	if($adminid)
	{
		$countstr = $countstr . ' AND bannedby = \'' . mysqli_real_escape_string($DB, strtolower($adminid)) . '\' ';
		$fetchquery = $fetchquery . ' AND bannedby = \'' . mysqli_real_escape_string($DB, strtolower($adminid)) . '\' ';
	}

	if($admin_ub)
	{
		$countstr = $countstr . ' AND unbannedby_nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($admin_ub)) . '%\' ';
		$fetchquery = $fetchquery . ' AND unbannedby_nick LIKE \'%' . mysqli_real_escape_string($DB, strtolower($admin_ub)) . '%\' ';
	}

	if($admin_ubid)
	{
		$countstr = $countstr . ' AND unbannedby = \'' . mysqli_real_escape_string($DB, strtolower($admin_ubid)) . '\' ';
		$fetchquery = $fetchquery . ' AND unbannedby = \'' . mysqli_real_escape_string($DB, strtolower($admin_ubid)) . '\' ';
	}

	if(!$displayinactive)
	{
		$countstr = $countstr .' AND (( time + (duration*60) ) > UNIX_TIMESTAMP(NOW()) or duration=0) AND  unbannedby IS NULL';
		$fetchquery = $fetchquery . ' AND (( time + (duration*60) ) > UNIX_TIMESTAMP(NOW()) or duration=0) AND  unbannedby IS NULL ';
	}



	$fetchquery = $fetchquery . 'ORDER BY time DESC  LIMIT ' . mysqli_real_escape_string($DB, $offset) . ',' . mysqli_real_escape_string($DB, $limit);


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


	$bans = array();

	if ($numrows > 0)
	{


	    // output data of each row
	    while($row = mysqli_fetch_assoc($result))
	    {
	    	$row['unbanned'] = false;

	    	if(!is_null($row['unbannedby']))
	    	{
	    		$row['unbanned'] = true;

	    	}

	    	if(!$row['unbanned'] && $row['duration'] != 0 && ($row['time'] + ($row['duration']*60)) < time())
	    	{
	    		$row['unbanned'] = true;
	    		$row['unbannedby_nick'] = 'Time';

	    	}


	    	$bans[] = $row;

	    }
	}

	mysqli_free_result($result);
	
	$amountfound = array();
	$amountfound['amount'] = $foundcount;
	$amountfound['skip'] = true;

	$bans['totalbans'] = $amountfound;

	return $bans;
}




?>