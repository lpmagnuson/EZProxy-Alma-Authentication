<?php
$key = '';
//Alma patron types that are allowed remote access via EZProxy
$allowable_patrons = array("Faculty", "Undergraduate-General", "Graduate", "ParttimeFacultyLecturers", "EmeritusFaculty", "EXLUndergraduate", "EXLGraduate", "StaffCSUN", "Staff", "Administrator", "VisitingScholar", "TeachingAssociate", "FacultyStaffRetired", "GraduateDoctoral", "Undergraduate", "ExtendedEducationOpenUniversity", "SpecialPrograms");
//Alma patron types considered faculty.  Example of how to use EZProxy groups functionality.  Optional.
$faculty = array("Faculty", "ParttimeFacultyLecturers", "EmeritusFaculty");
//MD5 Secret from EZProxy user.txt
$secret = '';
//Define LDAP server, e.g., directory.school.edu
$ldap_server = "";
$ldap_port = 389;
//LDAP Search base, e.g., o=wxyz
$base = "";
?>