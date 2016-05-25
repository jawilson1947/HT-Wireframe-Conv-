<?php  
include'ChromePhp.php';
//getchurchratings.php
ChromePhp::warn('getchurchratings.php');
$user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';  
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found)  {
      $pkg = json_decode($_POST["info"]);
      $sql = "select * from ht.tblUserRating where ChurchID = ".$pkg->id;
      ChromePhp::warn($sql);
      $result = mysql_query($sql) or die(mysql_error());
      if($result) {
        for($i = 0; $userrating[$i] = mysql_fetch_assoc($result); $i++) ;
        array_pop($ratings); 
        $userratingcount = sizeof($userrating); 
        ChromePhp::warn("Total Ratings for ".$pkg->name.": ".$userratingcount);
        if($userratingcount >1 ) {
          ChromePhp::warn($userratingcount.' Church Ratings found');       
           } else {
             $userrating[] = array('userid' => 0,
              'action' => 'select',
                     'error'=> mysql_error(),
                     'sql' => $sql,
                     'source' => 'GetuserRatings');           
                 }
        }
            echo json_encode($userrating);
  }//db_found
  mysql_close($db_handle);
?>
   