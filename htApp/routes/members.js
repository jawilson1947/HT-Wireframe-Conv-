//members.js
var express=require('express');
var http=require('http');
var cors=require('cors');
var MongoClient=require('mongodb').MongoClient;
//var ObjectId=require('mongodb').ObjectID;
var util=require('util');
var MemberTemplate=require('../files/members.json');
var PeerSurveyTemplate = require('../files/peersurvey.json');
var members=express.Router();
var bodyParser=require('body-parser');
members.options('*', cors());
var merge=require('merge'), original, cloned;
//var trigger=require('mongo-triggers');
var jsonsafeparse=require('json-safe-parse');
var ip=require('ip');
members.use(bodyParser.json());
members.use(bodyParser.urlencoded({extended: true}));

/* get root of /members */
members.get('/', function (req, res) {
    console.log('members.js.get/ (root)');
    res.send({"go": "Broncos"});
});
// handle member photo upload here
members.get('/photo', function (req, res) {
    console.log('members.js.router.get/(members)');
    res.send({"firstname": "Peaches", "lastname": "Wilson"});
});
members.get('/ip', cors(), function (req, res) {
    console.log('members.js.get/ip');
    res.setHeader('Content-Type', 'application/json');
    console.log('localhost ip: ' + ip.address());
    res.status(200).send({"ip": ip.address(), "status": "ok"});

});
//members/saverating
members.post('/saverating/:rdoc', cors(), function (req, res) {
    console.log('members.js.post/saverating');
//  res.setHeader('Content-Type', 'application/json');
    var RatingDocument=jsonsafeparse(req.params.rdoc);
    console.log(RatingDocument);
    var userid=RatingDocument.userid;
    console.log('Userid: ' + userid);
    console.log(RatingDocument.eventname + ' at ' + RatingDocument.churchname);
    var dbstatus={"status": "", "action": "", "error": "", "sql": ""};
    var MongoUrl='mongodb://localhost/ht';
    var db=MongoClient.connect(MongoUrl, function (dberr, db) {
        var assert=require('assert');
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.status(200).send({"status": "Database access error"});
            return;
        }
        console.log('Connected to MongoDB - HT Database successfully');
        var bioData=db.collection('members');
        console.log('Attempting Rating sub-document insertion for event');
        bioData.update({"member.bio.userid": userid}, {$push: {"member.ratings": RatingDocument}}, function (err, doc) {
            if (doc) {
                console.log('insertion successful');
                dbstatus={"status": "ok"};
                console.log(dbstatus);
                db.close();
                res.status(200).send(dbstatus);
                return;
            } else if (err) {
                dbstatus.error=err;
                dbstatus.status="error";
                res.status(200).send(dbstatus);
                db.close();
                return;
            } else {
                dbstatus.status='problem';
                res.status(200).send(dbstatus);
                db.close();
                return;
            }
        });
    });
});

/* GET members  page. */
members.get('/bio', function (req, res) {
    console.log("members.js.router.get/bio (members)");
    res.render('index', {title: "I think I've got this"});
});
// enable pre-flight for Cross domain call
/* POST get json object from login.html */
members.post('/login', cors(), function (req, res) {
    console.log('members.js.members.post/login');
    var userStub = req.body;
//    console.log(userStub);
    var email=userStub.email;
    var userpsw=userStub.password;
    var dbstatus={"status": "", "action": "login lookup", "error": "", "sql": ""};
//console.log('Email: '+ email);
    console.log('Opening Mongo Database to retrieve member profile for: '+ email);
    var assert=require('assert');
    var MongoUrl='mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
    var dbstatus={"status": "", "action": "", "error": "", "sql": ""};
    var db=MongoClient.connect(MongoUrl, function (dberr, db) {
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.setHeader('Content-Type', 'application/json');
            res.status(200).send({"status": "Database access error"});
            return;
        }
        console.log('Connected to MongoDB - HT Database successfully');
        var bioData=db.collection('members');
        console.log('Searching for document with email: ' + email);
        bioData.findOne({"member.bio.email": email}, function (error, doc) {
            if (error) {
                console.log('Problemm encountered in MongoDB Stack');
                dbstatus.error=email + ' lookup encountered a  system error';
                dbstatus.status="no";
                db.close();
//            res.setHeader('Content-Type', 'application/json');
                res.status(200).send(dbstatus);
                return;
            }
            if (!doc) {
                console.log(email + ' Could not be found');
                dbstatus.error=email + ' not found';
                dbstatus.status=" not found";
                db.close();
//                res.setHeader('Content-Type', 'application/json');
                res.status(200).send(dbstatus);
                return;
            } else {
                var memberdata=doc.member.bio;
                console.log('retrieved Person ' + memberdata.firstname + ' ' + memberdata.lastname);
                if (memberdata.userpsw == userpsw) {
                    dbstatus.status="ok";
                } else {
                    dbstatus.status=" Incorrect Password!";
                }
                console.log('merging json objects: docs and dbstatus');
                dbstatus=merge(dbstatus, memberdata);
                console.log(dbstatus);
                db.close();
                console.log('Sending json package back to client');
//            res.setHeader('Content-Type', 'application/json');
                res.status(200).send(dbstatus);
            }
        });
    });
});
//handle user stub inserts/updates --debug-brk=36502
members.post('/manage', cors(), function (req, res) {
    console.log("members.js.router.post/manage");
    var userStub=req.body;
    //   res.setHeader('Content-Type', 'application/json');
    var bio=MemberTemplate.member.bio;
    bio.userid=parseInt(userStub.userid);
    bio.churchid=parseInt(userStub.churchid);
    bio.churchname=userStub.churchname;
    bio.placeid=userStub.placeid;
    bio.firstname=userStub.firstname;
    bio.lastname=userStub.lastname;
    bio.email=userStub.email.toLowerCase();
    bio.userpsw=userStub.userpsw;
    // create the object
    var today=new Date();
    //   console.log('Current date is: ' + today);
    //make sure email does not already exist
    //************************
    console.log('Validating  email');
    var assert=require('assert');
    var MongoUrl='mongodb://localhost/ht';
    // fire in the hole!
    // ensure that we are connected
    var db=MongoClient.connect(MongoUrl, function (dberr, db) {
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            db.close();
            res.status(200).send({"status": "dbError", "error": dberr});
            return;
        }
        var bioData=db.collection('members');
        bioData.findOne({"member.bio.email": bio.email}, function (error, doc) {
            if (error) {
                db.close();
                res.status(200).send({"status": "Unable to pre-validate", "error": error});
                return;
            }
            if (!doc && (bio.userid == 0)) {
                // new record
                console.log(' inserting ' + bio.firstname + ' ' + bio.lastname);
                var dbstatus={"status": "", "action": "", "error": "", "sql": ""};
                MemberTemplate.member.bio.churchid=bio.churchid;
                MemberTemplate.member.bio.churchname=bio.churchname;
                MemberTemplate.member.bio.placeid=bio.placeid;
                MemberTemplate.member.bio.firstname=bio.firstname;
                MemberTemplate.member.bio.lastname=bio.lastname;
                MemberTemplate.member.bio.userpsw=bio.userpsw;
                MemberTemplate.member.bio.email=bio.email;
                MemberTemplate.member.bio.updated_at=today;
                MemberTemplate.member.bio.created_on=today;
                bioData.findOneAndUpdate({"keycontrol.userid": {$gt: 0}},
                    {$inc: {"keycontrol.userid": 1}},
                    {new: false},
                    function (error, result) {
                        if (result) {
                            bio.userid=result.value.keycontrol.userid;
                            console.log('Document new UserID returned: ' + bio.userid);
                            MemberTemplate.member.bio.userid=bio.userid;
                            console.log(MemberTemplate.member.bio);
                            MemberTemplate._id = require('mongodb').ObjectID();
                            console.log('New Object ID: ' + MemberTemplate._id);
                            bioData.insertOne(MemberTemplate, function (error, doc) {
                                if (error) {
                                    console.log('Error with this Action: ' + error);
                                    dbstatus.status="Insert error";
                                    dbstatus.action="Adding " + bio.firstname + ' ' + bio.lastname;
                                    dbstatus.error=error;
                                    dbstatus.sql='bioData.insertOne("")';
                                    res.status(200).send(dbstatus);
                                    db.close();
                                    return;
                                } //!error
                                if (!doc) {
                                    console.log('Problem with the Insert Action: ' + error);
                                    dbstatus.status="Document error";
                                    dbstatus.action="Adding " + bio.firstname + ' ' + bio.lastname;
                                    dbstatus.error='Query successful but document is non-existent';
                                    dbstatus.sql='bioData.insertOne("MemberTemplate")';
                                    res.status(200).send(dbstatus);
                                    db.close();
                                    return;
                                } else {
                                    console.log('Data Insertion for ' + bio.firstname + ' ' + bio.lastname + ' succesful!');
                                    dbstatus.status="ok";
                                    dbstatus=merge(dbstatus, bio);
                                    console.log('Sending json package back to client');
                                    res.status(200).send(dbstatus);
                                    db.close();
                                    return;
                                } //!doc
                            }); //bioData.insertOne
                        }//if result is good from new userid assignment
                    });// bioData.findOneandUpdate
            } else {
                var AlreadyInFile=doc.member.bio.userid;
                bio.userid=AlreadyInFile;
                console.log(' Updating ' + bio.firstname + ' ' + bio.lastname);
                var dbstatus={"status": "", "action": "", "error": "", "sql": ""};
                var updateObj={
                    "member.bio.churchid": bio.churchid,
                    "member.bio.churchname": bio.churchname,
                    "member.bio.placeid": bio.placeid,
                    "member.bio.firstname": bio.firstname,
                    "member.bio.lastname": bio.lastname,
                    "member.bio.userpsw": bio.userpsw,
                    "member.bio.email": bio.email,
                    "member.bio.updated_at": today
                };
                console.log(updateObj);
                bioData.updateOne({"member.bio.userid": bio.userid}, {$set: updateObj}, function (error, doc) {
                    if (error) {
                        console.log('Error with this Query: ' + error);
                        dbstatus.status="Query error";
                        dbstatus.action="updating " + bio.firstname + ' ' + bio.lastname;
                        dbstatus.error=error;
                        dbstatus.sql='bioData.updataOne("member.bio.userid:")';
                        //                res.setHeader('Content-Type', 'application/json');
                        res.status(200).send(dbstatus);
                        db.close();
                        return;
                    } //error
                    if (!doc) {
                        console.log('Problem with the returned document: ' + error);
                        dbstatus.status="Document error";
                        dbstatus.action="updating " + bio.firstname + ' ' + bio.lastname;
                        dbstatus.error='Query successful but document is non-existent';
                        dbstatus.sql='bioData.updataOne("member.bio.userid:")';
                        //                res.setHeader('Content-Type', 'application/json');
                        res.status(200).send(dbstatus);
                        db.close();
                        return;
                    } //!doc
                    console.log('Update for ' + bio.firstname + ' ' + bio.lastname + ' succesful!');
                    dbstatus.status="ok";
                    db.close();
                    dbstatus=merge(dbstatus, bio);
                    console.log('Sending json package back to client');
                    //            res.setHeader('Content-Type', 'application/json');
                    res.status(200).send(dbstatus);
                }); //biodata.updateOne
            }
        })
    });
}); // members/manage

//******************************************************
// update document;
members.get('/ratings/:id', cors(), function (req, res) {
    console.log("members.js.router.post/ratings/:id");
    var userid=parseInt(req.params.id);
    console.log('UserID: ' + userid);
    res.setHeader('Content-Type', 'application/json');
    // create the object
    console.log('Opening Mongo Database to search for rating sub-document');
    var assert=require('assert');
    var MongoUrl='mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
    var db=MongoClient.connect(MongoUrl, function (dberr, db) {
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            db.close();
            res.status(200).send({"status": "Error Opening Database"});
            return;
        }
        console.log('Connected to MongoDB - HT Database successfully');
        var bioData=db.collection('members');
        console.log('Searching for Member Ratings Sub Document with UserID: ' + userid);
        var items={
            "member.ratings.churchname": 1,
            "member.ratings.eventname": 1,
            "member.ratings.eventdate": 1,
            "member.ratings.score": 1
        };
        bioData.findOne({"member.bio.userid": userid}, {"member.ratings": 1}, {$sort: [['member.ratings.startdate', 'desc']]}, function (error, doc) {
            if (error) {
                db.close();
                res.status(200).send({"status": "no"});
            }
            if (!doc) {
                db.close();
                res.status(200).send({"status": "no"});
                return;
            } else {
                console.log('Search is Successful!');
                var RatingDoc=doc.member.ratings;
//     console.log(RatingDoc);
                res.status(200).send(RatingDoc);
                db.close();
            }
        });
    });
});
//
members.get('/peersurveyform', cors(), function (req, res) {
// Retrieves the blank Church Peer Survey Form
    console.log("members.js.router.get/peersurveyform/");
     res.setHeader('Content-Type', 'application/json');
    res.status(200).send(PeerSurveyTemplate) ;
 });
//
members.get('/peersurveystub/:id', cors(), function (req, res) {
// retireves the churchname and churchid of all Peer surveys done by an individual
    console.log("members.js.router.post/peersurveystub/:id");
    var userid = parseInt(req.params.id);
    console.log('UserID: ' + userid);
    res.setHeader('Content-Type', 'application/json');
    // create the object
    console.log('Opening Mongo Database to search for peer survey documents');
    var assert = require('assert');
    var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
    var db = MongoClient.connect(MongoUrl, function (dberr, db) {
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            db.close();
            res.status(200).send({"status": "Error Opening Database"});
            return;
        }
        console.log('Connected to MongoDB - HT Database successfully');
        var peerData = db.collection('peersurveys');
        console.log('Searching for Peer Survey Document with UserID: ' + userid);
        peerData.find({"peersurvey.userid": userid}, {
            "peersurvey.churchname": 1,
            "peersurvey.churchid": 1
        }).toArray(function (err, docs) {
            if (docs.length > 0) {
                console.log('Search is Successful!');
                res.status(200).send(docs);
                db.close();
            } else {
                db.close();
                console.log('No Peer Surveys found');
                res.status(200).send(PeerSurveyTemplate);
            }
        });
    });
});
    members.get('/peersurvey/:pStub', cors(), function (req, res) {
// retrieves the Peer Survey for a given church and given person
        console.log("members.js.router.post/peersurveys/:pStub");
        var peerStub = jsonsafeparse(req.params.pStub);
        //   res.setHeader('Content-Type', 'application/json');
        var churchid = parseInt(peerStub.churchid);
        var userid=parseInt(peerStub.userid);
        console.log('UserID: ' + userid);
        console.log('ChurchID: ' + churchid);
        res.setHeader('Content-Type', 'application/json');
        // create the object
        console.log('Opening Mongo Database to search for peer survey document');
        var assert=require('assert');
        var MongoUrl='mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
        var db=MongoClient.connect(MongoUrl, function (dberr, db) {
            assert.equal(null, dberr);
            if (dberr) {
                console.log('!!** Error opening database: ' + dberr);
                db.close();
                res.status(200).send({"status": "Error Opening Database"});
                return;
            }
            console.log('Connected to MongoDB - HT Database successfully');
            var peerData=db.collection('peersurveys');
            console.log('Searching for Peer Survey Document with UserID: ' + userid);
            peerData.findOne({"peersurvey.userid": userid,"peersurvey.churchid": churchid}).toArray(function(err, docs) {
                if(docs.length > 0){
                    console.log('Search is Successful!');
                    res.status(200).send(docs);
                    db.close();
                } else {
                    db.close();
                    console.log('No Peer Surveys found');
                    docs = [];
                    docs.push(PeerSurveyTemplate);
                    res.status(200).send(docs);
                }
            });
        });
});

module.exports=members;
