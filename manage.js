members.post('/manage', cors(), function(req, res) {
    console.log("members.js.router.post/manage"); 
    var userStub = req.body;
    var bio = MemberTemplate.member.bio;
    console.log(userStub);
    bio.userid = parseInt(userStub.userid);
    bio.churchid  = parseInt(userStub.churchid);
    bio.churchname = userStub.churchname;
    bio.placeid = userStub.placeid;
    bio.firstname = userStub.firstname;
    bio.lastname = userStub.lastname;
    bio.email = userStub.email;
    bio.userpsw = userStub.userpsw;
 // create the object
 	
    var today = new Date();
    console.log('Current date is: ' + today);		
    updateObj = {"member.bio.churchid": bio.churchid, "member.bio.churchname": bio.churchname,
    "member.bio.placeid": bio.placeid, "member.bio.firstname": bio.firstname, "member.bio.lastname": bio.lastname,
    "member.bio.userpsw": bio.userpsw, "member.bio.email": bio.email, "member.bio.updated_at": today};   
 // check if this is an insert or update
    if(bio.userid == 0) {
       console.log(bio.firstname + ' ' + bio.lastname + ' document: "member.bio"');
       res.setHeader('Content-Type', 'application/json');
       res.status(200).send({"status":"Data insert not implemented as yet"});
       return;
    }
    console.log(' Updating ' + bio.firstname + ' ' + bio.lastname);
    console.log('Opening Mongo Database to retrieve member profile for update');  
    var dbstatus = {"status":"","action":"","error":"","sql":""};
    var assert = require('assert');
    var MongoUrl = 'mongodb://localhost/htDB';
// fire in the hole!
    var db = MongoClient.connect(MongoUrl, function(dberr, db) {
        assert.equal(null, dberr);
        if(dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send({"status":"Database access error"});
            return;
        }  
        console.log('Connected to MongoDB - HT Database successfully');
        var bioData = db.collection('members');    
        console.log('updating document with userid: ' + bio.userid); 
        console.log(updateObj);
        bioData.updateOne({"member.bio.userid":bio.userid}, {$set: updateObj}, function(error, doc) {
        if(error) {
            console.log('Error with this Query: ' + error);
            dbstatus.status="Query error";
            dbstatus.action = "updating " + bio.firstname + ' ' + bio.lastname;
            dbstatus.error = error;
            dbstatus.sql = 'bioData.updataOne("member.bio.userid:")';
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send(dbstatus);
            db.close();
            return;
        }
        if(!doc) {
            console.log('Problem with the returned document: ' + error);
            dbstatus.status="Document error";
            dbstatus.action = "updating " + bio.firstname + ' ' + bio.lastname;
            dbstatus.error = 'Query successful but document is non-existent';
            dbstatus.sql = 'bioData.updataOne("member.bio.userid:")';
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send(dbstatus);
            db.close();
            return;
        }
  	console.log('Update for ' + bio.firstname + ' ' + bio.lastname + ' succesful!');
        dbstatus.status = "ok";
        db.close();
        console.log('Sending json package back to client');
        res.setHeader('Content-Type', 'application/json');
        res.status(200).send(dbstatus);
     });
  });
});
