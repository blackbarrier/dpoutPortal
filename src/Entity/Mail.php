<?php

namespace App\Entity;

class Mail {

    protected $smtp;
    protected $mail;
    protected $pass;
    protected $body;

    function __construct() {
        $this->setSmtp('mail.gob.gba.gov.ar');
        $this->setMail('no-responder-dpout@gob.gba.gov.ar');
        $this->setPass('A1P_20201#60');
    }

    public function enviarMail($destinatario, $asunto = "", $body = "", $tipo = 'text/html') {
        $transport = (new \Swift_SmtpTransport($this->getSmtp(), 25))
                ->setUsername('no-responder-dpout')
                ->setPassword($this->getPass());
//        $options = array('stream_options'=>array('ssl'=>array(
//            'verify_peer' => false,
//            'verify_peer_name' => false,
//            'allow_self_signed' => true
//        )));
//        $transport->setStreamOptions($options);




        $mailer = new \Swift_Mailer($transport);


        $message = (new \Swift_Message($asunto))
                ->setFrom($this->getMail())
                ->setTo($destinatario);
        $body = $this->getBody($body, $message);
        $message->setBody($body, $tipo);

        return $mailer->send($message);
    }

    public function enviarMailConAdjunto($destinatario, $asunto = "", $body = "", $tipo = 'text/html',$nombreAdjunto="", $adjunto="") {
        $transport = (new \Swift_SmtpTransport($this->getSmtp(), 25))
                ->setUsername('no-responder-partidas')
                ->setPassword($this->getPass());




        $mailer = new \Swift_Mailer($transport);

        // You can alternatively use method chaining to build the attachment
        $attachment = (new \Swift_Attachment())
                ->setFilename($nombreAdjunto)
                ->setContentType('application/pdf')
                ->setBody($adjunto)
        ;
        $message = (new \Swift_Message($asunto))
                ->setFrom($this->getMail())
                ->setTo($destinatario)
                ->attach($attachment);
        $body = $this->getBody($body, $message);
        $message->setBody($body, $tipo);

        return $mailer->send($message);
    }

    function getSmtp() {
        return $this->smtp;
    }

    function getMail() {
        return $this->mail;
    }

    function getPass() {
        return $this->pass;
    }

    function setSmtp($smtp) {
        $this->smtp = $smtp;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function getBody($texto, $message) {

        return '<html>
                    <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                      <title></title>
                    </head>
                    <body>
                      <div >
                        <fieldset>
                          <div align="left">   
                                 <img src="' . $message->embed(\Swift_Image::fromPath('images/encabezado_mail_izq.png')) . '" alt="encabezadoizquierda" align="left">

                                 <img src="' . $message->embed(\Swift_Image::fromPath('images/encabezado_email_dpout.png')) . '" alt="encabezadoderecha" align="right">
                            
                        </div>
                        <br><br><br><br><br><br>

                        <div style=" font-family: Arial, Helvetica, sans-serif; font-size: 16px;">
                           
                           ' . $texto . '
                          
                        </div>

                        <br>
                        <hr>
                        <div style="text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                        <p>Este e-mail es enviado desde una casilla autom&aacute;tica. Por favor no lo responda.
                        Si recibi&oacute; este correo electr&oacute;nico por error, es probable que otro usuario haya ingresado su 
                        direcci&oacute;n de correo electr&oacute;nico por equivocaci&oacute;n mientras intentaba registrarse. 
                        Si no fue Ud. quien envi&oacute; la solicitud, no es necesario que tome ninguna medida y puede ignorar este correo electr&oacute;nico sin problemas.</p>    
                        <br>
                        </div>
                        <p style="text-align: center;">Ministerio de Gobierno - Direcci&oacute;n de Inform&aacute;tica y Tecnolog&iacute;as de la Informaci&oacute;n</p> 

                        </fieldset>

                      </div>
                    </body>
                    </html>';
    }

}
