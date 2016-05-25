members.post('/login', cors(), function(req, res) {
    console.log('members.js.members.post/login');
    console.log("Incoming request: (" + req.method + "), " + req.url);
    var email = req.body.username;
    var userpsw = req.body.password;
    var dbstatus = {"status":"","action":"login lookup","error":"","sql":""};
//console.log('Email: '+ email);
    console.log('Opening Mongo Database to retrieve member profile');  
    var assert = require('assert');
    var MongoUrl = 'mongodb://localhost/htDB';
// fire in the hole!
// ensure that we are connected
    var dbstatus = {"status":"","action":"","error":"","sql":""};
    var db = MongoClient.connect(MongoUrl, function(dberr, db) {
        assert.equal(null, err);
        if(dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send({"status":"Database access error"});
            return;
        }
        console.log('Connected to MongoDB - HT Database successfully');
        var bioData = db.collection('members');    
        console.log('Searching for document with email: ' + email); 
        bioData.findOne({"member.bio.email": email}, function(error, doc) {
	    if(error){
	        console.log('Problemm encountered in MongoDB Stack');
	        dbstatus.error = email + ' lookup encountered a  system error';
	        dbstatus.status = "no";
            db.close(); 
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send(dbstatus);
            return;
	    }
            if(!doc) { 
                console.log(email + ' Could not be found');	
 	        dbstatus.error = email + ' not found';
		dbstatus.status = "no";
		db.close(); 
                res.setHeader('Content-Type', 'application/json');
                res.status(200).send(dbstatus);
                return;
            }  
            var memberdata = doc.member.bio;
 	    console.log('retrieved Person ' + memberdata.firstname + ' ' + memberdata.lastname);
            dbstatus.status = "ok";
            console.log('merging json objects: docs and dbstatus');
            dbstatus = merge(dbstatus, memberdata);
            console.log(dbstatus);
            db.close();
            console.log('Sending json package back to client');
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send(dbstatus); 
        });    
    });
});
