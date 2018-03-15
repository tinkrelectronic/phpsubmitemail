<?php
  ob_start();
 
  $file = file_get_contents("php://input"); //Gets binary PDF Data
  $time = microtime(true);

  //Names pdf file based on the time to the microsecond so nothing gets overwritten.  Obviously you can change this to whatever you want or do something like $_REQUEST['formName'] and just include the form name in your URL from your pdf submit button
  $newfile = "forms" . $time . ".pdf"; 
  $worked = file_put_contents($newfile, $file); //Creates File
  ob_end_clean();

  //Upon completion you can either return XFDF to update a field on your form or display a PDF document.  I chose the PDF route because it worked for our situation.
  $successFile = 'success.pdf';
  $errFileFile = 'error.pdf';
  require 'phpmailer/PHPMailerAutoload.php';

  $mail = new PHPMailer;
  $mail->isSendmail();
  $mail->setFrom('From email address', 'from name');
  $mail->addReplyTo('Reply to email', 'Reply to name');
  $mail->addAddress($_REQUEST['email']); //get email from URL- alternately you can just hardcode in an address
  $mail->addAttachment( $newfile );
  $mail->isHTML(true);
  $mail->Subject = 'New Ad form submission';
  $mail->Body = 'A new form has been submitted.';

  if(!$mail->send()) {
    header('Content-type: application/pdf');
    readfile($errFile);
  } else {
    header('Content-type: application/pdf');
    readfile($successFile);
    //if you want to delete the file from the server after emailing: unlink($newfile);
  }
?>