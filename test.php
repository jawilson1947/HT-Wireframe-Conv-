<?php  
$user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found)  {
      $sql = "select * from ht.tblchurch where ChurchID = 28092";
      $churchdata = mysql_fetch_array(mysql_query($sql));
      if($churchdata) {
      print_r($churchdata['GoogleMapPlaceID']);
      print_r('We have found a record for '.$churchdata['churchname']);
              } else {
                     print_r(mysql_error());
                     $churchdata = array('churchid' => "0",
                     'action' => $mode,
                     'error'=> mysql_error(),
                     'sql' => $sql,
                     'source' => 'getchurchdatawithid');
                    }
          
            echo json_encode($churchdata);
  }//db_found
  mysql_close($db_handle);
?>
