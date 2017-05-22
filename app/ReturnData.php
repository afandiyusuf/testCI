<?php

namespace App;

class ReturnData
{
   public $message = "unset";
   public $code = 200;
   public $data = "unset";

   public function set($message,$code,$data)
   {
      $this->message = $message;
      $this->code = $code;
      $this->data = $data;
   }
}