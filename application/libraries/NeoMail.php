<?php
/* 
 * Mail Service
 * @author Bardas Catalin
 */

class NeoMail {

  

    public static function check_email($email) {

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function genericMail($body, $subject, $email) {
     
        if(!self::check_email($email)){
            echo "invalid email: ".$email."<br/>";
            return false;
        }
        
        require_once 'Swift_ssl/swift_required.php';
        $transport = \Swift_SmtpTransport::newInstance('helpie.ro', 25)
                ->setUsername('helpie38')
                ->setPassword('gpqykNbQ');
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance($subject)
                ->setContentType("text/html")
                ->setFrom(array(App_constants::$OFFICE_EMAIl => App_constants::$WEBSITE_COMMERCIAL_NAME))
                ->setTo($email)
                ->setBody($body);
        $result = $mailer->send($message);
    }

    public static function genericMailAttach($body, $subject, $email, $attach) {
        if (self::check_email($email)) {
            require_once 'Swift_ssl/swift_required.php';
            $transport = \Swift_SmtpTransport::newInstance('', 25)
                    ->setUsername('dev')
                ->setPassword('123getadeal456');
            $mailer = \Swift_Mailer::newInstance($transport);
            $message = \Swift_Message::newInstance($subject)
                    ->setContentType("text/html")
                    ->setFrom(array('' =>  DLConstants::$WEBSITE_COMMERCIAL_NAME))
                    ->setTo(array($email))
                    ->setBody($body);

            foreach ($attach as $key => $att) {
                $message->attach(\Swift_Attachment::fromPath($attach[$key]));
            }

            $result = $mailer->send($message);
        } else {
            //invalid email
        }
    }

}

?>
