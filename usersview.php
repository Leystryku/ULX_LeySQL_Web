<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Servername - Bans</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="semantic/semantic.min.css"> <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">

		<link href="epicstyle.css" rel="stylesheet">

	</head>

	<body>


		



		<p></p>

		<div class="ui inverted transparent segment container">
			<p></p>
			<style type="text/css">
			p {
			   margin: 1px 0;
			}
			</style>
			<?php
			require_once 'inc/db/searchbans.php';
			if(!isset($_GET['banid']))
			{
				echo("no banid!");
				return;
			}

			$ban = searchbans_getbanbyid($_GET['banid']);

			if(!$ban)
			{
				echo("ban not found!");
				return;
			}

			$formattedbantime = gmdate("d-m-Y H:i", $ban['time']);
			$formattedlength = "";

			if($ban['duration'] == 0)
			{
				$formattedlength = "Permanent";
			}else{
				$formattedlength = formattime($ban['duration']*60);
			}

			$apikey = "C73D7CABCC27370B428B13DB12D8184B";

			$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$apikey."&steamids=" . $ban['steamid']);
			$content = json_decode($url, true);

			if($content['response'] && $content['response']['players'] && $content['response']['players'][0])
			{
				echo('<a target="_blank" href="http://steamcommunity.com/profiles/' . $ban['steamid']. '"><img src="' . $content['response']['players'][0]['avatarmedium'] . '" alt="Ass1"></img></a><p></p>');
			}

			echo('<p></p><p></p><p></p>Ban ID:' . $ban['banid'] . '<p></p>');
			echo('Nick: '. $ban['nick'] . '<p></p>');
			echo('Steam ID:' . $ban['steamid'] . '<p></p>');
			echo('Time: '. $formattedbantime . '<p></p>');
			echo('Duration: ' . $formattedlength. '<p></p>');

			if($ban['bannedby'])
			{
				echo('Banned by: <a target="_blank" href="http://steamcommunity.com/profiles/' . $ban['bannedby'] . '">'. $ban['bannedby_nick'] . '</a><p></p>');
			}else{
				echo('Banned by: '. $ban['bannedby_nick'] . '<p></p>');
			}

			if($ban['unbanned'])
			{
				if($ban['unbannedby'])
				{
					echo('Unbanned by: <a target="_blank" href="http://steamcommunity.com/profiles/' . $ban['unbannedby'] . '">'. $ban['unbannedby_nick'] . '</a><p></p>');
				}else{
					echo("Unbanned by: " . $ban['unbannedby_nick'] . '<p></p>');
				}
			}

			echo('Ban Reason: ' . $ban['reason'] . '<p></p>');

			$searchurl = 'bans.php?sid=' . $ban['steamid'];
			echo('<a target="_blank" href="' . $searchurl. '">All bans of this user </a><p></p>');

			?>

			

			<p></p>


		</div>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> <script src="semantic/semantic.min.js"></script>
		<script type="text/javascript" src="bans.js"></script>
	</body>
</html>