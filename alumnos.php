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
echo "<table class='table-condensed'; style='text-align: center;'> ";
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
        echo "<td><button type='submit' name='modificar' value='modificar'>Modificar</button> </td>";
        echo "<td><button type='submit' name='borrar' value='$fila[0]'>Borrar</button> </td>";
        echo "</tr>";

        $_SESSION['ca'] = $fila[0];
        $_SESSION['cc'] = $fila[1];
        $_SESSION['dni'] = $fila[2];
        $_SESSION['ape'] = $fila[3];
        $_SESSION['nom'] = $fila[4];
    }

    if (isset($_POST['modificar'])){
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

        echo "Alumno borrado correctamente";
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


    $query = "UPDATE ALUMNOS SET COD_CUR = ? WHERE COD_CUR = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('sss', $cod_cur, $dni, $apellido, $nom);    // Enlazamos 4 parámetros
    $stmt->execute();

    if ($stmt->affected_rows > 0)   // Número de filas insertadas
    {
        echo  "<p>Se ha añadido correctamente el Alumno.</p>";
    } else
    {
        echo "<p>No se ha podido añadir el Alumno.</p>";
    }
}

    if (isset($_POST['guardar'])){
        $ca = $_POST['ca'];
        $cc = $_POST['cc'];
        $dni = $_POST['dni'];
        $apellido = $_POST['apellido'];
        $nm = $_POST['nm'];

        $n1 = 0;
        $n2 = 0;
        $n3 = 0;
        $med = 0;

        $query = "INSERT INTO ALUMNOS VALUES (?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssss', $ca, $cc, $dni, $apellido, $nm);    // Enlazamos 4 parámetros
        $stmt->execute();
        $query2 = "INSERT INTO NOTAS VALUES (?,?,?,?,?,?)";
        $stmt=$db->prepare($query2);
        $stmt->bind_param('ssdddd', $ca, $cc, $n1, $n2, $n3, $med);
        $stmt->execute();

 /*MODIFICAR CODIGO PARA AÑADIR NOTAS AUTOMATICAMENTE Y ESTABLECERLAS A 0*/
        if ($stmt->affected_rows > 0)   // Número de filas insertadas
        {
            echo  "<p>Se ha añadido correctamente el Alumno.</p>";
        } else
        {
            echo "<p>No se ha podido añadir el Alumno.</p>";
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