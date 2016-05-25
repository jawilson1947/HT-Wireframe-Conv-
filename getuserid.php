<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found) {
  	if(isset($_POST["info"])) {
      $data = json_decode($_POST["info"]);
      $email = $data->email;
      $sql = "Select userID,firstname,lastname,churchname,placeid,datecreated,userpsw,churchadmin,churchid from tblMember where lower(email) = '".strtolower($email)."'";
      $result = mysql_query($sql);
      if($dbfield = mysql_fetch_assoc($result)){
        $info = array('status' => "ok",
                  'firstname' => $dbfield['firstname'],
                  'lastname' => $dbfield['lastname'],
                  'churchname' => $dbfield['churchname'],
                  'placeid' => $dbfield['placeid'],
                  'datecreated' => $dbfield['datecreated'],
                  'userpsw' => $dbfield['userpsw'],
                  'churchadmin' => $dbfield['churchadmin'],
                  'id' => $dbfield['userID'],
                  'churchid' => $dbfield['ChurchID']);
   	    echo json_encode($info);   
   	    } else {
            $sorry = array('status' => "No",
                  'firstname' => $email,
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'datecreated' => '',
                  'userpsw' =>'',
                  'churchadmin' => false,
                  'id' => 0);
            echo json_encode($sorry);             
	
   	    }
        mysql_close($db_handle);
  	} else {
           $sorry = array('status' => "system Error",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'datecreated' => '',
                  'userpsw' =>'');
            echo json_encode($sorry);             
  		}// isset $_POST
  } else {
           $sorry = array('status' => "database not initialized",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'datecreated' => '',
                  'userpsw' =>'');
            echo json_encode($sorry);             
      mysql_close($db_handle);
  }//$dbfound
}//isset($_SERVER
?>
