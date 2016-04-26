<?php 
include("class.phpmailer.php");
include("class.smtp.php");
 
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "alanisdg@gmail.com";
$mail->Password = "horacio1984+";



$mail->From = "user@domain.com";
$mail->FromName = "User Name";
$mail->Subject = "Subject del Email";
$mail->AltBody = "Hola, te doy mi nuevo numero\nxxxx.";
$mail->MsgHTML("Hola, te doy mi nuevo numero<br><b>xxxx</b>.");
$mail->AddAttachment("files/files.zip");
$mail->AddAttachment("files/img03.jpg");
$mail->AddAddress("alanisdg@gmail.com", "Destinatario");
$mail->IsHTML(true);
 
if(!$mail->Send()) {
  echo "Error: " . $mail->ErrorInfo;
} else {
  echo "Mensaje enviado correctamente";
}?>