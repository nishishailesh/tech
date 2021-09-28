<?php
session_start();
require_once 'config.php';
require_once '/var/gmcs_config/staff.conf';
require_once 'common_table_function.php';
$link=set_session();
//my_print_r($_POST);
head();
menu();
echo '<h4 style="color:red;"><br>Kindly Read Instructions->general Carefully reagarding how to fill application form online.<br></h4>';
echo '<h4>Step 1: Application -> Fill</h4>';
echo '<h4>Step 2: Application -> Print<br><br></h4>';
echo '<h4 style="color:blue;">Note:</h4>
<ol>
<li><h4>Come personally for document verification with all <b><u>original documents</u></b> and application with Self Attested photocopies of required documents to EST section of GMC,Surat.<br>
Only if a candidate has cleared his/her examinations in current year and original marksheets for such examinations is not issued by concerned university/institutions, then only other evidences of passing examination and grades/marks obtained will be acceptable</h4></li>';
echo '<li><h4>Observe important dates for online submission and verification of original docuements (See Login Page for Dates)</h4></li>';
echo '<li><h4>Authority letter (as per format given at main menu-> instructions) is required, if applicant can not come personally for giving application and varification of original documents.</h4></li>';
echo '<li><h4>Application will be rejected if original document verification is not done before last date of verification.</h4></li>';
echo '<li><h4>Any original document  verification will not be entertained after last date for document verification.</h4></li>';
echo '<li><h4>Candidates are requested to visit Website and Notice Board of Government Medical College,Surat for list of selected candidates.It will be displayed after approximately one week from last date of physical verification of application/documents.(Website : www.gmcsurat.edu.in)</h4></li>';
echo '</ol>';
tail();
?>
