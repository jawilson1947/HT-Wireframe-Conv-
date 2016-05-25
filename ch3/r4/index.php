<html>
 <head>
 <title>Using DOM</title>
 	<style type="text/css">
     h1{cursor:pointer;font-size:20px;}
     ul{display:none; list-style:none;margin:0pt;padding:0pt;}
 	</style>
 </head>
 <body>
   <h2>Welcome!</h2>
 	<?php
 	 $objXML = new DOMDocument();
 	 if(!$objXML)
 	    {
 	    echo '<h1>Cannot load XML Document</h1>';
 	    }
 	 else
 	  {   
 	 $objXML->load('http://localhost/ch3/common.xml', LIBXML_NOBLANKS);
 	 $books = $objXML->getElementsByTagName('book');
 	 foreach($books as $book)
 	  {
 	   echo '<h1>'.$book->firstChild->nodeValue.'
 	    ('.$book->firstChild->attributes->item(0)->value.')</h1>';
 	    $stories = $book->getElementsByTagName('story');
 	    echo '<ul>';
 	     foreach($stories as $story)
 	     {
 	      echo <'li>'.$story->firstChild->nodeValue.'</li>';
 	     }
 	     echo '</ul>';
 	    }
 	   }
 	  ?>
     <script type="text/javascript" src="../jquery.js"></script>
     <script type="text/javascript" src="../jquery-migrate.js"></script>  
     <script type="text/javascript">
     	$(document).ready(function()
     	 {
     	 	$('h1').click(function()
     	 	 {
     	 	 	$(this).next('ul').toggle('fast');
     	 	 }//$('h1').click
     	 	);
     	 }//$(document).ready
     	);
     </script>	  
 </body>
</html>
