<?php

require_once 'core.php';

$connection_queries = array(	"CREATE TABLE IF NOT EXISTS `teastats` (`setting` varchar(40) PRIMARY KEY NOT NULL, `setto` varchar(50) )"
								
								);

foreach($connection_queries as $query)
{
	mysqli_query($DB, $query);

}

echo 'Setup done!';

?>