<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Alumnos</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h1 style="text-align: center;">MANTENIMIENTO DE ALUMNOS</h1>
<form action="alumnos.php" method="post">
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Administración de Alumnos</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Inicio</a></li>
                    <li><a href="Cursos.php">Cursos</a></li>
                    <li><a href="Alumnos.php">Alumnos</a></li>
                    <li><a href="Notas.php">Notas</a></li>
                </ul>
            </div>
        </div>
    </nav>
<?php
$db = mysqli_connect("localhost","root","","ocupacional2");

$ca = "";
$cc="";
$dni="";
$apellido="";
$nm="";

$query = "select * from alumnos";
$resul = mysqli_query($db, $query);

$num = mysqli_num_rows($resul);
echo "<table class='table'> ";
    echo "<tr>";
        echo "<th>CODIGO ALUMNO</th>";
        echo "<th>CURSO</th>";
        echo "<th>DNI</th>";
        echo "<th>APELLIDO</th>";
        echo "<th>NOMBRE</th>";
        echo "<th></th>";
        echo "<th></th>";
    echo "</tr>";

    while($fila = $resul->fetch_array(MYSQLI_NUM)){
        echo "<tr>";
        echo "<td>$fila[0]</td>";
        echo "<td>$fila[1]</td>";
        echo "<td>$fila[2]</td>";
        echo "<td>$fila[3]</td>";
        echo "<td>$fila[4]</td>";
        echo "<td><button type='submit' name='modificar' value='$fila[0]'>Modificar</button> </td>";
        echo "<td><button type='submit' name='borrar' value='$fila[0]'>Borrar</button> </td>";
        echo "</tr>";

        $_SESSION['ca'] = $fila[0];
        $_SESSION['cc'] = $fila[1];
        $_SESSION['dni'] = $fila[2];
        $_SESSION['ape'] = $fila[3];
        $_SESSION['nom'] = $fila[4];
    }

    if (isset($_POST['modificar'])){
        $cod_alu = $_POST['modificar'];
        $query = 'SELECT * FROM ALUMNOS WHERE COD_ALU = $cod_alu';
        $resul = mysqli_query($db, $query);

        $ca=$_SESSION['ca'];
        $cc=$_SESSION['cc'];
        $dni=$_SESSION['dni'];
        $apellido=$_SESSION['ape'];
        $nom=$_SESSION['nom'];

        echo "<label for=\"CA\">CODIGO ALUMNO: <input type='text' name='ca' value='$ca' readonly></label>";
        echo "<label for=\"CC\">CODIGO CURSO: <input type='text' name='cc' value='$cc' readonly></label>";
        echo "<label for=\"DNI\">DNI: <input type='text' name='dni' value='$dni'></label>";
        echo "<label for=\"APELLIDO\">APELLIDO: <input type='text' name='apellido' value='$apellido'></label>";
        echo "<label for=\"NOMBRE\">NOMBRE: <input type='text' name='nom' value='$nom'></label>";
        echo "<button type='submit' name='guarda'>Guardar</button>";
        echo "</panel>";
    }
    if(isset($_POST['borrar'])){

        $ca = $_SESSION['ca'];
        $cc = $fila[1];

        $stmt = $db->prepare("delete from ALUMNOS where COD_ALU=?");
        $stmt->bind_param('s', $ca);
        $stmt->execute();

        $stmt = $db->prepare("delete from notas where COD_ALU=?");
        $stmt->bind_param('s', $ca);
        $stmt->execute();

        echo "<script>alert('Se ha borrado el alumno y sus notas correctamente.')</script>";
    }

    if (isset($_POST['nuevo'])){
        echo "<panel>";
            echo "<label for=\"CA\">CODIGO ALUMNO: <input type='text' name='ca' value='$ca'></label>";
            echo "<label for=\"CC\">CODIGO CURSO: <input type='text' name='cc' value='$cc'></label>";
            echo "<label for=\"DNI\">DNI: <input type='text' name='dni' value='$dni'></label>";
            echo "<label for=\"APELLIDO\">APELLIDO: <input type='text' name='apellido' value='$apellido'></label>";
            echo "<label for=\"NOMBRE\">NOMBRE: <input type='text' name='nm' value='$nm'></label>";
            echo "<button type='submit' name='guardar'>Guardar</button>";
        echo "</panel>";
    }

    if (isset($_POST['guarda'])){
    $dni = $_POST['dni'];
    $apellido = $_POST['apellido'];
    $nom = $_POST['nom'];
    $ca = $_SESSION['ca'];


    $sql = "update alumnos set dni= '%s', apellidos= '%s', nombre= '%s' where cod_alu = '%s'";
    $query = sprintf($sql, $dni, $apellido, $nom, $ca);
    $resultado = $db->query($query);
    if (!$resultado){
        echo "<script>alert('Se ha producido un error.')</script>";
        return(false);
    }
}

    if (isset($_POST['guardar'])){
        $ca = $_POST['ca'];
        $cc = $_POST['cc'];
        $dni = $_POST['dni'];
        $apellido = $_POST['apellido'];
        $nm = $_POST['nm'];

        $query = "INSERT INTO ALUMNOS VALUES (?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssss', $ca, $cc, $dni, $apellido, $nm);    // Enlazamos 4 parámetros
        $stmt->execute();

        if ($stmt->affected_rows > 0)   // Número de filas insertadas
        {
            $n1 = 0;
            $n2 = 0;
            $n3 = 0;
            $media = 0;

            $query = "insert into notas VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssdddd', $cc, $ca, $n1, $n2, $n3, $media);
            $stmt->execute();
            if ($stmt->affected_rows > 0){
                echo "<script>alert('Se ha creado el alumno y sus notas correctamente.')</script>";
            }else{
                echo "<script>alert('Se ha producido un error.')</script>";
            }
        } else
        {
            echo "<script>alert('Se ha producido un error.')</script>";
        }
    }

echo "</table>";
?>
    <div>
        <button type="submit" name="nuevo" value="nuevo">nuevo alumno</button>
    </div>
</form>

<div>
    <a href="index.php"><p>Volver al menú</p></a>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>