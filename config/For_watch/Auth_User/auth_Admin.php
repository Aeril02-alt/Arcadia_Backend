<?php
session_start();
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    echo "Vous n'êtes pas autorisé à accéder à cette page.";
    exit();
}