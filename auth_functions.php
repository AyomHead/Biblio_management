<?php
// C’est la vérification des valeurs des entrées lors de la conexion ou d’une inscription
function areAvalaible(...$values){
    foreach($values as $value){
        if(!isset($value) || empty(trim($value))){
            return false;
        }
    }
    return true;
}

// Fonction pour la connexion de l’utlisateur
function logedInUser($user){
    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['role'] = $user['role'];
}
?>