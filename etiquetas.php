<?php ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet"   href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <title>Generar códigos QR con PHP</title>
        <link rel="stylesheet" href="estilos.css">
        <script src="ajax_generate_code.js"></script>
    </head>

    <body class="">
        <div class="container" style="min-height:500px;">
            <div class="container">     
                <div class="row">
                    <h2>Generar códigos QR con PHP</h2>
                </div>
                
                <div class="col-md-3">
                    <form class="form-horizontal" method="post" id="codeForm" onsubmit="return false">
                        <div class="form-group">
                            <label class="control-label">Información : </label>
                            <input class="form-control col-xs-1" id="content" type="text" required="required">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nivel del código (ECC) : </label>
                            <select class="form-control col-xs-10" id="ecc">
                                <option value="H">H - Mejor</option>
                                <option value="M">M</option>
                                <option value="Q">Q</option>
                                <option value="L">L - Peor</option>                         
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tamaño : </label>
                            <input type="number" min="1" max="10" step="1" class="form-control col-xs-10" id="size" value="5">
                        </div>
                        <div class="form-group">
                            <label class="control-label"></label>
                            <input type="submit" name="submit" id="submit" class="btn btn-success" value="Generar código QR">
                        </div>
                    </form>
                </div>
                
                <div class="col-md-6">
                    <div class="showQRCode"></div>
                </div>
            </div>
        </div>
        </div>  
        <div class="insert-post-ads1" style="margin-top:20px;">

        </div>
        </div>
    </body>
</html>


