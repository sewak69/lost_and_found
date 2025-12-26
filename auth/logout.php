<?php
session_start();
session_destroy();

// redirect to dashboard/home page
header("Location: ../dashboard/index.php");
exit();
