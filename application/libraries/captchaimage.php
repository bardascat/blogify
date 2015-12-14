<?php
session_start();
class Captchaimage {
   var $CI;
   
   function __construct() {
      $this -> CI = &get_instance();
      $this -> CI -> load -> model('accesmodel');
   }

   function generateCode($characters) {
      /* list all possible characters, similar looking characters and vowels have been removed */
      $possible = '23456789qwertyuioasdfghjk';
      $code = '';
      $i = 0;
      while ($i < $characters) {
         $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
         $i++;
      }
      return $code;
   }

   function generateImage($width = '250', $height = '50', $characters = '5') {

      $font = 'centaur.ttf';
      $code = $this -> generateCode($characters);

      $font_size = $height * 0.75;
      $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');

      $background_color = imagecolorallocate($image, 255, 255, 255);
      $text_color = imagecolorallocate($image, 20, 40, 100);
      $noise_color = imagecolorallocate($image, 100, 120, 180);

      for ($i = 0; $i < ($width * $height) / 3; $i++) {
         imagefilledellipse($image, mt_rand(0, $width), mt_rand(0, $height), 1, 1, $noise_color);
      }

      for ($i = 0; $i < ($width * $height) / 150; $i++) {
         imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $noise_color);
      }

      $textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
      $x = ($width - $textbox[4]) / 2;
      $y = ($height - $textbox[5]) / 2;
      imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code) or die('Error in imagettftext function');

      header('Content-Type: image/jpeg');
      imagejpeg($image);
      imagedestroy($image);
      //$_SESSION['security_code'] = $code;
      $this->CI->session->set_userdata('security_code', $code);
      //set_cookie('security_code', $code, '', '', '', '' );
   }

}
