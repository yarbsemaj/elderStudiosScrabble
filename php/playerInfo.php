<?php
require_once 'dDO.php';
$link=sqlcon();
$success=true;

  $data = file_get_contents("php://input");
    $postData = json_decode($data,true);
$operand=array("memberID");
if(!missingOperand($operand,$postData)){
    $errorCode=1;
	$success=false;
}

if($success){
    $data=strip($link,$postData);
	$memberID=$data['memberID'];
    $query="SELECT *  FROM member
            WHERE memberID = '$memberID'";
	$query = mysqli_query($link,$query);
    if(mysqli_num_rows($query)==0){
        $errorCode=2;
	    $success=false;
    }
}
if($success){
	$memberData=mysqli_fetch_array($query);
	
	$query="SELECT COUNT(*) AS wins FROM (
                SELECT m.memberID, gp.gameID, gp.score FROM member AS m 
                JOIN gamePlayers AS gp ON gp.memberID = m.memberID
                WHERE m.memberID='$memberID'  
            ) AS you
            JOIN gamePlayers AS op ON op.memberID != you.memberID AND op.gameID = you.gameID
            WHERE you.score > op.score";
	$query = mysqli_query($link,$query);
	$results=mysqli_fetch_array($query);
    $wins = $results['wins'];

    $query="SELECT COUNT(*) AS loss FROM (
                SELECT m.memberID, gp.gameID, gp.score FROM member AS m 
                JOIN gamePlayers AS gp ON gp.memberID = m.memberID
                WHERE m.memberID='$memberID'  
            ) AS you
            JOIN gamePlayers AS op ON op.memberID != you.memberID AND op.gameID = you.gameID
            WHERE you.score < op.score";
	$query = mysqli_query($link,$query);
	$results=mysqli_fetch_array($query);
    $loss = $results['loss'];

    $query="SELECT AVG(score) as avgScore FROM member AS m 
            JOIN gamePlayers AS gp ON gp.memberID = m.memberID
            WHERE m.memberID='$memberID'  
            GROUP BY m.memberID";
	$query = mysqli_query($link,$query);
    $results=mysqli_fetch_array($query);
    $avg = $results['avgScore'];
	

     $query="SELECT bg.gameID, bg.score AS playerScore, op.name AS opName,gp.score AS opScore, g.location, g.time FROM (
                SELECT MAX(gp.score) as score, gp.gameID, m.memberID FROM member AS m 
 	            JOIN gamePlayers AS gp ON gp.memberID = m.memberID
                WHERE m.memberID='$memberID'  
                GROUP BY m.memberID
            ) AS bg
            JOIN gamePlayers AS gp ON bg.gameID = gp.gameID AND bg.memberID != gp.memberID
            JOIN member AS op ON gp.memberID = op.memberID
            JOIN game AS g on g.gameID = gp.gameID";
	$query = mysqli_query($link,$query);
    $bestGame=mysqli_fetch_array($query);

}

if($success){
	$reultrs = array("success"=>$success,"memberData"=>$memberData, "wins"=>$wins, "loss"=>$loss, "avg"=>$avg, "bestGame"=> $bestGame);
}else
{
	$reultrs = array("success"=>$success, "error_code" => $errorCode);
}
echo json_encode($reultrs);

?>