<?php

namespace sistema\Login;

use sistema\GlobalView;
use sistema\Login\LModel;

class LView {
    public static function createHtml () {
        $classButton = ['btn','btn-primary','w-full'];

        $error = '';
        if (!empty($_POST) AND (!empty($_POST["inp-login"]) OR !empty($_POST["inp-pass"]))) {
            LModel::formValidation($_POST["inp-login"], $_POST["inp-pass"], $error);
        }

        return '
            <div class="container-login flex-center">
                ' . $error . '
                <form action="index.php" method="post" onsubmit="return login.formValidation();">
                    <div class="form flex-center">
                        ' . self::createLogo() .'
                        ' . GlobalView::createInput('text', 'Login', 'inp-login', true) . '
                        ' . GlobalView::createInput('password', 'Senha', 'inp-pass', true) . '
                        ' . GlobalView::createButton($classButton, 'Entrar', 'submit') . '                  
                    </div>
                </form>
            </div>
        ';
    }

    private static function createLogo() {
        return '
            <div class="logo">
                <img src="images/logo.jpg" alt="Logo FIAP" class="w-full">
            </div>
        ';
    }
}


?>