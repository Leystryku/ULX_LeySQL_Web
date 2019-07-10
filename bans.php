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

		<!-- Navigation Bar -->
		<div class ="ui container">
			<p>&nbsp;</p>
			<div id="navigation" class="ui inverted menu">
					<a class="item" href="./index.php">Dashboard</a>
					<a class="active item" href="./bans.php">Ban List</a>
					<a class="item" href="./users.php">User List</a>
					<a class="item" href="./admin.php">Admin</a>
			</div>
		</div>

		


		<div class="ui inverted very padded transparent segment container">
			<h1>Bans</h1>
			
			<p>Overall: 
			<?php
			require_once 'inc/db/searchbans.php';

			$totalbans = searchbans_banamount();
			echo($totalbans);

			?></p>

			<p></p>
			<font color="white">
				<?php


				if(!isset($_GET['hide']) || intval($_GET['hide'] == 0))
				{
					$_GET['hide'] = "1";
					$url = http_build_query($_GET);
					$_GET["hide"] = "0";

					echo('<a href="?' . $url . '">Hide inactive</a>');
					$_GET['hide'] = "0";
				}else{

					$_GET['hide'] = "0";
					$url = http_build_query($_GET);
					$_GET["hide"] = "1";

					echo('<a href="?' . $url . '">Show inactive</a>');
					$_GET['hide'] = "1";
				}

				?>
			</font>
			<p></p>
			<button onclick="search_toggle();" class="ui inverted button">Search</button>



		</div>


		<p></p>

		<div class="ui container">

			<p></p>
			<!--<div style="position: fixed;z-index: 99;" class="search-menu" id="search">-->
			<div class="search-menu" id="search" style="position: fixed;z-index: 99;display:none">
				<div class="ui inverted segment">
					<form class="ui inverted form">

					<div class="fields">
						<div class="field">
							<label>Nick</label>
							<input class="input" type="text" name="nick" placeholder="Nick">
						</div>

						<div class="field">
							<label>SteamID</label>
							<input class="ui inverted input" type="text" name="sid" placeholder="SteamID">
						</div>

						<div class="field">
							<label>Reason</label>
							<input class="ui inverted input" type="text" name="rsn" placeholder="Reason">
						</div>

						<div class="field">
							<label>Duration ( in mins )</label>
							<input class="ui inverted input" type="text" name="dur" placeholder="Duration">
						</div>
					</div>

					<div class="ui inverted divider"></div>
					<div class="fields">
						<div class="field">
							<label>Banned by</label>
							<input class="ui inverted input" type="text" name="adm" placeholder="Admin">
						</div>
						<div class="field">
							<label>Banned by ID</label>
							<input class="ui inverted input" type="text" name="admid" placeholder="Admin ID">
						</div>
						<div class="field">
							<label>Unbanned by</label>
							<input class="ui inverted input" type="text" name="adm_ub" placeholder="Admin Unban">
						</div>
						<div class="field">
							<label>Unbanned by ID</label>
							<input class="ui inverted input" type="text" name="adm_ubid" placeholder="Admin Unban ID">
						</div>
					</div>

					<div class="ui inverted divider"></div>
					<button class="fluid ui button" type="submit">Search</button>
					</form>
				</div>
			</div>

			<p></p>


			<table class ="ui inverted transparent selectable table">
				<thead>
					<tr>
						<th>Date/Time</th>
						<th>Name</th>
						<th>Reason</th>
						<th>Admin</th>
						<th>Duration</th>
					</tr>
				</thead>
				<tbody>
					<?php

						$curpage = 1;
						if(isset($_GET["page"]))
						{
							$curpage = intval($_GET["page"]);
							if($curpage<1)
							{
								$curpage = 1;
							}
						}


						$requestedamount = $curpage*10;

						$displayinactive = true;

						if(isset($_GET['hide']) && intval($_GET['hide']) == 1)
						{
							$displayinactive = false;
						}

						$nick = 0;
						$steamid = 0;
						$reason = 0;
						$duration = 0;
						$admin = 0;
						$adminid = 0;
						$admin_ub = 0;
						$admin_ubid = 0;

						if(isset($_GET['nick']))
						{
							$nick = $_GET['nick'];
						}
						if(isset($_GET['sid']))
						{
							$steamid = $_GET['sid'];
						}
						if(isset($_GET['rsn']))
						{
							$reason = $_GET['rsn'];
						}
						if(isset($_GET['dur']))
						{
							$duration = $_GET['dur'];
						}
						if(isset($_GET['adm']))
						{
							$admin = $_GET['adm'];
						}
						if(isset($_GET['admid']))
						{
							$adminid = $_GET['admid'];
						}

						if(isset($_GET['adm_ub']))
						{
							$admin_ub = $_GET['adm_ub'];
						}

						if(isset($_GET['adm_ubid']))
						{
							$admin_ubid= $_GET['adm_ubid'];
						}

						$banamount = searchbans_search($displayinactive, ($curpage*10), ($curpage*10)-10, $nick, $steamid, $reason, $duration, $admin, $adminid, $admin_ub, $admin_ubid, true);


						if($curpage>0)
						{
							if(($requestedamount)>$banamount)
							{

								$curpage = $banamount/10;
								if($curpage>1)
								{
							
									$curpage = ceil($curpage);

								}else{
									$curpage = 1;
								}
							}

									
							$curpage = $curpage - 1;
							$bans = searchbans_search($displayinactive, 10, $curpage*10, $nick, $steamid, $reason, $duration, $admin, $adminid, $admin_ub, $admin_ubid);
							$banamount = $bans['totalbans']['amount'];
						}
						
						foreach($bans as $ban)
						{
							if(isset($ban['skip']))
								continue;

							$formattedbantime = gmdate("d-m-Y H:i", $ban['time']);
							if($ban['duration'] == 0)
							{
								$formattedlength = "Permanent";
							}else{

								$formattedlength = formattime($ban['duration']*60);
							}

							$banurl = 'bansview.php?banid=' . $ban['banid'];

							echo('<tr ');

							echo('onclick="window.open( \'' . $banurl . '\', \'mywindow\', \'menubar=1,resizable=1,width=500,height=300\')";>');

							echo('<td style="white-space: nowrap;max-width: 70px;overflow: hidden; text-overflow: ellipsis;">' . $formattedbantime . '</td>');
							echo('<td style="white-space: nowrap;max-width: 100px;overflow: hidden; text-overflow: ellipsis;">' . $ban['nick'] . '</td>');
							echo('<td style="white-space: nowrap;max-width: 200px;overflow: hidden; text-overflow: ellipsis;">' . $ban['reason'] . '</td>');
							echo('<td style="white-space: nowrap;max-width: 100px;overflow: hidden; text-overflow: ellipsis;">' . $ban['bannedby_nick'] . '</td>');
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

			<div class="ui container">
				<div class="ui inverted fluid pagination menu">

					<?php


						$_GET['page'] = $curpage;
						$url = http_build_query($_GET);


						$pagination_range = 3;

						$break_next = false;

						for($page=$curpage-$pagination_range;$page<=$curpage;$page++)
						{
							if(1>$page)
							{
								continue;
							}

							if($break_next)
							{
								break;
							}

							if((($page-1)*10)>$banamount)
							{
								$num = $banamount - ($page)*10;
								if($num==0||$num<0)
								{
									break;
								}else{
									$break_next = true;
								}
							}



							$_GET['page'] = $page;
							$url = http_build_query($_GET);
							echo('<a class="item" href="?' .$url . '">' . ($page) . '</a>');
						}


						echo('<a class="active item">' . ($curpage+1) . '</a>');

						$break_next = false;

						for($page=$curpage+2;$page<=$curpage+2+$pagination_range;$page++)
						{
							if($break_next)
							{
								break;
							}
							if((($page-1)*10)>$banamount)
							{
								$num = $banamount - ($page)*10;
								if($num==0||$num<0)
								{
									break;
								}else{
									$break_next = true;
								}
							}

							$_GET['page'] = $page;
							$url = http_build_query($_GET);
							echo('<a class="item" href="?' .$url . '">' . ($page) . '</a>');
						}


						if($banamount>(($curpage+5)*10))
						{
							echo('<div class="disabled item">...</div>');
						}

					?>

				</div>

			</div>
		</div>

		<p></p>

		<div class="ui container">
			<div class="ui inverted segment ">
				<font color="white">This website is a part of <a href="https://scriptfodder.com/scripts/view/3389">ULX LeySQL</a>. A Lua script by <a href="http://steamcommunity.com/profiles/76561198162962704">Leystryku</a> for managing Bans &amp; More. Copyright 2016/2017</font>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script> <script src="semantic/semantic.min.js"></script>
		<script type="text/javascript" src="bans.js"></script>
	</body>
</html>