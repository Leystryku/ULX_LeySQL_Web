<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Servername - Dashboard</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="semantic/semantic.min.css"> <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">

		<link href="epicstyle.css" rel="stylesheet">

	</head>

	<body>
		<!-- Navigation Bar -->
		<div class ="ui container">
			<p>&nbsp;</p>
			<div id="navigation" class="ui inverted menu">
					<a class="active item" href="./index.php">Dashboard</a>
					<a class="item" href="./bans.php">Ban List</a>
					<a class="item" href="./users.php">User List</a>
					<a class="item" href="./admin.php">Admin</a>
			</div>
		</div>

		

		<!-- Server Info -->

		<div class="ui inverted very padded transparent segment container">
			<h1>Dashboard</h1>

			<p>This is the dashboard. It's the main page of the leysql web.
				Feel free to  check out the other parts.</p>
		</div>

		<p></p>
		<div class ="ui container">
			<table class="ui inverted transparent selectable table">
				<thead>
					<tr>
						<th>Hostname</th>
						<th>Players</th>
						<th>Map</th>

					</tr>
				</thead>
				<tbody >

						<?php
								require_once 'inc/db/dashboarddata.php';
								$servers = dashboard_getservers();
								foreach($servers as $server)
								{
									echo('<tr><td><a href="steam://connect/');
									echo($server['ip']);
									echo(":");
									echo($server['port']);
									echo('">');
									echo($server['hostname']);
									echo('</a></td>');
									echo('<td>');
									echo($server['players']+$server['bots']);
									echo('/');
									echo($server['maxplayers']);
									echo('</td><td>');
									echo($server['map']);
									echo('</td></tr>');

								}
						?>

				</tbody>
			</table>
		</div>

		<p></p>

		<div class="ui container">
			
			<table class ="ui inverted transparent selectable table">
				<div class="ui inverted transparent compact segment ">
						<h2><font color="white">Latest Bans</font></h2>
				</div>

				<thead>
					<tr>
						<th>Date/Time</th>
						<th>Name</th>
						<th>Reason</th>
						<th>Duration</th>
					</tr>
				</thead>
				<tbody>

					<?php
						require_once 'inc/db/searchbans.php';
						$bans = searchbans_latest(10);
						foreach($bans as $ban)
						{
							$formattedbantime = gmdate("d-m-Y H:i", $ban['time']);
							if($ban['duration'] == 0)
							{
								$formattedlength = "Permanent";
							}else{

								$formattedlength = formattime($ban['duration']*60);


							}

							$searchurl = 'bans.php?sid=' . $ban['steamid'];

							
							echo('<tr ');

							echo('onclick="window.location.href = \'' . $searchurl . '\'";>');

							echo('<td style="white-space: nowrap;max-width: 70px;overflow: hidden; text-overflow: ellipsis;">' . $formattedbantime . '</td>');
							echo('<td style="white-space: nowrap;max-width: 100px;overflow: hidden; text-overflow: ellipsis;">' . $ban['nick'] . '</td>');
							echo('<td style="white-space: nowrap;max-width: 200px;overflow: hidden; text-overflow: ellipsis;">' . $ban['reason'] . '</td>');
							//echo('<td style="white-space: nowrap;max-width: 100px;overflow: hidden; text-overflow: ellipsis;">' . $ban['bannedby_nick'] . '</td>');
							echo('<td ');
							if($ban['unbanned'])
							{
								echo(' style="background-color:rgba(131,158,68,0.4)" ');
							}
							echo(">" . $formattedlength . "</td>");
							echo("</tr>");

						}
					?>


				</tbody>
			</table>

			<p></p>

			<div class="ui inverted segment ">
				<font color="white">This website is a part of <a href="https://scriptfodder.com/scripts/view/3389">ULX LeySQL</a>. A Lua script by <a href="http://steamcommunity.com/profiles/76561198162962704">Leystryku</a> for managing Bans &amp; More. Copyright 2016/2017</font>
			</div>

		</div>



	</body>
</html>