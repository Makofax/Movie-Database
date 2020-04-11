<?php

session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
//when we logout, we cant everything to be cleared.