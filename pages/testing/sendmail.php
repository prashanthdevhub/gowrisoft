<?php
// Variables
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$subject = "Enquiry to Gowrisoft";
$message = trim($_POST['message']);
$company = trim($_POST['company']);
$subjectEnq = "--Message from Footer contact form--";
$bccEmailAddress1 = "prashanth11391@gmail.com";
if( isset($_POST['subject']) ){
	$subjectEnq = trim($_POST['subject']);
}

// Email address validation - works with php 5.2+
function is_email_valid($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}


if( isset($name) && isset($email) && isset($phone) && isset($message) && is_email_valid($email) ) {

	// Avoid Email Injection and Mail Form Script Hijacking
	$pattern = "/(content-type|bcc:|cc:|to:)/i";
	if( preg_match($pattern, $name) || preg_match($pattern, $email) || preg_match($pattern, $message) ) {
		exit;
	}

	// Email will be send
	$to = "hr@gowrisoftsolutions.com, gopal.gudi@gowrisoftsolutions.com, prabhakargudi@gmail.com";
	$sub = $subject; // You can define email subject
	// HTML Elements for Email Body
	$body = <<<EOD
	<strong>Name:</strong> $name <br> <br>
	<strong>Email:</strong> <a href="mailto:$email?subject=feedback" "email me">$email</a> <br> <br>
	<strong>Phone:</strong> $phone <br> <br>
	<strong>Company:</strong> $company <br><br>
	<strong>Subject:</strong> $subjectEnq <br><br>
	<strong>Message:</strong> $message <br>
EOD;
//Must end on first column
	
	$headers = "From: $name <$email>\r\n";
	$headers.= "bcc: " . $bccEmailAddress1 . "\r\n" ;
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// PHP email sender
	mail($to, $sub, $body, $headers);
}


?>
