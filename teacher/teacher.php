<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo "Nie ste prihlasený";
    exit;
}

if ($_SESSION['role'] !== 'Učiteľ') {
    echo "Nemáte povolenia";
    exit;
}

