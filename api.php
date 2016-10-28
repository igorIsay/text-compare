<?php

require 'application.php';

$app = new Application($_POST["text1"], $_POST["text2"]);
$app->run();
echo json_encode($app->getResult());























