<?php
// Conexión a la base de datos
include("conexion.php");

// Verificar si se ha enviado el formulario con el correo electrónico
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correoElectronico'])) {
    $correoElectronico = $_POST['correoElectronico'];

    // Consulta SQL para obtener los datos del usuario con el correo electrónico especificado
    $sql = "SELECT p.nombre, p.apellido, u.correoelectronico, p.genero, p.tipocuenta, p.codigo_alumno, p.centro, u.estado FROM personas p INNER JOIN Usuarios u ON p.iduser = u.id WHERE u.correoelectronico = ?";
    
    // Sanitizar la entrada y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correoElectronico);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $result->fetch_assoc();
    }
}

// Consulta SQL para obtener todos los usuarios
$sql_all_users = "SELECT p.nombre, p.apellido, u.correoelectronico, p.genero, p.tipocuenta, p.codigo_alumno, p.centro, u.estado FROM personas p INNER JOIN Usuarios u ON p.iduser = u.id";
$result_all_users = $conexion->query($sql_all_users);

// Verificar si hay resultados
if ($result_all_users->num_rows > 0) {
    // Arreglo para almacenar los datos de todos los usuarios
    $usuarios = array();
    // Iterar sobre los resultados de la consulta y almacenarlos en el arreglo
    while($row = $result_all_users->fetch_assoc()) {
        $usuarios[] = $row;
    }
} else {
    echo "No se encontraron resultados.";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Referencia para AwesomeFont-->
    <link href="/your-path-to-fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="/your-path-to-fontawesome/css/brands.css" rel="stylesheet">
    <link href="/your-path-to-fontawesome/css/solid.css" rel="stylesheet">
    <!-- Favicon-->
    <link rel="icon" href="src-ico/empreverest.svg" type="image/x-icon">

</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="DashboardAdm.html"><img src="src-ico/empreverest.svg" alt="Icono">EMPREVEREST</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars" style="color: #0b6380;"></i></button>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button" style="background-color: #0b6380; border-color: #0b6380;"><i class="fas fa-search"></i></button>

        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Menú</div>
                    <a class="nav-link" href="DashboardAdm.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="color: #0b6380;"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Interface</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-table" style="color: #0b6380;"></i></div>
                        Administración
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #0b6380;"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="DashboardAdmin.php">Usuarios registrados</a>
                            <a class="nav-link" href="Registros.php">Tabla de registros</a>
                            <a class="nav-link" href="Search.php">Búsqueda de usuarios</a>
                        </nav>
                    </div>
                    
                                     
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                EMPREVEREST
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
            <div class="col-md-10">
                    <h1 class="mt-4">Bienvenido</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Busqueda de usuarios</li>
                    </ol> 
                </div>       
                
                <hr>
                <!-- Agregar un formulario para ingresar el correo electrónico y buscar -->
                    <form method="post" action="">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="correoElectronico" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correoElectronico" name="correoElectronico">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100" style="background-color: #0b6380; border-color: #0b6380;" >Buscar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- Mostrar los datos del usuario si se ha encontrado -->
                    <?php if (!empty($usuario)) : ?>
                    <div class="row">
                        <table  id="myTable" class="table align-middle mb-0 bg-white">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Correo electrónico</th>
                                    <th>Género</th>
                                    <th>Tipo de cuenta</th>
                                    <th>Código de alumno</th>
                                    <th>Centro</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $usuario['nombre'] ?></td>
                                    <td><?= $usuario['apellido'] ?></td>
                                    <td><?= $usuario['correoelectronico'] ?></td>
                                    <td><?= $usuario['genero'] ?></td>
                                    <td><?= $usuario['tipocuenta'] ?></td>
                                    <td><?= $usuario['codigo_alumno'] ?></td>
                                    <td><?= $usuario['centro'] ?></td>
                                    <td><?= $usuario['estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>

                
                <hr>
            
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Empreverest 2023</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable();
  });
</script>



</body>
</html>
