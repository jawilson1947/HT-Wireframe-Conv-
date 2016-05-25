<?php 

  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  $host = "localhost";
  $user = "root";
  $pass = "DanaDenyse32";

  $databaseName = "ht";
  $tableName = "tblMember";
  $email = $_GET['email'];

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  $dbs = mysql_select_db($databaseName, $con);

  //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------
  $result = mysql_query("SELECT * FROM $tableName where email = '"+ $email + "'");          //query
  $array = mysql_fetch_row($result);                          //fetch result    

  //--------------------------------------------------------------------------
  // 3) echo result as json 
  //--------------------------------------------------------------------------
  echo json_encode($array);

?>
