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
    <title>Información de idiomas</title>
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
    <div class="container" id="IdiomaCRUD">
        <div class="card mt-4">
            <div class="card-header text-bg-secondary text-white">
                <h5 class="card-title">
                    <img src="img/idiomas.png" alt="Datos de contacto" style="width: 32px;">
                    Información de idioma para el perfil de
                    <?php echo $perfil['nombres'] . " " . $perfil['apellidos']; ?>
                    <a href="perfil.php?id=<?php echo $perfil['id'] ?>" class="btn btn-light text-dark float-end mx-2">Volver</a>
                    <button type="button" class="btn btn-info text-dark float-end mx-2" data-bs-toggle="modal" data-bs-target="#createIdiomaModal">
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
                        <th scope="col">IDIOMA</th>
                        <th scope="col">PORCENTAJE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="idioma in idiomas">
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="#" class="btn btn-primary" @click="selectIdioma(idioma)" data-bs-toggle="modal" data-bs-target="#editIdiomaModal">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-danger" @click="selectIdioma(idioma)" data-bs-toggle="modal" data-bs-target="#deleteIdiomaModal">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                        <td>{{ idioma.idioma }}</td>
                        <td>{{ idioma.porcentaje }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- createIdiomaModal -->
        <div class="modal fade modal-lg" id="createIdiomaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createIdiomaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-info">
                        <h1 class="modal-title fs-5" id="createIdiomaModalLabel">Crear un nuevo idioma</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="idioma" placeholder="Nombre del idioma" v-model="newIdioma.idioma">
                                    <label for="idioma">Idioma</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="porcentaje" placeholder="valor" v-model="newIdioma.porcentaje" disabled>
                                    <label for="porcentaje2">Porcentaje</label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="porcentaje" class="form-label">Porcentaje de dominio</label>
                                <input type="range" class="form-range" id="porcentaje" placeholder="valor" v-model="newIdioma.porcentaje">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-info" @click="insertar(newIdioma)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Crear
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /createIdiomaModal -->

        <!-- editIdiomaModal -->
        <div class="modal fade modal-lg" id="editIdiomaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editIdiomaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-primary">
                        <h1 class="modal-title fs-5" id="editIdiomaModalLabel">Actualizar idioma</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="idioma" placeholder="Nombre del idioma" v-model="clickedIdioma.idioma">
                                    <label for="idioma">Idioma</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="porcentaje" placeholder="valor" v-model="clickedIdioma.porcentaje" disabled>
                                    <label for="porcentaje2">Porcentaje</label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="porcentaje" class="form-label">Porcentaje de dominio</label>
                                <input type="range" class="form-range" id="porcentaje" placeholder="valor" v-model="clickedIdioma.porcentaje">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" @click="actualizar(clickedIdioma)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /editIdiomaModal -->

        <!-- deleteIdiomaModal -->
        <div class="modal fade modal-lg" id="deleteIdiomaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteIdiomaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-danger">
                        <h1 class="modal-title fs-5" id="deleteIdiomaModalLabel">Si, eliminar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="idioma" placeholder="Nombre del idioma" v-model="clickedIdioma.idioma" disabled>
                                    <label for="idioma">Idioma</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="porcentaje" placeholder="valor" v-model="clickedIdioma.porcentaje" disabled>
                                    <label for="porcentaje2">Porcentaje</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="eliminar(clickedIdioma)">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            Si, eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /deleteIdiomaModal -->
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script>
        var AppIdiomas = new Vue({
            el: '#IdiomaCRUD',
            data: {
                idiomas: [],
                clickedIdioma: {},
                newIdioma: {
                    idioma: '',
                    porcentaje: 1,
                    persona_id: <?php echo $perfil['id']; ?>
                },
                clickedIdioma: {},

            },
            mounted: function() {
                this.getAllIdiomas();
            },
            methods: {
                getAllIdiomas: function() {
                    persona = {
                        persona_id: <?php echo $perfil['id']; ?>
                    }
                    var formData = this.toFormData(persona);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiIdiomas.php?action=read',
                        data: formData
                    };

                    var obj = this;

                    axios.request(config)
                        .then((response) => {
                            obj.idiomas = response.data.idiomas;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                selectIdioma: function(idioma) {
                    this.clickedIdioma = idioma;
                },
                insertar: function(idioma) {
                    if (this.validar(idioma) === true) {
                        var formData = this.toFormData(idioma);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiIdiomas.php?action=create',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.info(response.data.message, "Registro creado");
                                $('#createIdiomaModal').modal('hide');
                                obj.idiomas.push(response.data.idioma);
                                obj.limpiarDatos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                validar: function(idioma) {
                    var validacionExitosa = true;
                    if (idioma.idioma === '') {
                        toastr.warning("Por favor escriba el idioma", "Falta un dato");
                        validacionExitosa = false;
                    } else if (idioma.porcentaje == '') {
                        toastr.warning("Por favor escoja el porcentaje de dominio", "Falta un dato");
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
                    this.newIdioma = {
                        idioma: '',
                        porcentaje: 1,
                        persona_id: <?php echo $perfil['id']; ?>
                    };
                },
                actualizar: function(idioma) {
                    if (this.validar(idioma) === true) {
                        var formData = this.toFormData(idioma);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiIdiomas.php?action=update',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.success(response.data.mesage, "Registro actualizado", "El registro ha sido actualizado");
                                $('#editIdiomaModal').modal('hide');
                                obj.getAllIdiomas();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                eliminar: function(idioma) {
                    var formData = this.toFormData(this.clickedIdioma);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiIdiomas.php?action=delete',
                        data: formData
                    };
                    var obj = this;
                    axios.request(config)
                        .then((response) => {
                            toastr.success(response.data.message, "Idioma eliminado");
                            $('#deleteIdiomaModal').modal('hide');
                            obj.getAllIdiomas();
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