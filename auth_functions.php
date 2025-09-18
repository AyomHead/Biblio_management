<?php
function areAvalaible(...$values){
    foreach($values as $value){
        if(!isset($value) || empty(trim($value))){
            return false;
        }
    }
    return true;
}

function logedInUser($user){
    $_SESSION['id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['name'] = $user['name']; // ← CETTE LIGNE ÉTAIT MANQUANTE !
    $_SESSION['role'] = $user['role'];
    
    // Ajouter également pour plus de sécurité
    session_regenerate_id(true);
}
?>