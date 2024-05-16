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
    
    <!-- Referencias para archivos locales-->
    <link rel = "stylesheet" href="styles/style_modal.css">
    <link rel = "stylesheet" href="src-ico/empreverest.svg">

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
    <a class="navbar-brand ps-3" href="Dashboard.html"><img src="src-ico/empreverest.svg" alt="Icono">EMPREVEREST</a>
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
            <div class="row">
                <div class="col-md-10">
                    <h1 class="mt-4">Bienvenido</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Administración de usuarios</li>
                    </ol> 
                </div>
                <div class="col-md-2 d-flex justify-content-end align-items-center">
                    <button type="button" class="btn btn-danger" style="background-color: #0b6380; border-color: #0b6380;" data-bs-toggle="modal" data-bs-target="#newModal">
                        <i class="fa-solid fa-user-plus fa-3x" style="color: #ffffff;"></i>
                    </button>                  
                </div>
                <hr>
            </div>
                <div class="row">
                    <table id="myTable" class="table align-middle mb-0 bg-white">
                        <thead class="bg-light">
                            <tr>
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
                                echo "<td>{$usuario['nombre']}</td>";
                                echo "<td>{$usuario['apellido']}</td>";
                                echo "<td>{$usuario['correoelectronico']}</td>";
                                echo "<td>{$usuario['genero']}</td>";
                                echo "<td>{$usuario['tipocuenta']}</td>";
                                echo "<td>{$usuario['codigo_alumno']}</td>";
                                echo "<td>{$usuario['centro']}</td>";
                                echo "<td>";
                                // Mostrar "Activo" o "Inactivo" según el valor del estado
                                if ($usuario['estado'] == 1) {
                                    echo "Activo";
                                } else {
                                    echo "Inactivo";
                                }
                                echo "</td>";
                                // Agregar columna para las acciones
                                echo "<td>";
                                echo "<button type='button' class='btn btn-primary btn-rounded edit-btn' style='background-color: #0b6380; border-color: #0b6380;' data-bs-toggle='modal' data-bs-target='#editModal' data-mdb-ripple-init>";
                                echo "<i class='fa-solid fa-pen-to-square fa-xl' style='color: #ffffff;'></i>";
                                echo "</button>"; 
                                if ($usuario['estado'] == 1) {
                                    echo "<button type='button' class='btn btn-danger' style='width: 40px; height: 40px; margin-left: 10px;' onclick=\"openConfirmModal('" . $usuario['correoelectronico'] . "')\" data-bs-toggle='modal' data-bs-target='#confirmModal'><i class='fa-solid fa-xmark fa-xl' style='color: #ffffff;'></i></button>";
                                } else {
                                    // Mostrar el botón diferente si el usuario está inactivo
                                    echo "<button type='button' class='btn btn-success' style='width: 40px; height: 40px; margin-left: 10px;' onclick=\"openConfirmModal1('" . $usuario['correoelectronico'] . "')\" data-bs-toggle='modal' data-bs-target='#confirmModal1'><i class='fa-solid fa-check' style='color: #ffffff;'></i></button>";
                                }                                            
                                echo "</td>";
                                echo "</tr>";
                            }
                            
                            ?>
                        </tbody>
                    </table>
                </div>     
                <hr>                
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Está seguro que desea desactivar este usuario?</p>
        <form action="desactivar.php" method="post">
          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" readonly>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <!-- Cambiar el tipo de botón de button a submit y agregar el atributo name -->
            <button type="submit" class="btn btn-danger" name="desactivarUsuario">Desactivar usuario</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModal1Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModal1Label">Reactivar usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Está seguro que desea reactivar este usuario?</p>
        <form action="reactivar.php" method="post">
          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" readonly>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <!-- Cambiar el tipo de botón de button a submit y agregar el atributo name -->
            <button type="submit" class="btn btn-success" name="desactivarUsuario">Reactivar usuario</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action = "actualizarAdmin.php" method = "post">
                    
                    <div>
                        <div class="mb-3">
                            <label for="correoElectronico" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correoElectronico" name="correoElectronico" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" >
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
                        
                        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Nuevo Modal -->
<div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newForm" action="newUser.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correoElectronicoNew" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correoElectronicoNew" name="correoElectronico" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="passwordNew" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="passwordNew" name="password" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombreNew" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreNew" name="nombre">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellidosNew" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidosNew" name="apellidos">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="generoNew" class="form-label">Género</label>
                                <select class="form-select" id="generoNew" name="genero">
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigoAlumnoNew" class="form-label">Código de Alumno</label>
                                <input type="text" class="form-control" id="codigoAlumnoNew" name="codigoAlumno">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipoCuentaNew" class="form-label">Tipo de cuenta</label>
                                <select class="form-select" id="tipoCuentaNew" name="tipoCuenta">
                                    <option value="Estudiante">Estudiante</option>
                                    <option value="Maestro">Maestro</option>
                                    <option value="Administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="centroNew" class="form-label">Centro</label>
                                <select class="form-select" id="centroNew" name="centro">
                                    <option value="CUALTOS">CUALTOS</option>
                                    <option value="CUCEA">CUCEA</option>
                                </select>
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
            $('#nombre').val(rowData[0]);
            $('#apellidos').val(rowData[1]); 
            $('#genero').val(rowData[3]); // Establecer el valor del género

            // Obtener el valor del tipo de cuenta y establecerlo como seleccionado
            var tipoCuentaValue = rowData[4];
            $('#tipoCuenta').val(tipoCuentaValue);

            // Obtener el valor del centro y establecerlo como seleccionado
            var centroValue = rowData[6];
            $('#centro').val(centroValue);        

            $('#correoElectronico').val(rowData[2]);
            $('#codigoAlumno').val(rowData[5]);

            // Mostrar el modal
            $('#editModal').modal('show');
        });
    });
</script>

<script>
    function openConfirmModal(correo) {
        // Establecer el valor del campo de correo del modal
        $('#exampleModal').find('#correo').val(correo);
        // Mostrar el modal
        $('#exampleModal').modal('show');
    }
</script>

<script>
    function openConfirmModal1(correo) {
        // Establecer el valor del campo de correo del modal
        $('#exampleModal1').find('#correo').val(correo);
        // Mostrar el modal
        $('#exampleModal1').modal('show');
    }
</script>

</body>
</html>

<!-- Toast | Mensajes para el usuario en caso de error-->
<div role="alert" id="loginToast" aria-live="assertive" aria-atomic="true" class="toast" data-bs-autohide="true">
  <div class="toast-header">
    <img src="src-ico/empreverest.svg" class="rounded me-2" alt="Empreverest">
    <strong class="me-auto">Error de inicio de sesion</strong>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body">
    Mensaje al usuario
  </div>
</div>

<script>
  // Obtener los parámetros de la URL
  const urlParams = new URLSearchParams(window.location.search); //Buscar la URL actual 
  const updateStatus = urlParams.get('update_people');
  const name = urlParams.get('name'); //Nombre
  const lastname = urlParams.get('lastnames'); //Apellido
  const chars = urlParams.get('chars'); //Nombre y apellido 
  const code = urlParams.get('code'); //Codigo de estudiante 

  const modalEditar = document.getElementById('editModal');

  // Función para mostrar el toast de error
  function showToast() {
      const toastEl = document.getElementById('loginToast');
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
  }

  // Mostrar el toast correspondiente según el parámetro de error
  if (updateStatus === 'fail') { // Contraseña incorrecta
    if(name === 'bad_entry') {
        document.querySelector('.toast-body').innerText = 'El nombre contiene caracteres inválidos';
        showToast();
    } else if(name === 'bad_entrymin') { //Validar si el nombre tiene caracteres validos
        document.querySelector('.toast-body').innerText = 'El nombre no contiene con la cantidad mínima requerida de caracteres';
        showToast();
        modalEditar.show();
    } else if(name === 'bad_entrymax') { //Validar la cantidad de caracteres
        document.querySelector('.toast-body').innerText = 'El nombre contiene demasiados caracteres'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal 
    } //Fin de las validaciones para el nombre
    
    if (lastname === 'bad_entry') {
        document.querySelector('.toast-body').innerText = 'El apellido tiene caracteres inválidos'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } else if(lastname === 'bad_entrymin') {
        document.querySelector('.toast-body').innerText = 'El apellido no contiene la cantidad mínima requerida de caracteres'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } else if (lastname === 'bad_entrymax') {
        document.querySelector('.toast-body').innerText = 'El apellido contiene demasiado caracteres'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } //Fin de las validaciones para los apellidos 
    
    else if (chars === 'bad_entrymin') {
        document.querySelector('.toast-body').innerText = 'El nombre y el apellido no contienen con la cantidad mínima requerida de caracteres'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } else if (chars === 'bad_entrymax') {
        document.querySelector('.toast-body').innerText = 'Nombre y apellido contienen superan la cantidad máxima de caracteres'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } 

    else if(code === 'bad_entry') {
        document.querySelector('.toast-body').innerText = 'El código no es válido'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } else if(code === 'repeated') {
        document.querySelector('.toast-body').innerText = 'El código de alumno ya está registrado con otra cuenta'; //Mensaje de confirmacion al usuario
        showToast(); //Mostrar modal
    } else if(updateStatus === 'success') { //Validar si se hizo un cambio de contraseña
        document.querySelector('.toast-body').innerText = 'Los datos fueron actualizados correctamente';
        showToast();
    }
}
</script>
