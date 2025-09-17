<?php
function isConnected() {
    return isset($_SESSION['id']) && isset($_SESSION['role']);
}

function isAdmin() {
    return isConnected() && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isConnected() && $_SESSION['role'] === 'user';
}
?>