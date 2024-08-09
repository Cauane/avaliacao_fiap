<?php

namespace sistema\Login;

use sistema\GlobalView;

class LModel {
    const LOGIN_DEFAULT = 'admin';
    const SENHA_DEFAULT = 'admin';
    const ID_USER_DEFAULT = 1;

    public static function formValidation ($login, $senha, &$error) {
        if (empty($login)) {
            $error = GlobalView::createErrorAlert('O campo Login é obrigatório!');
            return;
        }

        if (empty($senha)) {
            $error = GlobalView::createErrorAlert('O campo Senha é obrigatório!');
            return;
        }

        if ($senha != self::SENHA_DEFAULT || $login != self::LOGIN_DEFAULT) {
            $error = GlobalView::createErrorAlert('Login ou Senha inválido!');
            return;
        }

        $_SESSION["userId"] = self::ID_USER_DEFAULT;
        $_SESSION["userLogin"] = self::LOGIN_DEFAULT;

        header("Location: home"); 
        exit;
       
    }

    /* valida se o user está logado */
    public static function validateLogin() {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION["userId"])) {
            session_destroy();
            header("Location: index.php"); 
            exit;
        }

    }
}

?>