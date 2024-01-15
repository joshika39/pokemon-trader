<?php

// Path: logout.php
// Do the logout

session_start();
session_destroy();

header("Location: index.php");