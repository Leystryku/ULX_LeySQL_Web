<?php

	if (headers_sent() === true)
	{
		return;
	}	

	$images = array();

	foreach(glob("img/bg/*") as $filename)
	{
		$file = pathinfo($filename);
		$images[] = $filename;

	}

	header('Location: ' . $images[0], true, 303);
	//header('Location: ' . $images[array_rand($images)], true, 303);
	//header('Location: http://wallpapercave.com/wp/1Y7lSD1.jpg', true, 303);

?>
