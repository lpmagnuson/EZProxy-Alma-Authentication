<?php
//Alma API key from Ex Libris Developer Network. Required.
$key = '';
//Alma user group types that are allowed remote access via EZProxy.  Modify to match your Alma configuration and local needs.
$allowable_patrons = array("Administrator", "Faculty", "Retiree", "Staff", "TA/GA", "VisitingScholar", "ExtendedEducation", "GraduateStudent", "SpecialPrograms", "Undergraduate");
//Alma patron types considered faculty.  Example of how to use EZProxy groups functionality.  Modify as needed.
$faculty = array("Faculty", "Administrator");
//MD5 Secret from EZProxy user.txt. Required.
$secret = '';
//Define LDAP server, e.g., directory.school.edu. Required.
$ldap_server = "";
//LDAP bind port.  Usually 389.
$ldap_port = 389;
//LDAP Search base, e.g., ou=people,ou=Auth,o=wxyz
//If not sure of this, use the "test LDAP" functionality of EZProxy admin
$base = "";
//EZProxy URL, e.g., libproxy.school.edu
$libproxy = "";
?>