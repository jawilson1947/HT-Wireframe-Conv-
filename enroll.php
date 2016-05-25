<?php
 //enroll.php
 include 'ChromePhp.php';
ChromePhp::warn('enroll.php');
ChromePhp::log($_SERVER);
ChromePhp::warn('Running!');
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $userStub = json_decode($_POST["info"]);
  $userid = (int)$userStub->id;
  $churchid = (int)$userStub->churchid;
  $email = $userStub->email;
  $status = array('status' => '',
                  'action' => '',
                  'error' => '',
                  'sql' => '');
  try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	if($conn) {
    	        	ChromePhp::warn('Connection is Good');
                     if( empty($userStub->id)) {
                       ChromePhp::warn('Adding '.$userStub->firstname.' '.$userStub->lastname);
                       insertUser($conn,$userStub);
                          } else {
                       ChromePhp::warn('Updating '.$userStub->firstname.' '.$userStub->lastname);
                       updateUser($conn,$userStub);
                          }
                  } else {
                  $userStub->status = "no";
                  $userStub->action = "User Enroll Attempt";
                  $userStub->error = "Unable to qualify userId on first look";
                  $userStub->sql = $sql;
                  ChromePhp::warn($userStub->error);
                  echo json_encode($userStub);
               }                      
       } catch (PDOException $pe) {
        $status['status'] = "failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
      } 
      
function insertUser($cn,$us) {
 	$firstname = $us->firstname;
 	$lastname = $us->lastname;
 	$churchname = str_replace(',','-',$us->churchname);
 	$email = $us->email;
 	$placeid = $us->placeid;
 	$userpsw = $us->password;
 	$churchadmin = $us->churchadmin;
 	$churchid = $us->churchid;
 	$email = $us->email;
 	if(empty($churchadmin)) { 
 	   $churchadmin = false; 
 	  } else { 
 	   $churchadmin = true;
 	  }
 	  if(empty($churchid)) {
 	  	$churchid = 0;
 	  }
               try {
 	               $sql ="CALL saveuser(:fn,:ln,:cn,:pl,:ca,:pa,:em,:ci,@uid)";
 	               ChromePhp::warn($sql);
 	               $stmt = $cn->prepare($sql);
 	               $stmt->bindParam(':fn',$firstname,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':ln',$lastname,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':ca',$churchadmin,PDO::PARAM_BOOL);
 	               $stmt->bindParam(':pa',$userpsw,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':em',$email,PDO::PARAM_STR,80);
 	               $stmt->bindParam(':ci',$churchid,PDO::PARAM_INT);
                    $stmt->execute();
                    $stmt->closeCursor();
                    $r = $cn->query("select @uid as uid")->fetch(PDO::FETCH_ASSOC);
                    $us->id = $r['uid'];
                    $us->status = 'ok';
                    $us->action = 'insert';
                    $us->error = '';
                    $us->sql = $sql;
                   ChromePhp::warn('Insert Successful - New ID is '.$us->id);
                    echo json_encode($us);
                  } catch (PDOException $e) {
                    $us->status = 'no';
                    $us->action = 'insert';
                    $us->error = $e->getMessage;
                    $us->sql = $sql;
                    echo json_encode($us);
                    ChromePhp::warn($e->getMessage);
                      }
 }
 function updateUser($cn,$us) {
 	$id = $us->id;
	$firstname = $us->firstname;
 	$lastname = $us->lastname;
 	$churchname = str_replace(',','-',$us->churchname);
 	$email = $us->email;
 	$placeid = $us->placeid;;
 	$userpsw = $us->password;
 	$churchadmin = $us-churchadmin;
 	$churchid = $us->churchid;
 	if(empty($churchadmin)) { 
 	   $churchadmin = false; 
 	  } else { 
 	   $churchadmin = true;
 	  }
 	  if(empty($churchid)) {
 	    $churchid = 0;	
 	  }
  	          try {
 	              	$sql = $cn->prepare("update ht.tblMember set firstname=:fn,lastname=:ln,churchname=:cn,email=:em,placeid=:pl,userpsw=:pa,churchadmin=:ca,ChurchId=:ci where UserID=:id");
 	               ChromePhp::warn($sql);
 	               $sql->bindParam(':fn',$firstname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':ln',$lastname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':em',$email,PDO::PARAM_STR,50);
 	               $sql->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
 	               $sql->bindParam(':pa',$userpsw,PDO::PARAM_STR,50);
 	               $sql->bindParam(':ca',$churchadmin,PDO::PARAM_BOOL);
 	               $sql->bindParam(':ci',$churchid,PDO::PARAM_INT);
 	               $sql->bindParam(':id',$id,PDO::PARAM_INT);
 	               if($sql->execute()) {
                 ChromePhp::warn('Update Successful');
                     $us->status="ok";
                     $us->action="update";
                     $us->error="";
                     $us->sql=$sql;
                   echo json_encode($us);
 	               } else {
                   ChromePhp::warn('Update Unsuccessful');
                    $us->status = 'no';
                    $us->action = 'update';
                    $us->error = "!!!***!!!";
                    $us->sql = $sql;
                    echo json_encode($us);
  	               }
              } catch (PDOException $e) {
                   $us->status = 'no';
                    $us->action = 'update';
                    $us->error = $e->getMessage;
                    $us->sql = $sql;
                    echo json_encode($us);
                    ChromePhp::warn($e->getMessage);
              }
 
 }
?>
