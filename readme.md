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

Clone repository onto a web server that supports PHP.

##Configuration

Copy and rename config.sample.php to config.php and fill in required values.

##Deploy

Set up EZProxy's user.txt file with the above configuration, replacing https://someschool.edu/ezalma.php with the location of your script.
