    </div>
    <footer class="text-right">
        <hr>
        <p><strong>Desarrollado por System7x</strong></p>
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
<<<<<<< HEAD
=======
            case 'jquery-treeview';
                echo '<script type="text/javascript" language="javascript" src="lib/js/jquery-treeview-master/jquery.treeview.js"></script>';
                break;
            case 'chosen';
                echo '<script type="text/javascript" language="javascript" src="lib/js/chosen_v1.8.7/chosen.jquery.min.js"></script>';
                break;
>>>>>>> refs/remotes/origin/main
            case 'highcharts';
                echo '<script type="text/javascript" language="javascript" src="lib/js/Highcharts-7.0.0/code/highcharts.js"></script>';
                echo '<script type="text/javascript" language="javascript" src="lib/js/Highcharts-7.0.0/code/modules/exporting.js"></script>';
                echo '<script type="text/javascript" language="javascript" src="lib/js/Highcharts-7.0.0/code/modules/export-data.js"></script>';
                break;
        }
    }

    ?>
    <script type="text/javascript" language="javascript" src="js/system.js?v=<?php echo $parametro['webversion']; ?>"></script>
    <script type="text/javascript" language="javascript" src="js/<?php echo $pagina; ?>.js?v=<?php echo $parametro['webversion']; ?>"></script>
</html>