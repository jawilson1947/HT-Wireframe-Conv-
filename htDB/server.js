//server.js

var http = require("http");
var url = require("url");

function start(route, handle) {
 function onRequest(request, response) {
//  var postData = "";	
  var pathname = url.parse(request.url).pathname;
  console.log("server.onRequest: Request for " + pathname + " received.");
  route(handle, pathname, response, request);
  
 
/*   request.setEncoding("utf8");
   
   request.addListener("data", function(postDataChunk) {
//called when a new chunk of data is received	
    postData += postDataChunk;
    console.log("Received POST data chunk '"  + 
     postDataChunk + "'.");
   });

   request.addListener("end", function() {
     //called when all chunks of data have been received
    route(handle, pathname, response, postData);
   });

 
 /* 
  response.writeHead(200, {"Content-Type": "text/plain"});
  var content = route(handle, pathname);
  response.write(content);
//  response.end("Welcome to Holy Tomatoes Application Stack");	
  response.end();
*/  
  }
  
  http.createServer(onRequest).listen(8888);
   console.log("server.start: Server has started"); 	
}

exports.start = start;
