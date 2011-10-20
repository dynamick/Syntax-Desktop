<?php
  session_start();

  if (isset($_COOKIE[session_name()])) { //distruggo il cookie di sessione
    setcookie(session_name(), '', time()-42000, '/');
  }
  session_destroy(); // elimino tutti i dati in sessione

  header("location: ../../");
?>
