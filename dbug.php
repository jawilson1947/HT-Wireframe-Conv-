<?php
 //dbug.php
print_r("enroll.php \n");
print_r("Running! \n");
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $data = array('id' => 10,
                'churchid' => 43161,
                'email' => 'jhill@oakwood.edu',
                	'placeid' => 'ChIJv-MoHhy5t4kR9W1es1Fr5Bc',
                	'churchadmin' => 0,
                	'churchname' => 'Dupont Park SDA Church',
                	'churchid' => 43161,
                	'password' => 'ellen',
                	'firstname' => 'John',
                	'lastname' => 'Hill',
                	'status' => '',
                	'action' => '',
                	'error' => '',
                	'sql' => ''); 
               $a= json_encode($data);
               $userStub = json_decode($a);
  try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	if($conn) {
    	        	print_r("Connection is Good \n");
                     if( empty($userStub->id)) {
                       print_r('Adding '.$userStub->firstname.' '.$userStub->lastname." \n");
//                       insertUser($conn,$userStub);
                          } else {
                       print_r('Updating '.$userStub->firstname.' '.$userStub->lastname." \n");
                       updateUser($conn,$userStub);
                          }
                  } else {
                  $userStub->status = "no";
                  $userStub->action = "User Enroll Attempt";
                  $userStub->error = "Unable to qualify userId on first look";
                  $userStub->sql = $sql;
                  print_r($userStub->error."\n");
                  echo json_encode($userStub);
               }                      
       } catch (PDOException $pe) {
        $status['status'] = "failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        print_r($pe-getMessage." \n");
//        echo json_encode($status);	
      } 
      
/*function insertUser($cn,$u) {
 	$firstname = $userStub->firstname;
 	$lastname = $userStub->lastname;
 	$churchname = str_replace(',','-',$userStub->churchname);
 	$email = $userStub->email;
 	$placeid = $userStub->placeid;
 	$userpsw = $userStub->password;
 	$churchadmin = $userStub->churchadmin;
 	$churchid = $userStub->churchid;
 	$email = $userStub->email;
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
 	               print_r($sql. "\");
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
                    $userStub->id = $r['uid'];
                    $userStub->status = 'ok';
                    $userStub->action = 'insert';
                    $userStub->error = '';
                    $userStub->sql = $sql;
                   print_r('Insert Successful - New ID is '.$userStub->id." \n");
                    echo json_encode($userStub);
                  } catch (PDOException $e) {
                    $userStub->status = 'no';
                    $userStub->action = 'insert';
                    $userStub->error = $e->getMessage;
                    $userStub->sql = $sql;
//                    echo json_encode($userStub);
                    print_r($e->getMessage);
                      }
 }*/
 
 function updateUser($cn,$u) {
 	$id = $u->id;
	$firstname = $u->firstname;
 	$lastname = $u->lastname;
 	$churchname = str_replace(',','-',$u->churchname);
 	$email = $u->email;
 	$placeid = $u->placeid;;
 	$userpsw = $u->password;
 	$churchadmin = $u->churchadmin;
 	$churchid = $u->churchid;
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
 	               print_r($sql);
 	               $sql->bindParam(':fn',$firstname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':ln',$lastname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
 	               $sql->bindParam(':em',$email,PDO::PARAM_STR,50);
 	               $sql->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
 	               $sql->bindParam(':pa',$userpsw,PDO::PARAM_STR,50);
 	               $sql->bindParam(':ca',$churchadmin,PDO::PARAM_BOOL);
 	               $sql->bindParam(':ci',$churchid,PDO::PARAM_INT,5);
 	               $sql->bindParam(':id',$id,PDO::PARAM_INT,5);
 	               if($sql->execute()) {
                     print_r("Update Successful \n");
 	               } else {
	               	print_r(json_encode($sql->errorInfo())." \n");
 	               }	               	
              } catch (PDOException $e) {
                    print_r($e->getMessage," \n");
              }
 
 }
?>
