<?php
use src\support\Mail;
use \src\models\User;
use src\models\Auth;
require __DIR__ . "/vendor/autoload.php";

// $mail = new Mail();
$msg = '<h1>Email de Teste</h1>' .
    '<p>Testando disparo de email com <b>PHPMailer</b> e <b>sendgrid</b>.</p>' .
    '<p>Se esse email chegou, está funcionando.</p>';

echo '<pre>';
$user = new User();
$auth = new Auth();
$auth->email = 'tvirapegubeco@gmail.com';
$auth->password = 'tvirapegubeco@gmail.com';
var_dump($auth->forget('tvirapegubeco@gmail.com'));

// var_dump($user->findByEmail('tvirapegubeco@gmail.com')->fetch());
echo '</pre>';
{
$mail_date = date('d/m/Y H:i:s');
$toEmail = 'niltonbatera297@gmail.com';
$fromEmail = 'tvirapegubeco@hotmail.com';
$subject = 'Assunto';
$headers = "From: {$fromEmail}\n" .
    "content-type: text/html; charset=\"utf-8\"\n\n";
$body = $msg;
}
// mail($toEmail, $subject, $body, $headers);