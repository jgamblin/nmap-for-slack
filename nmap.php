<?php
/*
REQUIREMENTS
* A custom slash command on a Slack team
* A web server running PHP5 with cURL enabled and NMap 7.
USAGE
* Place this script on a server running PHP5 with cURL.
* Set up a new custom slash command on your Slack team: 
  http://*.slack.com/services/new/slash-commands
* Under "Choose a command", enter whatever you want for 
  the command. /dns is easy to remember.
* Under "URL", enter the URL for the script on your server.
* Leave "Method" set to "Post".
* Decide whether you want this command to show in the 
  autocomplete list for slash commands.
* If you do, enter a short description and usage hint.
*/
# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
# Check the token and make sure the request is from our team 
if($token != '9OPGYcRv8sHXBJwWpfr3JKrM'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}

$user_agent = "Slack/1.0";

$cmd = "nmap --top-ports 50 --open ".$text;
#while (@ ob_end_flush()); // end all output buffers if any

$proc = popen($cmd, 'r');
while (!feof($proc))
{
    echo fread($proc, 4096);
    @ flush();
}
