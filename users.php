<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Servername - User List</title>
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
					<a class="item" href="./index.php">Dashboard</a>
					<a class="item" href="./bans.php">Ban List</a>
					<a class="active item" href="./users.php">User List</a>
					<a class="item" href="./admin.php">Admin</a>
			</div>
		</div>

		

		<div class="ui inverted very padded transparent segment container">
			<h1>Users</h1>
			<p>Overall: 
			<?php
			require_once 'inc/db/searchusers.php';
			require_once 'inc/db/searchgroups.php';

			$totalusers = searchusers_useramount();
			$groups = searchgroups_getgroups();
			$selected = array();

			if(isset($_GET['selectedgroup']))
			{
				$selectedgetarray = $_GET['selectedgroup'];
				foreach($selectedgetarray as $selection)
				{
					if(!searchgroups_search(999999999, 0, $selection, 0, 0, 1))
					{
						continue;
					}

					$selected[$selection] = $selection;
				}
			}

			echo($totalusers);
			
			?></p>

			<form class="ui form">
				<select name="selectedgroup[]" class="ui fluid search dropdown" multiple="" id="search-select">
				  <option value="">Group</option>

				  <?php

				  foreach($groups as $group)
				  {
				  	$upperedname = ucfirst($group['name']);
				  	echo('<option value="' . $group['name'] . '">' . $upperedname . '</option>');
				  }
				  ?>

				</select>

				<p></p>
				<button class="fluid ui button" type="submit">Submit</button>
			</form>
		</div>


		<p></p>

		<div class="ui inverted very padded transparent segment container">
			<?php
			foreach($selected as $groupname)
			{
				echo($groupname . ':' . '<p></p>');

				$users = searchusers_search(9999999, 0, $groupname);

				foreach($users as $user)
				{
					if(isset($user['skip']))
					{
						continue;
					}

					echo($user['steamid'] . '<p></p>');
				}

			}
			?>

		</div>
		<p></p>

		<div class="ui container">

			<p></p>
			
			<div class="ui inverted fluid pagination menu">
				<a class="active item">1</a>
				<a class="item">2</a>
				<a class="item">3</a>
				<a class="item">4</a>
				<a class="item">5</a>
				<a class="item">10</a>
				<div class="disabled item">
			    ...
				</div>
			</div>
			<p></p>

		</div>

		<p></p>

		<div class="ui container">
			<div class="ui inverted segment ">
				<font color="white">This website is a part of <a href="https://scriptfodder.com/scripts/view/3389">ULX LeySQL</a>. A Lua script by <a href="http://steamcommunity.com/profiles/76561198162962704">Leystryku</a> for managing Bans &amp; More. Copyright 2016/2017</font>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> <script src="semantic/semantic.min.js"></script>
		<script type="text/javascript" src="users.js"></script>
	</body>
</html>