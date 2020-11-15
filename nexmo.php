<?php


$apikey = $_POST["api_key"];
$apisecret = $_POST["api_secret"];

include ( "src/NexmoAccount.php" );
$account = new NexmoAccount($apikey, $apisecret);
$msg = "";
$code = 0;
if ( $account->balance() == null ){
     $code = 1;
     $msg = "<div class='alert alert-danger' role='alert'> Invalid informations </div>";
}
else
{
     $code = 2;
     $msg = "<div class='alert alert-success' role='alert'> Connected, your balance is " . $account->balance () . " EUR </div>";
}

echo json_encode(['code'=>$code, 'msg'=>$msg]);

?>  
      