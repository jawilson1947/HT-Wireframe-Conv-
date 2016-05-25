<?php
 include 'ChromePhp.php';
 ChromePhp::warn('Running: start.php');
 $user_name = 'root';
 $password = 'DanaDenyse32';
 $database = 'ht';
 $server = '127.0.0.1';
 $cookie_name = 'holytomatoes';
//   if(!isset($_COOKIE[$cookie_name])) {
     // print 'Cookie with name "' . $cookie_name . '" does not exist...';
//     return;
//} else {
  //print 'Cookie with name "' . $cookie_name . '" value is: ' . $_COOKIE[$cookie_name];
//}
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  $email = $_COOKIE[$cookie_name];
  ChromePhp::warn('Email in Holy Tomatoes cookie is: '.$email);
  if($db_found)  {
   $sql = "Select * from tblMember where lower(email) = '".strtolower($email)."'";
   $result = mysql_query($sql);
   if ($dbfield = mysql_fetch_assoc($result)){
    $info = array('status' => "ok",
                  'firstname' => $dbfield['firstname'],
                  'lastname' => $dbfield['lastname'],
                  'churchname' => $dbfield['churchname'],
                  'placeid' => $dbfield['placeid'],
                  'password' => $dbfield['userpsw'],
                  'churchadmin' =>$dbfield['churchadmin'],
                  'churchid' => $dbfield['churchID'],
                  'email' => $email,
                  'id' => $dbfield['userID'],
                  'status' => 'ok',
                  'action' => 'login',
                  'error' => '',
                  'sql' => '');
                  ChromePhp::warn('Email belongs to: '.$info['firstname'].' '.$info['lastname']);
                  ChromePhp::warn('Church ID in Core: '.$info['churchid']);
   	echo json_encode($info);   }
   	else {
      $sorry = array('status' => "No",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'datecreated' => '');
                  ChromePhp::warn('No record found for: '.$email);
     echo json_encode($sorry);             
	
   	}
   mysql_close($db_handle);
  }
  else {
   ChromePhp::warn("Database NOT Found!".$db_handle);
   mysql_close($db_handle);
 }

?>
