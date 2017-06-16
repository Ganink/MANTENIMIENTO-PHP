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
            <a class="navbar-brand" href="#">Administración de Notas</a>
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
if (isset($_POST["opcion"]))
    $t = $_POST["opcion"];
else
    $t = "Seleccione un Curso";
?>
<h2 style="text-align: center; "><?php echo "$t"?></h2>
<div>
    <form action="notas.php" method="post">
        <select title="opcion" class="form-control navbar-center" onchange="this.form.submit()" id="opcion" name="opcion" >
            <option value="%">Seleccione un Curso</option>
                <?php
                    $db = mysqli_connect("localhost","root","","ocupacional2");
                    $db->set_charset("UTF8");

                    $sql = $db->query("SELECT DESCRIPCION FROM cursos");
                    while($list=mysqli_fetch_array($sql)){
                        echo "<option value='".$list['DESCRIPCION']."'>".$list['DESCRIPCION']."</option>";
                    }
                ?>
        </select>
        <table class="table table-striped table-hover">
            <tr>
                <th>COD CURSO</th>
                <th>COD ALUMNO</th>
                <th>NOMBRE</th>
                <th>APELLIDO</th>
                <th>NOTA 1</th>
                <th>NOTA 2</th>
                <th>NOTA 3</th>
                <th></th>
                <th></th>
            </tr>
            <?php
                if (isset($_POST["opcion"])){
                    $opcion = $_POST["opcion"];
                }else{
                    $opcion = "%";
                }

                $resul = $db->query("SELECT a.cod_cur, a.cod_alu, a.nombre, a.apellidos, n.nota1, n.nota2, n.nota3
                            FROM alumnos as a 
                            join notas as n on a.cod_alu = n.cod_alu 
                            join cursos as c on c.cod_cur = a.cod_cur
                            WHERE c.descripcion like '".$opcion."';");

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
                WHERE a.cod_cur like ''
                ORDER by a.cod_alu DESC;";
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

            ?>
        </table>
    </form>
</div>

<div>
    <a href="index.php"><p>Volver al menú</p></a>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>