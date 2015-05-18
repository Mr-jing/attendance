<?php
var_dump($_REQUEST, $_GET, $_POST);

var_dump(json_decode($_REQUEST['records'], true));