<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\stylesReporteDocente.css">
    <title>Teacher Data</title>
</head>
<body>
    <div class="encabezado">
        <div class="parteEncabezado"><img class="img" width="100" src="img\imagen1.png" alt=""></div>
        <div class="parteEncabezado">
            <div class="tituloEncabezado"><h2>SEMINARIO MAYOR «PÍO XII»<br><span id="subtitulo">DIÓCESIS DE SAN VICENTE,<br>EL SALVADOR, C.A.</span></h2></div>
        </div>
        <div class="parteEncabezado"><img class="img" width="100" src="img\imagen2.jpg" alt=""></div>
    </div>
    <div class="cuerpoReporte">
        <div class="tituloCuerpo"><h3>REPORTE DE USUARIO</h3></div>
        <div class="detalle"><b>Nombre:</b> {{$teacherName}}</div><br>
        <div class="detalle"><b>Usuario: </b>{{$userName}}</div><br>
        <div class="detalle"><b>Contraseña: </b>{{$pass}}</div><br>
    </div>
    <p>Ingresar al sitio web www.sistemanotas.com utilizando las credenciales asignadas. Se recomienda cambiar la contraseña al primer ingreso.</p>
</body>
</html>