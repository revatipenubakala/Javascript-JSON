<?php
    session_start();

    header('Content-type: application/json; charset=utf-8');

    if ( !isset($_SESSION['chats']) ) 
    {
        $_SESSION['chats'] = array();
    }
    echo json_encode($_SESSION['chats']);