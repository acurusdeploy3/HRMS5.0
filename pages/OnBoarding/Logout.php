<?php
   session_start();
   
   if(session_destroy()) {
      header("Location: ../forms/MainLogin.php");
   }
?>