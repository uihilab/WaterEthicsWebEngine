<?php

// Start a session within the local directory
session_start();

// ---- Connect to DB ----
include '../app/php/database.php';
// -----------------------

// General rest of application
include '../app/php/engine.php';

?>