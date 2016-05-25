<?php
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  include 'ChromePhp.php';
  ChromePhp::warn('genericDB.php');
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  try {
 	    ChromePhp::warn("Opening Data Connection");
 	    $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $pkg = json_decode($_POST["info"]);
         $sql= $pkg->sql;
            ChromePhp::warn($sql);        
          $stmt = $cn->prepare($sql);
          ChromePhp::warn('Query submitted');
          if($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ChromePhp::warn('Count of dbfield is: '.count($results, COUNT_RECURSIVE));
           if(empty($pkq->postsql)== false) {
             ChromePhp::warn('Post SQL: '.$pkg->postsql);
             $stmt->closeCursor();
             $post = $cn->prepare($pkg->postsql);
             if($post->execute()){
                ChromePhp::warn('Post SQL successfuly executed');
             } else {
                ChromePhp::warn('Post SQL unsucessful');
             }
           } 
            echo json_encode($results);
          } else {
                     ChromePhp::warn('Search not successful');
                       $pkg->status = 'Not Found';
                     ChromePhp::warn("Church ID for ".$info['name']." is ".$info['id']);
                 echo json_encode($pkg);
          }
   }catch (PDOException $e) {
      ChromePhp::warn('Query Bombed');
      $c['status'] = 'no';
      $c['action'] = 'query';
      $c['error'] = $e->getMessage();
      $c['sql'] = $sql;
      echo json_encode($c);
	
  }	
 
?>