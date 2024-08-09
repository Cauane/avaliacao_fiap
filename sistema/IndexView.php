<?php
    namespace sistema;

    class IndexView {
        public static function createHeader() {
            return '
                <div class="container-header">
                    <div class="util">
                        <div class="header">
                            ' . self::createLogo() . '
                            ' . self::createMenu() . '
                        </div>
                    </div>
                </div>
            ';
        }

        private static function createLogo() {
            return '
                <div class="logo">
                    <a href="home">
                        <img src="images/logo.jpg" alt="Logo FIAP" class="w-full">
                    </a>
                </div>
            ';
        }

        private static function createMenu() {
            return '
                <div class="menu">
                    <div>
                        <a href="home">Home</a>
                    </div>
                    <div class="divider"></div>
                    <div>
                        <a href="alunos">Alunos</a>
                    </div>
                    <div class="divider"></div>
                    <div>
                        <a href="turmas">Turmas</a>
                    </div>
                    <div class="divider"></div>
                    <div>
                        <a href="matriculas">Matrículas</a>
                    </div>
                    <div class="divider"></div>
                    <div>
                        <a href="logout">Sair do sistema</a>
                    </div>
                </div>
            ';
        }

        public static function createFooter() {
            return '
                <div class="container-footer">
                    <div class="util">
                        <div class="footer">
                            ' . self::createLogo() . '
                            ' . self::createCopyright() . '
                        </div>
                    </div>
                </div>
            ';
        }

        private static function createCopyright() {
            return '
                <div class="Copyright">
                    Todos os direitos reservados.
                </div>
            ';
        }

    }

?>