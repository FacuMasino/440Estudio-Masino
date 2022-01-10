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
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-relay.sendinblue.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'joaqfm@gmail.com';                     //SMTP username
    $mail->Password   = 'jcvASTXwpI4U17Gx';                         //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('web440estudio@gmail.com', '440 Estudio'); // Hacer coincidir con el username. (preferentemente)
    $mail->addAddress('web440estudio@gmail.com', '440 Estudio');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Consulta de: '.$_POST['inp_nombre'];
    $mail->Body    = $HTML;
    /*    'Nombre: '.$_POST['inp_nombre'].
        '<br>Apellido: '.$_POST['inp_apellido'].
        '<br>Email: '.$_POST['inp_email'].
        '<br>Direccion: '.$_POST['inp_dir'].
        '<br>Mensaje: '.$_POST['inp_mensaje'];
    */
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    // COPIA DE CONFIRMACIÓN

    $mail->addAddress($_POST['inp_email'], $_POST['inp_nombre']);
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $_POST['inp_nombre']. ' recibimos tu consulta - 440 Estudio';
    $mail->Body    = $HTML_CC;
    /*    'Nombre: '.$_POST['inp_nombre'].
        '<br>Apellido: '.$_POST['inp_apellido'].
        '<br>Email: '.$_POST['inp_email'].
        '<br>Direccion: '.$_POST['inp_dir'].
        '<br>Mensaje: '.$_POST['inp_mensaje'];
    */
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo json_encode(array('enviado' => 1));
} catch (Exception $e) {
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo json_encode(array('enviado' => 0));
}

?>