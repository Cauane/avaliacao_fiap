<?php 
    include 'sistema/IndexView.php';

    use sistema\IndexView;

    echo IndexView::createHeader();
    
    echo '<div class="content">';
        $dir = 'pages/';
        $ext = '.php';
        $prm = array();
        $url = ( isset($_GET['page']) ) ? $_GET['page'] : 'home';
        if ( substr_count($url, '/') > 0 ){
        $atual = explode('/', $url);
        $page  = ( file_exists( $dir . $atual[0] . $ext ) ) ? $atual[0] : 'help';
        }else{
        $page  = ( file_exists( $dir . $url . $ext ) ) ? $url : 'help';
        }
        include( $dir . $page . $ext );
    echo '</div>';	

    echo IndexView::createFooter();
?>

</body>
</html>

