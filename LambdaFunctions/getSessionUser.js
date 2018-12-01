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
	
	 // Validate the user input
	validator.validateUserID(event.userid, errors);
	
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
		
		//attempts to connect to the database
		conn.connect(function(err) {
		  	
			if (err)  {
				// This should be a "Internal Server Error" error
				callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			} else {
				console.log("Connected!");
				var sql = "SELECT usersessionid, usersessions.userid, email, username, usersessions.registrationcode, isadmin " +
					"FROM usersessions " +
					"LEFT JOIN users on usersessions.userid = users.userid " +
					"WHERE usersessionid = ? AND expires > NOW()";
				
				conn.query(sql, [event.usersessionid], function (err, result) {
					
				  	if (err) {
						// This should be a "Internal Server Error" error
						callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
				  	} else {

				  		for(var i=0; i<result.length) {
							codes.push(result[i]['usersessionid']),
							codes.push(result[i]['userid']),
							codes.push(result[i]['email']),
							codes.push(result[i]['username']),
							codes.push(result[i]['registrationcode']),
							codes.push(result[i]['isadmin']);
						}
						
						// Build an object for the JSON response
						var json = {
							usersessionid : event.usersessionid,
							userid : event.userid,
							email : event.email,
							username : event.username,
							registrationcode : event.registrationcode,
							isadmin : event.isadmin
						};
						// Return the json object
						callback(null, json);
						conn.exit();
				  	}
				}); //query registration codes
			} // no connection error
		}); //connect database
	} //no validation errors
} //handler