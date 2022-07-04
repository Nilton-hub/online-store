<?php
namespace src\support;

use \PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\SMTP;
use \stdClass;

class Mail
{
    /** @var PHPMailer */
    private $mail;

    /** @var stdClass */
    private $data;

    /** @var Exception */
    private $error;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->data = new stdClass();
        // send config
        $this->mail->isSMTP(); //ok
        $this->mail->isHTML(true); //ok
        $this->mail->setLanguage('br'); //ok
        $this->mail->SMTPAuth = true; //ok
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //ok
        $this->mail->CharSet = "utf-8"; //ok
        // from config
        $this->mail->Host = MAIL['host']; //ok
        $this->mail->Port = MAIL['port']; //ok
        $this->mail->Username = MAIL['user']; //ok
        $this->mail->Password = MAIL['password']; //ok
        
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
    }

    /**
     * @var string $subject
     * @var string $body
     * @var string $recipientName
     * @var string $recipientEmail
     * @return Mail
     */
    public function add(string $subject, string $body, string $recipientName, string $recipientEmail): self
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipientName = $recipientName;
        $this->data->recipientEmail = $recipientEmail;
        return $this;
    }

    /**
     * @var string $filePath
     * @var string $fileName
     * @return Mail
     */
    public function attach(string $filePath, string $fileName): self
    {
        $this->data->attach[$filePath] = $fileName;
        return $this;
    }

    public function send(string $fromName = MAIL['from_name'], string $fromEmail = MAIL['from_email']): bool
    {
        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->msgHTML($this->data->body);
            $this->mail->addAddress($this->data->recipientEmail, $this->data->recipientName);
            $this->mail->setFrom($fromEmail, $fromName);

            if (!empty($this->data->attach)) {
                foreach ($this->data->attach as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }
            $this->mail->send();
            return true;
        } catch (MailException $exception) {
            $this->error = $exception;
            return false;
        }
    }

    public function error(): ?MailException
    {
        return $this->error;
    }
}
