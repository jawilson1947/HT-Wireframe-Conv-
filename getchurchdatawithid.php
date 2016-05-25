<?php
include 'ChromePhp.php';
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
ChromePhp::warn('getchurchdatawithid.php');	
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found)  {
 //	if (isset($_POST["info"])) {
      $pkg = json_decode($_POST["info"]);
      $sql = "select * from ht.tblchurch where ChurchID = ".$pkg->churchid;
      ChromePhp::warn($sql);
      $churchdata = mysql_fetch_array(mysql_query($sql));
      if($churchdata) {
        ChromePhp::warn('Query is Successful');
        if($churchdata['ChurchID'] == $pkg->churchid){
           ChromePhp::warn('Church is: '.$churchdata['churchname']);
           } else {
                     ChromePhp::warn(mysql_error());
                     $churchdata = array('churchid' => "0",
                     'action' => $mode,
                     'error'=> mysql_error(),
                     'sql' => $sql,
                     'source' => 'getchurchdatawithid');
                    }
          } else {
            ChromePhp::warn('Something is amiss');
          }
            echo json_encode($churchdata);
  //	}//isset[post[info
  }//db_found
  mysql_close($db_handle);
// }//isset
?>
