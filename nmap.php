<?php

/***********************************************************************************************
 *
 * REQUIREMENTS
 * A custom slash command on a Slack team
 * A web server running PHP5 with cURL enabled and NMap 7.
 * USAGE
 * Place this script on a server running PHP5 with cURL.
 * Set up a new custom slash command on your Slack team: 
 * http://*.slack.com/services/new/slash-commands
 * Under "Choose a command", enter whatever you want for 
 * the command. /dns is easy to remember.
 * Under "URL", enter the URL for the script on your server.
 * Leave "Method" set to "Post".
 * Decide whether you want this command to show in the 
 * autocomplete list for slash commands.
 * If you do, enter a short description and usage hint.
 *
 * Co-author: reedphish 
 * 
 * Reedphish contact information: 
 * reedphish@outlook.com>, github.com/reedphish, reedphish.wordpress.com, twitter.com/reedphish
 ***********************************************************************************************/

/**
 * Master token, replace this with the token from your slash command configuration page
 */
$mastertoken = "motorhead";

/**
 * Processing POST request
 */
if(isset($_POST["command"]) && isset($_POST["token"]) && isset($_POST['text'])) {
	// Grab some of the values from the slash command, create vars for post back to Slack
	$command = $_POST['command'];
	$token = $_POST['token'];
	$text = $_POST['text'];

	// Check the token and make sure the request is from our team 
	if($token != $mastertoken) {
		die("The token for the slash command doesn't match. Check your script.");
	}

	$user_agent = "Slack/1.0";

	if(isvalid($text)) {
		$cmd = "nmap --top-ports 50 --open {$text}";

		$proc = popen($cmd, 'r');
		
		while (!feof($proc))
		{
		    echo fread($proc, 4096);
		    @ flush();
		}
	} else {
		die("Address is not legal");
	}
} else {
	die("Failure processing request. Please check required parameters and transport verb.");
}

/**
 * Check if string contains illegal characters
 */
function isValid(string $content) {
	return preg_match('/^[a-zA-Z0-9\.]*$/', $content);
}