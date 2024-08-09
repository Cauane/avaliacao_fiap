<?php

    include_once 'sistema/Aluno/AView.php';
    include_once 'sistema/Aluno/AController.php';
    include_once 'sistema/Aluno/AModel.php';

    use sistema\GlobalView;
    use sistema\Aluno\AView;
    use sistema\Login\LModel;

    LModel::validateLogin();

    echo '
        <div class="util">
            ' . GlobalView::createPageTitle('Alunos') . '
            ' . AView::createHtmlStudent() . '
        </div>
    ';

?>