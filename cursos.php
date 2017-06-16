<html>
<head>
    <title> curso.php proceso de cursos</title>
    <META http-equiv="content-type" content="text/html" charset="utf-8">
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
            <a class="navbar-brand" href="#">Administración de Cursos</a>
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
    session_start();
    $cod_cur="";
    $desc="";
    $horas="";
    $tutor="";

    $db = mysqli_connect("localhost","root","","ocupacional2");

    if ($db == false){
        echo "Error, no se ha podido conectar";
        exit();
    }

    else{
        $db->set_charset("UTF8");
        $query = "select * from cursos";
        $resul = mysqli_query($db, $query);

        $num = mysqli_num_rows($resul);

        if (isset($_SESSION['valor'])) {
            if (isset($_POST['siguiente']))
                if ($_SESSION['valor'] < $num - 1)
                    $_SESSION['valor']++;
            if (isset($_POST['anterior']))
                if ($_SESSION['valor'] > 0)
                    $_SESSION['valor']--;
            if (isset($_POST['primero']))
                if ($_SESSION['valor'])
                    $_SESSION['valor'] = 0;
            if (isset($_POST['ultimo']))

                    $_SESSION['valor'] = $num-1;
            mysqli_data_seek($resul, $_SESSION['valor']);
        }
        else{
            $_SESSION['valor'] = 0;
        }

        if (isset($_POST['grabar'])){
            $cod_cur=$_POST["cc"];
            $desc=$_POST["dc"];
            $horas=$_POST["hr"];
            $tutor=$_POST["tt"];

            $query = "INSERT INTO cursos VALUES (?, ?, ?, ?)";   // Consulta con 4 parámetros
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssis', $cod_cur, $desc, $horas, $tutor);    // Enlazamos 4 parámetros
            $stmt->execute();

            if ($stmt->affected_rows > 0)   // Número de filas insertadas
            {
                echo  "<p>Se ha añadido correctamente el curso.</p>";
            } else
            {
                echo "<p>No se ha podido añadir el curso.</p>";
            }
        }

        if (isset($_POST['borrar'])){
            $cod_cur = $_POST['cc'];
            $stmt = $db->prepare("delete from cursos where COD_CUR=?");
            $stmt->bind_param('s', $cod_cur);
            $stmt->execute();

            echo "Curso borrado correctamente";
        }

        $row=mysqli_fetch_row($resul);
        if (isset($_POST['nuevo'])){
            $cod_cur="";
            $desc="";
            $horas="";
            $tutor="";
        }
        else{
            $cod_cur=$row[0];
            $desc=$row[1];
            $horas=$row[2];
            $tutor=$row[3];
        }
        mysqli_close($db);
    }

    ?>

    <div style="border: 3px solid #222; margin: 1%; padding: 1%;">
        <form action="cursos.php" method="post">
            <label for="Curso">Curso: <input type="text" name="cc" value="<?php echo $cod_cur; ?>"</label><br/>
            <label for="Descripcion">Descripción: <input name="dc" type="text" value="<?php echo $desc; ?>"</label><br/>
            <label for="Horas">Horas: <input type="text" name="hr" value="<?php echo $horas; ?>"</label><br/>
            <label for="Tutor">Tutor: <input type="text" name="tt" value="<?php echo $tutor; ?>"</label><br/>

            <br/>
            <INPUT TYPE="submit" name="primero" value="<|">
            <INPUT TYPE="submit" name="anterior" value="<<">
            <INPUT TYPE="submit" name="siguiente" value=">>">
            <INPUT TYPE="submit" name="ultimo" value="|>">
            <INPUT TYPE="submit" name="grabar" value="Grabar">
            <INPUT TYPE="submit" name="nuevo" value="Nuevo">
            <INPUT TYPE="submit" name="borrar" value="Borrar">
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