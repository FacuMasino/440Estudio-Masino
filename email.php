<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Plantilla de email
include('htmlemail.php');

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     //(deshabilitado para devolver JSON correctamente)
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-relay.sendinblue.com';            //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                  //Enable SMTP authentication
    $mail->Username   = getenv('smtp_user');                   //SMTP username - Se obtiene usando las variables de entorno de HEROKU
    $mail->Password   = getenv('smtp_pass');                   //SMTP password - Se obtiene usando las variables de entorno de HEROKU
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                   //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Codificación
    $mail->CharSet = 'UTF-8';

    //Recipients
    $mail->setFrom('web440estudio@gmail.com', '440 Estudio'); // Hacer coincidir con el username. (preferentemente)
    $mail->addAddress('web440estudio@gmail.com', '440 Estudio');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Consulta de: '.$_POST['inp_nombre'];
    $mail->Body    = $HTML;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    // COPIA DE CONFIRMACIÓN

    $mail->clearAllRecipients(); // Borrar mails anteriores configurados como destino
    $mail->addAddress($_POST['inp_email'], $_POST['inp_nombre']);
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_POST['inp_nombre']. ' recibimos tu consulta - 440 Estudio';
    $mail->Body    = $HTML_CC;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo json_encode(array('enviado' => 1));
} catch (Exception $e) {
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo json_encode(array('enviado' => 0));
}

?>