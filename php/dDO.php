<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

date_default_timezone_set("Europe/London");
function sqlcon(){
	$host_name  = "db667042279.db.1and1.com";
    $database   = "db667042279";
    $user_name  = "dbo667042279";
	$password 	= "elderStudios";


    $connect = mysqli_connect($host_name, $user_name, $password, $database);
    
    if(mysqli_connect_errno())
    {
        echo '<p>Acsess Denied '.mysqli_connect_error().'</p>';
    }
    else
    {
        return  $connect;
    }
	
}

function missingOperand ($list,$data){
	foreach($list as $item){
	    if(null==$data[$item]){return false;}	
	}
	return true;
}

function strip($link,$data){
	foreach($data as $key=>$value) {
	    $clean[$key]=mysqli_real_escape_string($link,$value);}
	return $clean;
}
?>