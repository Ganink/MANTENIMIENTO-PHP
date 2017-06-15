<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Notas</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
            <a class="navbar-brand" href="#">Administración de notas</a>
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

<h1>MANTENIMIENTO DE NOTAS</h1>
<div>
    <form action="notas.php" method="post">
        <select>
            <option>Seleccione un Curso</option>
            <option>PROGRAMACION</option>
            <option>INGLES</option>
            <option>BASE DE DATOS</option>
            <option>LENGUAJE DE MARCAS</option>
            <option>SISTEMAS</option>
        </select>



        <?php
        $db = mysqli_connect("localhost","root","","ocupacional2");

        $ca = "";
        $cc="";
        $dni="";
        $apellido="";
        $nm="";

        $query = "SELECT a.cod_cur, a.cod_alu, a.nombre, a.apellidos, n.nota1, n.nota2, n.nota3
        FROM alumnos as a join notas as n on a.cod_alu = n.cod_alu";
        $resul = mysqli_query($db, $query);

        $num = mysqli_num_rows($resul);
        echo "<table class='table'> ";
        echo "<tr>";
        echo "<th>COD CURSO</th>";
        echo "<th>COD ALUMNO</th>";
        echo "<th>NOMBRE</th>";
        echo "<th>APELLIDO</th>";
        echo "<th>NOTA 1</th>";
        echo "<th>NOTA 2</th>";
        echo "<th>NOTA 3</th>";
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
            echo "<td>$fila[5]</td>";
            echo "<td>$fila[6]</td>";
            echo "<td><button type='submit' name='modificar' value='$fila[0]'>Modificar</button> </td>";
            echo "<td><button type='submit' name='borrar' value='$fila[0]'>Borrar</button> </td>";
            echo "</tr>";


            $_SESSION['cc'] = $fila[0];
            $_SESSION['ca'] = $fila[1];
            $_SESSION['nom'] = $fila[2];
            $_SESSION['ape'] = $fila[3];
            $_SESSION['n1'] = $fila[4];
            $_SESSION['n2'] = $fila[5];
            $_SESSION['n3'] = $fila[6];
        }

        if (isset($_POST['modificar'])){
            $cod_alu = $_POST['modificar'];
            $query = "SELECT a.cod_alu, a.cod_cur, a.apellidos, a.nombre, n.nota1, n.nota2, n.nota3, n.media 
        FROM alumnos as a join notas as n on a.cod_alu = n.cod_alu 
        WHERE a.cod_cur like '';";
            $resul = mysqli_query($db, $query);

            $cc = $_SESSION['cc'];
            $ca = $_SESSION['ca'];
            $nom = $_SESSION['nom'];
            $ape = $_SESSION['ape'];
            $n1 = $_SESSION['n1'];
            $n2 = $_SESSION['n2'];
            $n3 = $_SESSION['n3'];
            echo "<br/>";
            echo "<label for=\"NOMBRE\">NOMBRE: <input type='text' name='nom' value='$nom'></label>";
            echo "<label for=\"NOMBRE\">APELLIDO: <input type='text' name='ape' value='$ape'></label>";
            echo "<label for=\"NOMBRE\">NOTA 1: <input type='text' name='n1' value='$n1'></label>";
            echo "<label for=\"NOMBRE\">NOTA 2: <input type='text' name='n2' value='$n2'></label>";
            echo "<label for=\"NOMBRE\">NOTA 3: <input type='text' name='n3' value='$n3'></label>";
            echo "<button type='submit' name='guarda'>Guardar</button>";
        }
        if(isset($_POST['borrar'])){
            $cc = $_SESSION['cc'];
            $ca = $_SESSION['ca'];
            $n1 = 0;
            $n2 = 0;
            $n3 = 0;
            $med = 0;

            $sql = "update notas set nota1 = %d, nota2 = %d, nota3 = %d, media = %d where cod_alu = '%s'";
            $query = sprintf($sql, $n1, $n2, $n3, $med, $ca);
            $resultado = $db->query($query);
        }

        if (isset($_POST['guarda'])){
            $cc = $_SESSION['cc'];
            $ca = $_SESSION['ca'];
            $n1 = $_POST['n1'];
            $n2 = $_POST['n2'];
            $n3 = $_POST['n3'];
            $med = ($n1+$n2+$n3)/3;

            $sql = "update notas set nota1 = %d, nota2 = %d, nota3 = %d, media = %d where cod_alu = '%s'";
            $query = sprintf($sql, $n1, $n2, $n3, $med, $ca);
            $resultado = $db->query($query);
        }

        echo "</table>";
        ?>
    </form>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>