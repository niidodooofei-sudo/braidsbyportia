<?php
require_once 'includes/auth.php';
cms_logout();
header('Location: login.php'); exit;
