<?php
// une petite fonction pour vérifier si l’utilisateur est connecté
function isConnected(){
    return isset($_SESSION['email']) && !empty($_SESSION['email']);
}
?>