    </div>
    <footer class="text-right">
        <hr>
        <p><strong>Desarrollado por Neptuno. INC</strong></p>
    </footer>
</body>

    <?php

    for($f=0; $f < count($varAcceso['framework']); $f++){
        switch($varAcceso['framework'][$f]){
            case 'jquery';
                echo '<script type="text/javascript" language="javascript" src="lib/js/jquery/jquery-3.3.1.min.js"></script>';
                break;
            case 'bootstrap';
                echo '<script type="text/javascript" language="javascript" src="lib/css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>';
                break;
        }
    }

    ?>
    <script type="text/javascript" language="javascript" src="js/system.js?v=<?php echo $parametro['webversion']; ?>"></script>
    <script type="text/javascript" language="javascript" src="js/<?php echo $pagina; ?>.js?v=<?php echo $parametro['webversion']; ?>"></script>
</html>