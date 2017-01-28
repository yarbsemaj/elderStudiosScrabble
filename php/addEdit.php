<?php
require_once 'dDO.php';
$link=sqlcon();
$success=true;

  $data = file_get_contents("php://input");
    $postData = json_decode($data,true);
$operand=array("name","adress1","adress2","postCode","mode");
if(!missingOperand($operand,$postData)){
    $errorCode=1;
	$success=false;
}

if($success){
    $data=strip($link,$postData);
    $memberID= $data['memberID'];
	$mode=$data['mode'];
    $name=$data['name'];
    $add1=$data['adress1'];
    $add2=$data['adress2'];
    $pC=$data['postCode'];

    switch ($mode){
        case "edit":
            $query="UPDATE member
                    SET  name='$name', adress1='$add1', adress2='$add2', postCode='$pC'
                    WHERE memberID='$memberID'";
	        $query = mysqli_query($link,$query);
            break;
        case "add":
            $time = time();
            $query="INSERT INTO member
                    (name, adress1, adress2, postCode, joinDate)
                    VALUES ('$name','$add1','$add2','$pC','$time')";
	        $query = mysqli_query($link,$query) or die (mysqli_error($link));
            $memberID=mysqli_insert_id ($link);
            break;
    }
}

if($success){
	$reultrs = array("success"=>$success, "memberID" => $memberID );
}else
{
	$reultrs = array("success"=>$success, "error_code" => $errorCode, "data"=>$postData);
}
echo json_encode($reultrs);

?>