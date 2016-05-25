//index.js
var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function(req, res, next) {
  console.log('router.index.js.router.get /');
  res.render('index', { title: 'Express' });
});
/* GET Hello World page */
router.get('/helloworld', function(req, res, next) {
 console.log('router.index.js.router.get/helloworld');
 res.render('helloworld', {title: 'Hello, Jimbo!'});
  
});

// GET member lookup json object
router.get('/members', function(req, res) {
console.log('router.index.js.router.get/members');  
res.send({"firstname":"Jim","lastname":"Wilson"}); 
/*
  var db = req.db;
  var members = db.get('members');
  
   members.find({},{"bio.email":1,"_id":0}, function(e, docs) {
   if(e) {
     console.log('db.members collection lookup error: ' + e);
    } else {
     console.log('db.members collection lookup successful');
//      res.render('userlist', {  "userlist" : docs   });
        res.send(docs);
    } 
});*/

});

module.exports = router;
