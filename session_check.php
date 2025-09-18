<?php
function isConnected() {
    return isset($_SESSION['id']);
}

function isAdmin() {
    return isConnected() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isConnected() && isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}
?>