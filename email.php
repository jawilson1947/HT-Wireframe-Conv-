<?php 
 //email.php
  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  $host = "127.0.0.1";
  $user = "root";
  $pass = "DanaDenyse32";

  $databaseName = "ht";
  $tableName = "tblMember";
  $email = $_GET['email'];
  $ok = "No";
  $info = "";
  $mysqli = new mysqli($host, $user, $pass, 'ht');
    if (mysqli_connect_errno())
      {
      	 die('Unable to Connect!');
      	 $ok="Oops!";
      }
      else
       {
       	  $db_handle = mysql_connect($server,$user_name,$password);
         $db_found = mysql_select_db($database,$db_handle);

         $query = "Select firstname,lastname,churchname,placeid from tblMember where email ='".$email."'";
         if ($result = $mysqli->query($query))
           {
           	 if ($result->num_rows > 0)
           	  {       	
                $ok = "Yes";
                $
                $info = array(
                 'firstname' =>;
           	  }
            }
           $result->close();
      }
      $mysqli->close();
    switch ($ok) {
   case "Yes":
         echo $info;
         break;
   case "No":
         echo json_encode($email.":Not Found");
         break;
   case "Oops":
         echo json_encode("firstname:Unable to Connect");
         break;
  }  
    
  
  
       
  /*
  
  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  //include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  if(!$con) {
  	 die('Unable to connect: '. mysql_error();
  }
  else
    {
    	 $dbs = mysql_select_db($databaseName, $con);

  //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------
    $stmt = $dbh->prepare("SELECT firstname,lastname,churchname,placeid FROM tblMember where email = ?");
   if ($stmt->execute(array($email))) {
     echo json_encode( ($row = $stmt->fetch()) )
   }
  //$result = mysql_query("SELECT * FROM $tableName where email = '"+ $email + "'");          //query
  //$array = mysql_fetch_row($result);                          //fetch result    

  //--------------------------------------------------------------------------
  // 3) echo result as json 
  //--------------------------------------------------------------------------
  //echo json_encode($array);
    }
   msql_close($con); 
   */
?>
