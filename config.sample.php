<?php
//Alma API key from Ex Libris Developer Network
$key = '';
//Alma user group types that are allowed remote access via EZProxy
$allowable_patrons = array("Administrator", "Faculty", "Retiree", "Staff", "TA/GA", "VisitingScholar", "ExtendedEducation", "GraduateStudent", "SpecialPrograms", "Undergraduate");
//Alma patron types considered faculty.  Example of how to use EZProxy groups functionality.
$faculty = array("Faculty", "ParttimeFacultyLecturers", "EmeritusFaculty");
//MD5 Secret from EZProxy user.txt
$secret = '';
//Define LDAP server, e.g., directory.school.edu
$ldap_server = "";
$ldap_port = 389;
//LDAP Search base, e.g., o=wxyz
$base = "";
?>