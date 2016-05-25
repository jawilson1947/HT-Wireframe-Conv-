//router.js
function route(handle, pathname, response, request) {
	
console.log("router.route: About to route a request for " + pathname);
if(typeof handle[pathname] === 'function') {
 return handle[pathname](response, request);	
 } else {
  console.log("router.route: No request handler found for " + pathname);
  response.writeHead(404, {"Content-Type": "text/plain"});
  response.end( "404 Not Found");	
 }		
}

exports.route = route;