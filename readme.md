EZProxy External Script Authentication with Alma API and LDAP
============================================================

This repository contains an example script for setting up External Script Authentication to query an LDAP server and the Alma User API.

## EZProxy user.txt Configuration

These scripts assume your EZproxy user.txt file is configured to direct users
attempting to authenticate to a script on another server which will use an 
EZProxy "ticket" to authenticate the user, e.g.:

::cgi=https://someschool.edu/ezalma.php?url=^U  
::Ticket  
AcceptGroups Default+Faculty  
TimeValid 10  
MD5 topsecretvalue  
Expired; Deny expired.html  
/Ticket  

##Installation

Clone repository onto a web server that supports PHP.  Include ezticket.php and functions.php in the same directory as ezalma.php.  You don't need the sample_response.txt file, that's just there to show you what the response from the Alma API looks like.

##Configuration

Copy and rename config.sample.php to config.php and fill in required values.  
Customize HTML / CSS in ezalma.php for desired look and feel of login form.


##Deploy

Set up EZProxy's user.txt file with the above configuration, replacing https://someschool.edu/ezalma.php with the location of your script.
