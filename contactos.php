<?php
//Inicializamos nuestro arreglo de valores
$fields = array("id" => $_GET['id']);
$url = 'localhost/hojavidaphp/ApiRestFull/apiPersonas.php?action=getpersona';
//Convertir el arreglo a formato URL
$fields_string = http_build_query($fields);
//Abriri conexion cURL
$ch = curl_init();
//configurar la url destino
curl_setopt($ch, CURLOPT_URL, $url); //la funcion pide esos 3 parametros, lo que hace es que configura un obejto curl para una transferencia
//Indicar que se trata de una peticion POST
curl_setopt($ch, CURLOPT_POST, 1);
//Enviar los campos adjuntos en el POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//Evitar que el resultado de la peticion no se muestre por pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//Ejecutar nestra peticion y almacenar el resultado en una variable
$data = curl_exec($ch);
$data = json_decode($data, true);
//cerrar la conexion cURL
curl_close($ch);
$perfil = $data['persona'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de contactos</title>
    <!--hojas de estilos-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/toastr.min.css">
    <!--FavIcon-->
    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
    <link rel="manifest" href="img/site.webmanifest">
</head>

<body>
    <div class="container" id="ContactosCRUD">
        <div class="card mt-4">
            <div class="card-header text-bg-secondary text-white">

                <h5 class="card-title">
                    <img src="img/contacto.png" alt="Datos de contacto" style="width: 32px;">
                    Información de contacto para el perfil de
                    <?php echo $perfil['nombres'] . " " . $perfil['apellidos']; ?>

                    <a href="perfil.php?id=<?php echo $perfil['id'] ?>" class="btn btn-light text-dark float-end mx-2">Volver</a>
                    <button type="button" class="btn btn-info text-dark float-end mx-2" data-bs-toggle="modal" data-bs-target="#createContactoModal">
                        <i class="fa fa-file-o" aria-hidden="true"></i>
                        Nuevo
                    </button>
                </h5>
            </div>
        </div>
        <div class="card-body">
            <table id="contactos" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 100px;"></th>
                        <th scope="col">TIPO</th>
                        <th scope="col">DESCRIPCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="contacto in contactos">
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="#" class="btn btn-primary" @click="selectContacto(contacto)" data-bs-toggle="modal" data-bs-target="#editContactoModal">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-danger" @click="selectContacto(contacto)" data-bs-toggle="modal" data-bs-target="#deleteContactoModal">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>
                        </td>
                        <td><i :class="contacto.tipo+ ' fa-2x'" aria-hidden="true"></i></td>
                        <td>{{contacto.descripcion}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--createContactoModal-->
        <div class="modal fade modal-lg" id="createContactoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createContactoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-info">
                        <h1 class="modal-title fs-5" id="createContactoModalLabel">
                            Crear un nuevo contacto
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-floating">
                                    <select class="form-select fa" id="tipo" aria-label="Tipo de contacto" v-model="newContacto.tipo">
                                        <option class="fa" value="fa fa-map-marker"> &#xf041; &nbsp; Direccion</option>
                                        <option class="fa" value="fa fa-envelope-o"> &#xf003; &nbsp; Correo Electronico</option>
                                        <option class="fa" value="fa fa-phone"> &#xf095; &nbsp; Teléfono</option>
                                        <option class="fa" value="fa fa-whatsapp"> &#xf232; &nbsp; WhatsApp</option>
                                        <option class="fa" value="fa fa-facebook-official"> &#xf230; &nbsp; FaceBook</option>
                                        <option class="fa" value="fa fa-instagram"> &#xf16d; &nbsp; Instagram</option>
                                        <option class="fa" value="fa fa-youtube-play"> &#xf16a; &nbsp; Youtube</option>
                                        <option class="fa" value="fa fa-twitter"> &#xf099; &nbsp; Twitter</option>
                                        <option class="fa" value="fa fa-spotify"> &#xf1bc; &nbsp; Spotify</option>
                                        <option class="fa" value="fa fa-pinterest"> &#xf0d2; &nbsp; Pinterest</option>
                                        <option class="fa" value="fa fa-snapchat-ghost"> &#xf2ac; &nbsp; SnapChat</option>
                                        <option class="fa" value="fa fa-skype"> &#xf17e; &nbsp; Skype</option>
                                        <option class="fa" value="fa fa-github"> &#xf09b; &nbsp; GitHub</option>
                                        <option class="fa" value="fa fa-telegram"> &#xf2c6; &nbsp; Telegram</option>
                                    </select>

                                    <label for="tipo">Tipo</label>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="Descripcion" placeholder="Descripción del contacto" v-model="newContacto.descripcion">
                                    <label for="Descripcion">Descripción</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-info" @click="insertar(newContacto)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Crear
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/createContactoModal-->

        <!--editContactoModal-->
        <div class="modal fade modal-lg" id="editContactoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editContactoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-primary">
                        <h1 class="modal-title fs-5" id="editContactoModalLabel">
                            Editar contacto
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-floating">
                                    <select class="form-select fa" id="tipo" aria-label="Tipo de contacto" v-model="clickedContacto.tipo">
                                        <option class="fa" value="fa fa-map-marker"> &#xf041; &nbsp; Direccion</option>
                                        <option class="fa" value="fa fa-envelope-o"> &#xf003; &nbsp; Correo Electronico</option>
                                        <option class="fa" value="fa fa-phone"> &#xf095; &nbsp; Teléfono</option>
                                        <option class="fa" value="fa fa-whatsapp"> &#xf232; &nbsp; WhatsApp</option>
                                        <option class="fa" value="fa fa-facebook-official"> &#xf230; &nbsp; FaceBook</option>
                                        <option class="fa" value="fa fa-instagram"> &#xf16d; &nbsp; Instagram</option>
                                        <option class="fa" value="fa fa-youtube-play"> &#xf16a; &nbsp; Youtube</option>
                                        <option class="fa" value="fa fa-twitter"> &#xf099; &nbsp; Twitter</option>
                                        <option class="fa" value="fa fa-spotify"> &#xf1bc; &nbsp; Spotify</option>
                                        <option class="fa" value="fa fa-pinterest"> &#xf0d2; &nbsp; Pinterest</option>
                                        <option class="fa" value="fa fa-snapchat-ghost"> &#xf2ac; &nbsp; SnapChat</option>
                                        <option class="fa" value="fa fa-skype"> &#xf17e; &nbsp; Skype</option>
                                        <option class="fa" value="fa fa-github"> &#xf09b; &nbsp; GitHub</option>
                                        <option class="fa" value="fa fa-telegram"> &#xf2c6; &nbsp; Telegram</option>
                                    </select>

                                    <label for="tipo">Tipo</label>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="Descripcion" placeholder="Descripción del contacto" v-model="clickedContacto.descripcion">
                                    <label for="Descripcion">Descripción</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" @click="actualizar(clickedContacto)">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/editContactoModal-->

        <!--deleteContactoModal-->
        <div class="modal fade modal-lg" id="deleteContactoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteContactoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-bg-danger">
                        <h1 class="modal-title fs-5" id="deleteContactoModalLabel">
                            Confirme que desea eliminar el contacto
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-floating">
                                    <select class="form-select fa" id="tipo" aria-label="Tipo de contacto" v-model="clickedContacto.tipo" disabled>
                                        <option class="fa" value="fa fa-map-marker"> &#xf041; &nbsp; Direccion</option>
                                        <option class="fa" value="fa fa-envelope-o"> &#xf003; &nbsp; Correo Electronico</option>
                                        <option class="fa" value="fa fa-phone"> &#xf095; &nbsp; Teléfono</option>
                                        <option class="fa" value="fa fa-whatsapp"> &#xf232; &nbsp; WhatsApp</option>
                                        <option class="fa" value="fa fa-facebook-official"> &#xf230; &nbsp; FaceBook</option>
                                        <option class="fa" value="fa fa-instagram"> &#xf16d; &nbsp; Instagram</option>
                                        <option class="fa" value="fa fa-youtube-play"> &#xf16a; &nbsp; Youtube</option>
                                        <option class="fa" value="fa fa-twitter"> &#xf099; &nbsp; Twitter</option>
                                        <option class="fa" value="fa fa-spotify"> &#xf1bc; &nbsp; Spotify</option>
                                        <option class="fa" value="fa fa-pinterest"> &#xf0d2; &nbsp; Pinterest</option>
                                        <option class="fa" value="fa fa-snapchat-ghost"> &#xf2ac; &nbsp; SnapChat</option>
                                        <option class="fa" value="fa fa-skype"> &#xf17e; &nbsp; Skype</option>
                                        <option class="fa" value="fa fa-github"> &#xf09b; &nbsp; GitHub</option>
                                        <option class="fa" value="fa fa-telegram"> &#xf2c6; &nbsp; Telegram</option>
                                    </select>

                                    <label for="tipo">Tipo</label>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="Descripcion" placeholder="Descripción del contacto" v-model="clickedContacto.descripcion" readonly> <!--un input text recibe un readonly que va ser solo de lectura o puede recibir tambien el atributo disabled pero este (disabled) se le coloca el atributo para que no este inabilitado y no permita cambiar -->
                                    <label for="Descripcion">Descripción</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="eliminar(clickedContacto)">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            Si, eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--/deleteContactoModal-->
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script>
        var AppContactos = new Vue({
            el: '#ContactosCRUD',
            data: {
                contactos: [],
                clickedContacto: {},
                newContacto: {
                    tipo: '',
                    descripcion: '',
                    persona_id: <?php echo $perfil['id']; ?>
                },
                clickedContacto: {},

            },
            mounted: function() {
                this.getAllContactos();
            },
            methods: {
                getAllContactos: function() {
                    persona = {
                        persona_id: <?php echo $perfil['id']; ?>
                    }
                    var formData = this.toFormData(persona);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiContactos.php?action=read',
                        data: formData
                    };

                    var obj = this;

                    axios.request(config)
                        .then((response) => {
                            obj.contactos = response.data.contactos;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                selectContacto: function(contacto) {
                    this.clickedContacto = contacto;
                },
                insertar: function(contacto) {
                    if (this.validar(contacto) === true) {
                        var formData = this.toFormData(contacto);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiContactos.php?action=create',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.info(response.data.message, "Registro creado");
                                $('#createContactoModal').modal('hide');
                                obj.contactos.push(response.data.contacto);
                                obj.limpiarDatos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                validar: function(contacto) {
                    var validacionExitosa = true;
                    if (contacto.tipo == '') {
                        toastr.warning("Por favor seleccione el tipo de contacto, este no debe quedar vacio", "Falta un dato");
                        validacionExitosa = false;
                    } else if (contacto.descripcion == '') {
                        toastr.warning("Por favor la informacion de contacto, este dato no debe quedar vacio", "Falta un dato");
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
                    this.newContacto = {
                        tipo: '',
                        descripcion: '',
                        persona_id: <?php echo $perfil['id']; ?>
                    };
                },
                actualizar: function(contacto) {
                    if (this.validar(contacto) === true) {
                        var formData = this.toFormData(contacto);
                        let config = {
                            method: 'post',
                            url: 'ApiRestFull/apiContactos.php?action=update',
                            data: formData
                        };
                        var obj = this;
                        axios.request(config)
                            .then((response) => {
                                toastr.success(response.data.mesage, "Registro actualizado", "El registro ha sido actualizado");
                                $('#editContactoModal').modal('hide');
                                obj.getAllContactos();
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                },
                eliminar: function(contacto) {
                    var formData = this.toFormData(this.clickedContacto);
                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiContactos.php?action=delete',
                        data: formData
                    };
                    var obj = this;
                    axios.request(config)
                        .then((response) => {
                            toastr.success(response.data.message, "Registro eliminado");
                            $('#deleteContactoModal').modal('hide');
                            obj.getAllContactos();
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