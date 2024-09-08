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
    <title>Información de pasatiempos</title>
    <!-- hojas de estilos -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/toastr.min.css">
    <!-- FavIcon -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="img/site.webmanifest">
</head>

<body>
<div class="container" id="PasatiempoCRUD">
    <div class="card mt-4">
        <div class="card-header text-bg-secondary text-white">
            <h5 class="card-title">
                <img src="img/pasatiempo.png" alt="Datos de contacto" style="width: 32px;">
                Información de pasatiempo para el perfil de
                <?php echo $perfil['nombres'] . " " . $perfil['apellidos']; ?>
                <a href="perfil.php?id=<?php echo $perfil['id'] ?>" class="btn btn-light text-dark float-end mx-2">Volver</a>
                <button type="button" class="btn btn-info text-dark float-end mx-2" data-bs-toggle="modal" data-bs-target="#createPasatiempoModal">
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
                    <th scope="col">Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pasatiempo in pasatiempos">
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <a href="#" class="btn btn-primary" @click="selectPasatiempo(pasatiempo)" data-bs-toggle="modal" data-bs-target="#editPasatiempoModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            <a href="#" class="btn btn-danger" @click="selectPasatiempo(pasatiempo)" data-bs-toggle="modal" data-bs-target="#deletePasatiempoModal">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </td>
                    <td>{{ pasatiempos.descripcion }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- createPasatiempoModal -->
    <div class="modal fade modal-lg" id="createPasatiempoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createPasatiempoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-info">
                    <h1 class="modal-title fs-5" id="createPasatiempoModalLabel">Crear un nuevo pasatiempo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="descripcion" placeholder="Nombre del pasatiempo" v-model="newPasatiempos.descripcion">
                                <label for="descripcion">Descripción</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-info" @click="insertar(newPasatiempos)">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Crear
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /createPasatiempoModal -->

    <!-- editPasatiempoModal -->
    <div class="modal fade modal-lg" id="editPasatiempoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPasatiempoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-primary">
                    <h1 class="modal-title fs-5" id="editPasatiempoModalLabel">Actualizar Pasatiempo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="editDescripcion" placeholder="Nombre del pasatiempo" v-model="clickedPasatiempos.descripcion">
                                <label for="editDescripcion">Descripción</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" @click="actualizar(clickedPasatiempos)">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /editPasatiempoModal -->

    <!-- deletePasatiempoModal -->
    <div class="modal fade modal-lg" id="deletePasatiempoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deletePasatiempoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-bg-danger">
                    <h1 class="modal-title fs-5" id="deletePasatiempoModalLabel">Eliminar Pasatiempo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="deleteDescripcion" placeholder="Nombre del pasatiempo" v-model="clickedPasatiempos.descripcion" disabled>
                                <label for="deleteDescripcion">Descripción</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-undo" aria-hidden="true"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" @click="eliminar()">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /deletePasatiempoModal -->
</div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script>
        var AppPasatiempos = new Vue({
            el: '#PasatiempoCRUD',
            data: {
                pasatiempos: [],
                clickedPasatiempos: {},
                newPasatiempos: {
                    descripcion: '',
                    persona_id: <?php echo $perfil['id']; ?>
                }
            },
            mounted: function() {
                this.getAllpasatiempos();
            },
            methods: {
                getAllpasatiempos: function() {
                    let persona = {
                        persona_id: <?php echo $perfil['id']; ?>
                    };
                    var formData = this.toFormData(persona);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiPasatiempos.php?action=read',
                        data: formData
                    };

                    axios.request(config)
                        .then((response) => {
                            this.pasatiempos = response.data.pasatiempos;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                selectPasatiempo: function(pasatiempo) {
                    this.clickedPasatiempos = pasatiempo;
                },
                insertar: function(pasatiempo) {
                    if (this.validar(pasatiempo)) {
                        var formData = this.toFormData(pasatiempo);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiPasatiempos.php?action=create',
                            data: formData
                        };

                        axios.request(config)
                            .then((response) => {
                                toastr.info(response.data.message, "Registro creado");
                                $('#createPasatiempoModal').modal('hide');
                                this.pasatiempos.push(response.data.pasatiempo);
                                this.limpiarDatos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                validar: function(pasatiempo) {
                    if (pasatiempo.descripcion === '') {
                        toastr.warning("Por favor escriba la descripcion", "Falta un dato");
                        return false;
                    }
                    return true;
                },
                toFormData: function(obj) {
                    var form_data = new FormData();
                    for (var key in obj) {
                        form_data.append(key, obj[key]);
                    }
                    return form_data;
                },
                limpiarDatos: function() {
                    this.newPasatiempos = {
                        descripcion: '',
                        persona_id: <?php echo $perfil['id']; ?>
                    };
                },
                actualizar: function(pasatiempo) {
                    if (this.validar(pasatiempo)) {
                        var formData = this.toFormData(pasatiempo);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiPasatiempos.php?action=update',
                            data: formData
                        };

                        axios.request(config)
                            .then((response) => {
                                toastr.success(response.data.message, "Registro actualizado");
                                $('#editPasatiempoModal').modal('hide');
                                this.getAllpasatiempos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                eliminar: function() {
                    var formData = this.toFormData(this.clickedPasatiempos);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiPasatiempos.php?action=delete',
                        data: formData
                    };

                    axios.request(config)
                        .then((response) => {
                            toastr.success(response.data.message, "Descripción eliminada");
                            $('#editPasatiempoModal').modal('hide');
                            this.getAllpasatiempos();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            }
        });
    </script>

</body>

</html>