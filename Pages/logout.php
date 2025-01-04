<?php
session_start();
session_destroy();
header('Location: /labogon/index.php');
exit();
?>
