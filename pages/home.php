<?php

use sistema\Login\LModel;

LModel::validateLogin();

echo '
    <div class="util">
        <div class="welcome"><span>Bem-vindo</span>, '.$_SESSION['userLogin'].'!</div>    
    </div>

';

?>