<?php
include 'ChromePhp.php';
ChromePhp::warn('Login.php');
function customError($errno, $errstr) {
  $info = array('status' => 'system error',
      	              'action' => 'process',
      	              'error' => $errno. '  - '. $errstr,
                 	              'sql' => '');
                 	  ChromePhp::warn('PHP System error: '.$info['error']);
               echo json_encode($info);  
               die();	              
                   }
//set_error_handler("customError");
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  ChromePhp::warn('Running');
  if($db_found) {
  	if(isset($_POST["info"])) {
      $data = json_decode($_POST["info"]);
      $email = $data->email;
      $sql = "select * from tblMember where lower(email) = '".strtolower($email)."'";
      $result = mysql_query($sql);
      ChromePhp::warn('SQL is '.$sql.'\n');
      if($dbfield = mysql_fetch_assoc($result)){
        $info = array('status' => "ok",
                  'firstname' => $dbfield['firstname'],
                  'lastname' => $dbfield['lastname'],
                  'churchname' => $dbfield['churchname'],
                  'placeid' => $dbfield['placeid'],
                  'email' => $dbfield['email'],
                  'password' => $dbfield['userpsw'],
                  'churchadmin' => $dbfield['churchadmin'],
                  'churchid' => $dbfield['churchID'],
                  'id' => $dbfield['userID'],
                  'action' => 'login.php',
                  'error' => '',
                  'sql' => $sql);
                  ChromePhp::warn('Operation is successful. id = '.$info['id']);
   	    echo json_encode($info);   
   	    } else {
            $sorry = array('status' => "No",
                  'firstname' => $email,
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'email' => '',
                  'password' =>'',
                  'churchadmin' => false,
                  'id' => 0,
                  'action' => 'login.php',
                  'error' => mysql_error(),
                  'sql' => $sql);
                  ChromePhp::warn('Operation is unsuccessful. error: '.$info['error']);
            echo json_encode($sorry);             
	
   	    }
        mysql_close($db_handle);
  	} else {
           $sorry = array('status' => "system Error",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'email' => '',
                  'churchadmin' => false,
                  'id' => 0,
                  'action' => 'login.php',
                  'error' => mysql_error(),
                  'sql' => $sql,
                  'password' =>'');
                  ChromePhp::warn('System Error: '.$info['error']);
           echo json_encode($sorry);             
  		}// isset $_POST
  } else {
           $sorry = array('status' => "database not initialized",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'email' => '',
                  'id' => 0,
                 'churchadmin' => false,
                  'action' => 'login.php',
                  'error' => mysql_error(),
                  'sql' => $sql,
                 'password' =>'');
                  ChromePhp::warn('Database not initialized. error: '.$info['error']);
            echo json_encode($sorry);             
      mysql_close($db_handle);
      
      //
  }//$dbfound
}//isset($_SERVER
?>