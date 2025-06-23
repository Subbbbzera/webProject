<?php
session_start();
session_unset();
session_destroy();

header("Location: /WebProject/includes/home.php");
exit;
