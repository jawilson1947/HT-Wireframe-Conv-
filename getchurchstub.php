<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
 {
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found)  {
 	if (isset($_POST["info"])) {
      $pkg = json_decode($_POST["info"]);
      $mode = $pkg->source;
      $domain = $pkg->domain;
      $placeid = $pkg->placeid;
      $sql = "select * from tblChurch where domain  = '".$domain."' or GoogleMapPlaceID = '".$placeid."'";
          $result = mysql_query($sql);
          if ($dbfield = mysql_fetch_assoc($result)){
            $info = array('status' => "ok",
                     'action' => $mode,
                     'error' => '',
                     'sql' => $sql,
                     'source' => 'ht.tblChurch',
                     'id' => $dbfield['ChurchID'],
                     'name' => $dbfield['churchname'],
                     'placeid' => $dbfield['GoogleMapPlaceId'],
                     'domain' => $dbfield['domain'],
                     'street' => $dbfield['address'],
                     'city' => $dbfield['city'],
                     'phone' => $dbfield['phone'],
                     'website' => $dbfield['website'],
                     'province' => $dbfield['state'],
                     'postalcode' => $dbfield['zipcode'],
                     'vicinity' => '',
                     'address' => $dbfield['address'],
                     'country' => $dbfield['country'],
                     'churchid' => $dbfield['ChurchID']
                     );
              } else {
                     $info = array('status' => "no",
                     'action' => $mode,
                     'error'=> mysql_error(),
                     'sql' => $sql,
                     'source' => 'ht.tblChurch');
                    }
            echo json_encode($info);
  	}//isset[post[info
  }//db_found
  mysql_close($db_handle);
 }//isset
?>

