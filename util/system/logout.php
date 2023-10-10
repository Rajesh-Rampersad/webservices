<?php

include("ession.php");

$session = new Session();
$session->endSession();

header('Location: ../../');