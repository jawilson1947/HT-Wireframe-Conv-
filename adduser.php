<?php
 //adduser.php
 include 'ChromePhp.php';
ChromePhp::warn('adduser.php');
ChromePhp::warn('Running!');
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $userStub = json_decode($_POST["info"]);
  $userid = (int)$userStub->id;
  $churchid = (int)$userStub->churchid;
  $churchname = str_replace(',','-',$userStub->churchname);

  try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	if($conn) {
    	        ChromePhp::warn('Connection is Good');
 	        $sql = $conn->prepare("update ht.tblMember set ChurchID = :ci ,churchname = :cn where UserID = :id");
 	        ChromePhp::warn($sql);
                $sql->bindParam(':ci',$churchid,PDO::PARAM_INT);
                $sql->bindParam(':cn',$churchname, PDO::PARAM_STR);
 	            $sql->bindParam(':id',$userid,PDO::PARAM_INT);
 	        if($sql->execute()) {
                     ChromePhp::warn('Update Successful');
                     $userStub->status="ok";
                     $userStub->action="update";
                     $userStub->error="";
                     $userStub->sql=$sql;
                   echo json_encode($userStub);
 	               } else {
                   ChromePhp::warn('Update Unsuccessful');
                    $userStub->status = 'no';
                    $userStub->action = 'update';
                    $userStub->error = "!!!***!!!";
                    $userStub->sql = $sql;
                    echo json_encode($userStub);
  	               }
                 }
       } catch (PDOException $e) {
                   $userStub->status = 'no';
                    $userStub->action = 'update';
                    $userStub->error = $e->getMessage;
                    $userStub->sql = $sql;
                    echo json_encode($userStub);
                    ChromePhp::warn($e->getMessage);
      }
?>
