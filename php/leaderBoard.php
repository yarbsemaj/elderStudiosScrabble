<?php
require_once 'dDO.php';
$link=sqlcon();
		
	$query="SELECT m.name , m.memberID , COUNT(*) AS gamesPlayed , ROUND(AVG (gp.score),0) AS avgScore FROM gamePlayers AS gp
            JOIN member AS m ON gp.memberID = m.memberID 
            GROUP BY memberID HAVING gamesPlayed >= 10 ORDER BY avgScore DESC LIMIT 0, 9";
	$query = mysqli_query($link,$query);
	
	$results['members'] = array();
	while($line = mysqli_fetch_array($query))
    	array_push($results ["members"], $line);

    echo json_encode($results);
?>