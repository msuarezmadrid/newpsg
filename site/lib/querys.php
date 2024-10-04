<?php 
    $queries = [
        "qry_login" => "SELECT * FROM users WHERE USER=':user' AND CLAVE=':pass' AND ELIMINADO IS NULL ",
    ];