var mysql = require('./node_modules/mysql');
var config = require('./config.json');
var validator = require('./validation.js');

function formatErrorResponse(code, errs) {
	return JSON.stringify({ 
		error  : code,
		errors : errs
	});
}

exports.handler = (event, context, callback) => {
	//instruct the function to return as soon as the callback is invoked
	context.callbackWaitsForEmptyEventLoop = false;

	//validate input
	var errors = new Array();
	
	if(errors.length > 0) {
		// This should be a "Bad Request" error
		callback(formatErrorResponse('BAD_REQUEST', errors));
	} else {
	
		//getConnection equivalent
		var conn = mysql.createConnection({
			host 	: config.dbhost,
			user 	: config.dbuser,
			password : config.dbpassword,
			database : config.dbname
		});
		conn.connect(function(err) {
			
			if (err)  {
				// This should be a "Internal Server Error" error
				callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			};
			console.log("Connected!");
					
			var sql="INSERT INTO usersessions (usersessionid, userid, expires, registrationcode)" +
				"VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY), ?)";
				
				conn.query(sql, [event.usersessionid, event.userid, event.expires, event.registrationcode], function (err, result) {
						if (err) {
							callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
						} else {
						    console.log("successful session creation");
					      	callback(null,"successful session creation");
							conn.exit();
				      	}
					}