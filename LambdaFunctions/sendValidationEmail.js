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
	validator.validateEmail(event.email, errors);
	
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
			var sql = "INSERT INTO emailvalidation (emailvalidationid, userid, email, emailsent) " +
				"VALUES (?, ?, ?, NOW())";
				
				conn.query(sql, [event.emailvalidationid, event.userid, event.email, event.emailsent], function (err, result) {
					if (err) {
						callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
					} else {
						console.log("validation email sent");
					    callback(null,"validation email sent");
						conn.exit();
				    }
				}
		}
	}
}