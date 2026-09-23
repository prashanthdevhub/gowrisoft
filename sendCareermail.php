<?php
  $name = $_GET['name'];
  $email = $_GET['email'];
  $phone = $_GET['phone'];
  $msg = $_GET['msg'];
  $file = $_FILES['file']['name'];
  $ext = $_GET['ext'];
  $flag = true;
  $allowed =  array('doc','docx','pdf');
  
  $bccEmailAddress1 = "prashanth11391@gmail.com";

  if(!in_array($ext,$allowed) ) {
    $flag = false;
  }
  
    $to_email       = "hr@gowrisoftsolutions.com, gopal.gudi@gowrisoftsolutions.com, prabhakargudi@gmail.com";
 
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        $output = json_encode(array( //create JSON data
            'type'=>'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    }
 
    //Sanitize input data.
    $name      = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
    $email_address   = filter_var($email, FILTER_SANITIZE_EMAIL);
    $subject         = "Student career info request";
   
    $message = '<html><body>';
    $message .= '<div>';
    $message .= "<span><strong>Name : </strong>" . strip_tags($name) . "</span><br/><br/>";
    $message .= "<span><strong>Email Address : </strong> " . strip_tags($email) . "</span><br/><br/>";
    $message .= "<span><strong>Phone : </strong>" . strip_tags($phone) . "</span><br/><br/>";
    $message .= "<span><strong>Message : </strong>" . strip_tags($msg) . "</span><br/>";
    $message .= "</div>";
    $message .= "</body></html>";
 
 
    $file_attached = false;
    if(isset($_FILES['file'])) //check uploaded file
    {
        //get file details we need
        $file_tmp_name    = $_FILES['file']['tmp_name'];
        $file_name        = $_FILES['file']['name'];
        $file_size        = $_FILES['file']['size'];
        $file_type        = $_FILES['file']['type'];
        $file_error       = $_FILES['file']['error'];
 
 
 
        //exit script and output error if we encounter any
        if($file_error>0)
        {
            $mymsg = array(
            1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
            2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            3=>"The uploaded file was only partially uploaded",
            4=>"No file was uploaded",
            6=>"Missing a temporary folder" );
             
            $output = json_encode(array('type'=>'error', 'text' => $mymsg[$file_error]));
            die($output);
        }
         
        //read from the uploaded file & base64_encode content for the mail
        if ($file_size <= 0) {
            $output = json_encode(array('type'=>'error', 'text' => 'Uploaded file is empty'));
            die($output);
        }
        $handle = fopen($file_tmp_name, "r");
        if ($handle === false) {
            $output = json_encode(array('type'=>'error', 'text' => 'Could not read uploaded file'));
            die($output);
        }
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));
        //now we know we have the file for attachment, set $file_attached to true
        $file_attached = true;
 
 
 
        
    }
 
 
     
    if($file_attached) //continue if we have the file
    {
       
    // a random hash will be necessary to send mixed content
    $separator = md5(time());
 
    // carriage return type (RFC)
    $eol = "\r\n";
 
    // main header (multipart mandatory)
    $headers = "From:GowriSoftSolutionCarrers <info@gowrisoft.com>" . $eol;
	$headers.= "bcc: " . $bccEmailAddress1 . "\r\n" ;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;
 
    // message
    $body = "";
    $body .= "--" . $separator . $eol;
    $body .= "Content-type:text/html; charset=utf-8\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . $eol;
 
    // attachment
    $body .= "--" . $separator . $eol;
    $body  .= "Content-Type:".$file_type." ";
    $body .= "Content-Type: application/octet-stream; name=\"" . $file_name . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment; filename=\"".$file_name."\"". $eol;
    $body .= $encoded_content . $eol;
    $body .= "--" . $separator . "--";
        
    }
    else
    {

        $eol = "\r\n";
        $body = "";

        $headers = "From: GowriSoftSolutionCarrers <info@gowrisoft.com>" . $eol;
        $headers .= "Reply-To: ". strip_tags($email_address) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $body .= $message . $eol;

    }

    $send_mail = false;
    if($flag){
        $send_mail = mail($to_email, $subject, $body, $headers);
    }

    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'res' => 'failure'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'res' => 'success'));
        die($output);
    }
 
?>