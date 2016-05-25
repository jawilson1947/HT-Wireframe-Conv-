<?php
$json = <<<JSON
 {
  "origin":"Delhi",
  "destination":"London",
  "passengers":
   [
    {
     "name":"Mr. Perry Mason",
     "type":"Adult",
     "age":28
     },
     { 
      "name":"Miss Irene Adler",
      "type":"Adult",
       "age":25
       },
      {
      	"name":"Mr. Jimmie Wilson",
       "type":"Adult",
       "age":68
      }
     ],
     "travelDate":"17-Dec-2010"
   }
   JSON;
   echo '<pre>';
   $objJson = json_decode($json);
   print_r ($objJson);
   echo '</pre>';
?>

