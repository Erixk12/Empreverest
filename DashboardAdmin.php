<?php
// Conexión a la base de datos
include("conexion.php");

// Consulta SQL para obtener los datos
$sql = "SELECT p.nombre, p.apellido, u.correoelectronico, p.genero, p.tipocuenta, p.codigo_alumno, p.centro, u.estado, p.fotoperfil FROM personas p INNER JOIN Usuarios u ON p.iduser = u.id";
$result = $conexion->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Arreglo para almacenar los datos
    $usuarios = array();
    // Iterar sobre los resultados de la consulta y almacenarlos en el arreglo
    while($row = $result->fetch_assoc()) {
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


<style>
    .card-img {
            width: 80%; /* Ajuste del tamaño de la imagen */
            border-radius: 15px 0 0 15px; /* Bordes redondeados solo en la esquina izquierda */
        }
</style>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="Dashboard.html"><img src="src-ico/empreverest.svg" alt="Icono">EMPREVEREST</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#!">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Menú</div>
                    <a class="nav-link" href="Dashboard.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Interface</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Layouts
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="layout-static.html">Static Navigation</a>
                            <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                        Pages
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                Authentication
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="login.html">Login</a>
                                    <a class="nav-link" href="register.html">Register</a>
                                    <a class="nav-link" href="password.html">Forgot Password</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                Error
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="#">Ejemplo 1</a>
                                    <a class="nav-link" href="#">Ejemplo 2</a>
                                    <a class="nav-link" href="#">Ejemplo 3</a>
                                </nav>
                            </div>
                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link" href="charts.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Charts
                    </a>
                    <a class="nav-link" href="tables.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Tables
                    </a>
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
                <h1 class="mt-4">Bienvenido Juan</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>   
                <div class="row">
                <table id="myTable" class="table align-middle mb-0 bg-white">
            <thead class="bg-light">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo electrónico</th>
                    <th>Genero</th>
                    <th>Tipo de cuenta</th>
                    <th>Código de alumno</th>
                    <th>Centro</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Iterar sobre los datos almacenados en el arreglo y mostrarlos en la tabla
                foreach ($usuarios as $usuario) {
                    echo "<tr>";
                    echo "<td><img src='{$usuario['foto_perfil']}' alt='Foto de perfil' style='width: 45px; height: 45px;' class='rounded-circle'></td>";
                    echo "<td>{$usuario['nombre']}</td>";
                    echo "<td>{$usuario['apellido']}</td>";
                    echo "<td>{$usuario['correoelectronico']}</td>";
                    echo "<td>{$usuario['genero']}</td>";
                    echo "<td>{$usuario['tipocuenta']}</td>";
                    echo "<td>{$usuario['codigo_alumno']}</td>";
                    echo "<td>{$usuario['centro']}</td>";
                    echo "<td>{$usuario['estado']}</td>";
                    // Agregar columna para la foto de perfil
                    echo "<td><button type='button' class='btn btn-primary btn-rounded edit-btn' data-bs-toggle='modal' data-bs-target='#editModal' data-mdb-ripple-init>Edit</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
                </div>                      
            </div>
            
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
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="row">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4 text-center">
                            <!-- Campo para mostrar la foto de perfil -->
                            <img id="perfilImagen" src="#" alt="Foto de perfil" class="rounded-circle mb-3" style="max-width: 100%; height: auto; width: 150px; height: 150px;">
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fotoPerfil" class="form-label">Cambiar foto de perfil</label>
                        <input type="file" class="form-control" id="fotoPerfil" name="fotoPerfil">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero">
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigoAlumno" class="form-label">Código de Alumno</label>
                                <input type="text" class="form-control" id="codigoAlumno" name="codigoAlumno">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipoCuenta" class="form-label">Tipo de cuenta</label>
                                <select class="form-select" id="tipoCuenta" name="tipoCuenta">
                                    <!-- Opciones de tipo de cuenta -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="centro" class="form-label">Centro</label>
                                <select class="form-select" id="centro" name="centro">
                                    <!-- Opciones de centro -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correoElectronico" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correoElectronico" name="correoElectronico">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contrasena" name="contrasena">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Agregar opciones al tipo de cuenta
        var tipoCuentaOptions = [
            { value: 'Estudiante', text: 'Estudiante' },
            { value: 'Maestro', text: 'Maestro' },
            { value: 'Administrador', text: 'Administrador' }
        ];
        tipoCuentaOptions.forEach(function(option) {
            $('#tipoCuenta').append($('<option>', {
                value: option.value,
                text: option.text
            }));
        });

        $('#fotoPerfil').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#perfilImagen').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
            });

        // Agregar opciones al centro
        var centroOptions = [
            { value: 'CUALTOS', text: 'CUALTOS' },
            { value: 'CUCEA', text: 'CUCEA' }
        ];
        centroOptions.forEach(function(option) {
            $('#centro').append($('<option>', {
                value: option.value,
                text: option.text
            }));
        });

        $('#myTable').on('click', '.edit-btn', function() {
            // Obtener los datos de la fila correspondiente
            var rowData = $(this).closest('tr').find('td').map(function() {
                return $(this).text();
            }).get();

            // Llenar los campos del formulario en el modal con los datos de la fila
            $('#nombre').val(rowData[1]);
            $('#apellidos').val(rowData[2]); 
            $('#genero').val(rowData[4]); // Establecer el valor del género

            // Obtener el valor del tipo de cuenta y establecerlo como seleccionado
            var tipoCuentaValue = rowData[5];
            $('#tipoCuenta').val(tipoCuentaValue);

            // Obtener el valor del centro y establecerlo como seleccionado
            var centroValue = rowData[7];
            $('#centro').val(centroValue);        

            $('#correoElectronico').val(rowData[3]);

            $('#codigoAlumno').val(rowData[6]);

            // Mostrar el modal
            $('#editModal').modal('show');
        });
    });
</script>





</body>
</html>
