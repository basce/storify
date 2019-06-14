<?php
namespace noisycrayons\phpemail;

include_once("inc/class.phpmailer.php");
include_once("inc/class.smtp.php");
include_once("inc/class.pop3.php");

class mailer{

    private $mail; 
    function __construct(){
        $this->mail = new \PHPMailer();
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'quoted-printable';
    }

    private static $storedvalue = array();

    public static function setSenderAndDKIM($sender_name, $sender_email, $use_SMTP, $SMTP_ENDPOINT, $SMTP_PORT, $SMTP_USERNAME, $SMTP_PASSWORD, $DKIM_domain, $DKIM_privatekey, $DKIM_selector, $DKIM_passphrase=""){
        self::$storedvalue = array(
            "sender_name"=>$sender_name,
            "sender_email"=>$sender_email,
            "use_SMTP"=>$use_SMTP,
            "SMTP_ENDPOINT"=>$SMTP_ENDPOINT,
            "SMTP_PORT"=>$SMTP_PORT,
            "SMTP_USERNAME"=>$SMTP_USERNAME,
            "SMTP_PASSWORD"=>$SMTP_PASSWORD,
            "DKIM_domain"=>$DKIM_domain,
            "DKIM_privatekey"=>$DKIM_privatekey,
            "DKIM_selector"=>$DKIM_selector,
            "DKIM_passphrase"=>$DKIM_passphrase
        );
    }

    public static function echoStoredValue(){
        print_r(self::$storedvalue);
    }

    private function parse_email_template($t_file, $replace){
        $fd = @fopen ($t_file, "r") or die(__FILE__." , ". __LINE__. " Can't open file $t_file");
        $content = @fread ($fd, filesize ($t_file)) or 
        die(__FILE__." , ". __LINE__. " Can't open file $t_file");
        @fclose ($fd);
    
        $content = preg_replace_callback("/%%([A-Za-z0-9_ ]+)%%/", function($matches) use ($replace){
            return isset($replace[$matches[1]])?$replace[$matches[1]]:'';
        },$content);

        $content = preg_replace_callback("/%%([A-Za-z0-9_ ]+)%%/", function($matches) use ($replace){
            return isset($replace[$matches[1]])?$replace[$matches[1]]:'';
        },$content);
    
        return $content;
    }  

    public function sendEmail($receiver, $title, $content, $emailtemplate, $cc='', $bcc='', $attachment=false){
        
        if(sizeof(self::$storedvalue) == 0){
            die("please setup sender and DKIM with setSenderAndDKIM");
        }

        $debug_msg = array();

        $debug_msg[] = self::$storedvalue;

        if(self::$storedvalue["use_SMTP"]){
            $this->mail->IsSMTP(); 
            $this->mail->SMTPAuth = true;
            $this->mail->Host = self::$storedvalue["SMTP_ENDPOINT"];
            $this->mail->Port = self::$storedvalue["SMTP_PORT"];
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Username = self::$storedvalue["SMTP_USERNAME"];
            $this->mail->Password = self::$storedvalue["SMTP_PASSWORD"];
        }

        $this->mail->From = self::$storedvalue["sender_email"];
        $this->mail->FromName = self::$storedvalue["sender_name"];

        if($cc!=''){
            $additional_emails = explode(",", $cc);
            foreach($additional_emails as $value){
                $this->mail->AddCC($value);
            }
        }

        if($bcc!=''){
            $additional_emails = explode(",", $bcc);
            foreach($additional_emails as $value){
                $this->mail->AddBCC($value);
            }
        }

        $this->mail->AddAddress($receiver["email"], $receiver["name"]);
        $this->mail->Subject = '=?utf-8?B?'.base64_encode($title).'?=';

        $this->mail->isHTML(true);
        $this->mail->MsgHTML($this->parse_email_template($emailtemplate, $content));

        if($attachment){
            if(is_array($attachment)){
                foreach($attachment as $key=>$value){
                    $this->mail->AddAttachment($value);
                }
            }else{
                $this->mail->AddAttachment($attachment);
            }
        }

        //setup DKIM
        if(!self::$storedvalue["use_SMTP"]){
            if(self::$storedvalue["DKIM_domain"]){
                $this->mail->DKIM_domain = self::$storedvalue["DKIM_domain"];
                $this->mail->DKIM_private = self::$storedvalue["DKIM_privatekey"];
                $this->mail->DKIM_selector = self::$storedvalue["DKIM_selector"];
                $this->mail->DKIM_passphrase = self::$storedvalue["DKIM_passphrase"];
                $this->mail->DKIM_identifier = $this->mail->From;
            }
        }

        try{
            if($this->mail->Send()) {
                return array("error"=>0, "msg"=>"email sent to ".$receiver["email"]." bcc :".$bcc." cc:".$cc." Debug :".json_encode($debug_msg));
            }else{
                return array("error"=>1, "msg"=>$this->mail->ErrorInfo." Debug :".json_encode($debug_msg));
            }
        }catch(Exception $e){
            return array(
                $debug_msg,
                $e
            );
        }
    }
}
?>


