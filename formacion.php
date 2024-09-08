<?php
// Inicializamos nuestro arreglo de valores
$fields = array("id" => $_GET['id']);
$url = 'localhost/hojavidaphp/ApiRestFull/apiPersonas.php?action=getpersona';
// Convertir el arreglo a formato URL
$fields_string = http_build_query($fields);
// Abrir conexion cURL
$ch = curl_init();
// Configurar la url destino
curl_setopt($ch, CURLOPT_URL, $url);
// Indicar que se trata de una petición POST
curl_setopt($ch, CURLOPT_POST, 1);
// Enviar los campos adjuntos en el POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
// Evitar que el resultado de la petición se muestre por pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Ejecutar nuestra petición y almacenar el resultado en una variable
$data = curl_exec($ch);
$data = json_decode($data, true);
// Cerrar la conexión cURL
curl_close($ch);
$perfil = $data['persona'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de formación</title>
    <!-- hojas de estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/toastr.min.css">
    <!-- FavIcon -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    
</head>

<body>
    <div class="container" id="CRUD">
        <div class="card mt-4">
            <div class="card-header text-bg-secondary text-white">
                <h5 class="card-title">
                    <img src="img/formacion academica.png" alt="Datos de contacto" style="width: 32px;">
                    Información de la formación  para el perfil de
                    <?php echo $perfil['nombres'] . " " . $perfil['apellidos']; ?>
                    <a href="perfil.php?id=<?php echo $perfil['id'] ?>" class="btn btn-light text-dark float-end mx-2">Volver</a>
                    <button type="button" class="btn btn-info text-dark float-end mx-2" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fa fa-file-o" aria-hidden="true"></i>
                        Nuevo
                    </button>
                </h5>
            </div>
        </div>
        <div class="card-body">
            <table id="Personas" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 100px;"></th>
                        <th scope="col">ESCUELA</th>
                        <th scope="col">TÍTULO</th>
                        <th scope="col">PERIODO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in rows">
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="#" class="btn btn-primary" @click="selectRow(row)" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-danger" @click="selectRow(row)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                        <td>{{ row.escuela }}</td>
                        <td>{{ row.title }}</td>
                        <td>{{ row.periodo }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- createModal -->
        <div class="modal fade modal-lg" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-info">
                        <h1 class="modal-title fs-5" id="createModalLabel">Crear un nuevo registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="escuela" placeholder="escuela" v-model="newRow.escuela">
                                    <label for="escuela">Escuela</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="title" placeholder="title" v-model="newRow.title">
                                    <label for="title">Título</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="periodo" placeholder="periodo" v-model="newRow.periodo">
                                    <label for="periodo">Periodo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-info" @click="insertar(newRow)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Crear
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /createModal -->

        <!-- editModal -->
        <div class="modal fade modal-lg" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-primary">
                        <h1 class="modal-title fs-5" id="editModalLabel">Actualizar registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="escuela" placeholder="escuela" v-model="clicked.escuela">
                                    <label for="escuela">Escuela</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="title" placeholder="title" v-model="clicked.title">
                                    <label for="title">Título</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="periodo" placeholder="periodo" v-model="clicked.periodo">
                                    <label for="periodo">Periodo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" @click="actualizar(clicked)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /editModal -->

        <!-- deleteModal -->
        <div class="modal fade modal-lg" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-danger">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Confirme que desea eliminar este registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="escuela" placeholder="escuela" v-model="clicked.escuela">
                                    <label for="escuela">Escuela</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="title" placeholder="title" v-model="clicked.title">
                                    <label for="title">Título</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="periodo" placeholder="periodo" v-model="clicked.periodo">
                                    <label for="periodo">Periodo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="eliminar(clicked)">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            Si, Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /deleteModal -->
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script>
        var AppFormacion = new Vue({
            el: '#CRUD',
            data: {
                rows: [],
                clicked: {},
                newRow: {
                    escuela: '',
                    title: '',
                    periodo: '',
                    persona_id: <?php echo $perfil['id']; ?>
                },

            },
            mounted: function() {
                this.getAll();
            },
            methods: {
                getAll: function() {
                    persona = {
                        persona_id: <?php echo $perfil['id']; ?>
                    }
                    var formData = this.toFormData(persona);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiFormacion.php?action=read',
                        data: formData
                    };

                    var obj = this;

                    axios.request(config)
                        .then((response) => {
                            obj.rows = response.data.formacion;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                selectRow: function(row) {
                    this.clicked = row;
                },
                insertar: function(row) {
                    if (this.validar(row) === true) {
                        var formData = this.toFormData(row);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiFormacion.php?action=create',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.info(response.data.message, "Registro creado");
                                $('#createModal').modal('hide');
                                obj.rows.push(response.data.formacion);
                                obj.limpiarDatos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                validar: function(row) {
                    var validacionExitosa = true;
                    if (row.escuela === '') {
                        toastr.warning("Por favor escriba el nombre de la escuela", "Falta un dato");
                        validacionExitosa = false;
                    } else if (row.title == '') {
                        toastr.warning("Por favor escriba el título", "Falta un dato");
                        validacionExitosa = false;
                    }else if (row.periodo == '') {
                        toastr.warning("Por favor agregue el periodo", "Falta un dato");
                        validacionExitosa = false;
                    }
                    return validacionExitosa;
                },
                toFormData: function(obj) {
                    var form_data = new FormData()
                    for (var key in obj) {
                        form_data.append(key, obj[key])
                    }
                    return form_data;
                },
                limpiarDatos: function() {
                    this.newRow = {
                        escuela: '',
                        title: '',
                        periodo: '',
                        persona_id: <?php echo $perfil['id']; ?>
                    };
                },
                actualizar: function(row) {
                    if (this.validar(row) === true) {
                        var formData = this.toFormData(row);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiFormacion.php?action=update',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.success(response.data.mesage, "Registro actualizado", "El registro ha sido actualizado");
                                $('#editModal').modal('hide');
                                obj.getAll();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                eliminar: function(row) {
                    var formData = this.toFormData(this.clicked);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiFormacion.php?action=delete',
                        data: formData
                    };
                    var obj = this;
                    axios.request(config)
                        .then((response) => {
                            toastr.success(response.data.message, "Registro eliminado");
                            $('#deleteModal').modal('hide');
                            obj.getAll();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
            },
        });
    </script>
</body>

</html>