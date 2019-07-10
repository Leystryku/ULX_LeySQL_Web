<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Servername - Group List</title>
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
			<h1>Groups</h1>
			<p>Overall: 
			<?php
			require_once 'inc/db/searchgroups.php';

			$totalgroups = searchgroups_groupamount();
			$groups = searchgroups_getgroups();

			echo($totalgroups);


			?></p>

			<select class="ui dropdown" id="search-select">
				

			  <option value="">Group</option>

			  <?php

			  foreach($groups as $group)
			  {

			  	$upperedname = ucfirst($group['name']);
			  	echo('<option value="' . $group['name'] . '">' . $upperedname . '</option>');
			  }
			  ?>
			</select>
		</div>


		<p></p>

		<div class="ui container">

			<?php

				$group = 0;
				if(isset($_GET['selectedgroup']))
				{



					$selectedgroup = $_GET['selectedgroup'];

					foreach($groups as $findgroup)
					{
						if($findgroup['name']==$selectedgroup)
						{
							$group = $findgroup;
						}
					}

				}

				if(!$group)
				{
					echo('<p> Group not found!');
				}else{
					echo('<p>Inherits from: ' . $group['inheritsfrom']  . '</p>');
					echo('<p>Can Target: ' . $group['cantarget']  . '</p>');
				}



			?>

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