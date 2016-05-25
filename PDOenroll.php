<?php
 //enroll.php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $servername = '127.0.0.1';
  $status = array('status' => 'no',
                  'action' => '',
                  'error' => '',
                  'sql' => '');
 
   if(isset($_POST["info"])) {
  	$data = json_decode($_POST["info"]);
  	$userid = $data->id;
    if ($userid != 0 ) {
      updateMember($userid);
       } else {
       	try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Create connection
          $sql = "select userID from tblMember where lower(email) = '".strtolower($data->email)."'";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
// output data of each row
            $row = $result->fetch_assoc());
            $userid = $row["userID"];
            updateMember($userid);
          } else {;
          
          }
          $conn->close();prepare statement
          $stmt = $conn->prepare($sql);

    // execute the query
          $stmt->execute();

    // echo a message to say the UPDATE succeeded
    }
catch(PDOException $e)
    {
    	$status[0] = "no";
    	$status[1] = "select";
    	$status[2] = $e->getMessage();
    	$status[4] = $sql;
      echo json_encode($status);
    }


      	       
      	       $retval = mysql_query($sql);
      	       if(! retval) {
                 $sql = "insert into tblMember(email,firstname,lastname,userpsw,placeid,churchname,churchadmin,datecreated) values ('".$data->email."','".$data->firstname."','".$data->lastname."',password('".$data->password."'),'".$data->placeid."','".$data->churchname."',false,now())";
                 $retval = mysql_query($sql); 
                 if(! $retval ) { 
                   $info = array('status' => "No",
                     'action' => 'insert',
                     'error'=> mysql_error(),
                     'sql' => $sql);
                    } else {
                      $info = array('status' => "ok",
                      'action' => 'inserted',
                      'error' => '',
                      'sql' => $sql);
                           }
                echo json_encode($info);             
	          } else {
	       	updateMember($data);
	                 }
	        //! retval
        mysql_close($db_handle);
        }
    } else {
      echo "No Incoming Data Package!";	
           } 
  } else {
  	$badnews = array('status' => "Database NOT Found!".$db_handle,
  	'action' =>'enroll attempt',
        'sql' => '');
  	echo json_encode($badnews);
        mysql_close($db_handle);
         }//if dbfound
}//isset $_SERVER
function updateMember($data) {
	    $userid = $data->id;
 	    $sql = "update tblMember set firstname='".$data->firstname."',lastname='".$data->lastname."',userpsw=password('".$data->password."'),placeid='".$data->placeid."',churchname='".$data->churchname."', email='".$data->email."' where userID = ".$userid;
        $retval = mysql_query($sql);   
        if(! $retval ) { 
          $info = array('status' => "No",
                     'action' => '[updateMember].update',
                     'error' => mysql_error(),
                     'sql' => $sql);
          } else {
                   $info = array('status' => "ok",
                     'action' => '[UpdateMember].updated',
                     'error'=> '',
                     'sql' => $sql);
                  }
           echo json_encode($info);
 	
        }
?>
