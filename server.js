'use strict';

var express = require('express');
var bodyParser = require("body-parser");
var app = express();
app.use(bodyParser.json());
var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(8890);

function pingUsers(ids) {
    //var usersWithFeeds = checkUsersForFeeds(ids);
    //console.log(usersWithFeeds);
    io.emit('article', ids);
}

app.post('/ping', function(req, res){
    //console.log(req.body);
    //return;
    pingUsers(req.body);
    res.setHeader('Content-Type', 'application/json');
    res.send(JSON.stringify({ ping: 'success' }));
});

var connectedUsers = [];

function getUserSocket(socket) {
    connectedUsers.map(function(i) {
       if(i.socket == socket) return i;
    });
}

function checkUsersForFeeds(ids) {
    ids = ids.split(',');
    var arr = [];
    connectedUsers.map(function(i) {
        var userID = i.userID;
        var userFeeds = i.feeds;
        var filteredIds = ids.map(function(i) {
           if(userFeeds.indexOf(parseInt(i)) > -1) return parseInt(i);
        });
        arr.push({userID: userID, feeds: filteredIds});
    });
    return arr;
}

io.on('connection', function(socket){

    /**
     * Receive request to subscribe a user. They pass feed id's.
     */
    socket.on('subscribe', function(obj){
        connectedUsers.push({socket: socket, feeds: obj.feeds, userID: obj.userID});
        //console.log(connectedUsers[0]);
        console.log(checkUsersForFeeds('1,2,3,4'));
    });

    /**
     * Run on socket disconnect
     */
    socket.on('disconnect', function() {
        var index = connectedUsers.indexOf(getUserSocket(socket));
        connectedUsers.splice(index, 1);

        console.log('Got disconnect!');
        console.log(connectedUsers);
    });

});