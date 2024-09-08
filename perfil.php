<?php
//Inicializamos nuestro sarreglo de valores 
$fields = array("id" => $_GET['id']);
$url =  'localhost/hojavidaphp/ApiRestFull/apiPersonas.php?action=getpersona';
//convertir el arreglo a formato URL
$fields_string = http_build_query($fields);
//abrir conexion cURL
$ch = curl_init();
//Configurar la url destino
curl_setopt($ch, CURLOPT_URL, $url);
//Indicar que se trata de una peticion POST
curl_setopt($ch, CURLOPT_POST, 1);
//Enviar los campos adjuntos en el POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//Evitar que el resultado de la peticion no se muestre por pantalla 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//Ejecutar nuestra peticion y almacenar el resultado en una variable 
$data = curl_exec($ch);
$data = json_decode($data, true);
//Cerrar la conexion cURL
curl_close($ch);
$perfil = $data['persona'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil personal</title>
    <!--Hojas de estilo-->
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
    <div class="container">
        <div class="card mt-3">
            <div class="card-header text-bg-warning">
                <h5 class="card-title">
                    <i class="fa fa-address-card-o me-2" aria-hidden="true"></i>
                    Perfil personal
                    <a href="/hojavidaphp/" class="btn btn-secondary float-end">Volver</a>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="text-center" id="fotoPerfil">
                            <div v-if="imgData.length > 0">
                                <img :src='imgData' alt="" class="rounded mg-fluid img-thumbnail">
                            </div>
                            <div>
                                <img :src='foto' alt="" class="rounded mg-fluid img-thumbnail">
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Cambiar foto de perfil</label>
                                <input class="form-control" type="file" id="foto" accept="image/*" @change="previewImage">
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-4">
                                <p class="lead">
                                    Identidad:
                                    <strong>
                                        <? echo $perfil['identidad']; ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="lead">
                                    Nombres:
                                    <strong>
                                        <? echo $perfil['nombres']; ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="lead">
                                    Apellidos:
                                    <strong>
                                        <? echo $perfil['apellidos']; ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <p class="lead">
                                    Sexo:
                                    <strong>
                                        <? echo $perfil['sexo']; ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="lead">
                                    Nacimiento:
                                    <strong>
                                        <? echo $perfil['fechanac']; ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="lead">
                                    Nacionalidad:
                                    <strong>
                                        <? echo $perfil['nacionalidad']; ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <p class="lead">
                                    Estado Civil:
                                    <strong>
                                        <? echo $perfil['estadocivil']; ?>
                                    </strong>
                                </p>
                            </div>
                            <div class="col-4">
                                <p class="lead">
                                    Casado con:
                                    <strong>
                                        <? echo $perfil['casadocon']; ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="lead">
                                    <strong>
                                        <?php echo $perfil['resumen']; ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="contactos.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/contacto.png" class="rounded w-100 h-100" alt="">
                                        Contacto
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="idiomas.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/idiomas.png" class="rounded w-100 h-100" alt="">
                                        Idiomas
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="habilidades.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/habilidades.png" class="rounded w-100 h-100" alt="">
                                        Habilidades
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="pasatiempos.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/pasatiempo.png" class="rounded w-100 h-100" alt="">
                                        Pasatiempos     
                                    </a>
                                </div>
                            </div> 
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="formacion.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/academia.png" class="rounded w-100 h-100" alt="">
                                        Formacion     
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="experiencia.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/experiencia.png" class="rounded w-100 h-100" alt="">
                                        Experiencia     
                                    </a>
                                </div>
                            </div> 
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="logros.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/logros.png" class="rounded w-100 h-100" alt="">
                                        Logros     
                                    </a>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="formato1.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/formato1.png" class="rounded w-100 h-100" alt="">
                                        Formato 1     
                                    </a>
                                </div>
                            </div> 
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="formato2.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/formato2.png" class="rounded w-100 h-100" alt="">
                                        Formato 2     
                                    </a>
                                </div>
                            </div> 
                            <div class="col-2">
                                <div class="tex-center">
                                    <a href="formato.php?id=<?php echo $perfil['id'] ?>" class="btn btn-outline-warning link-undeline-light border-0">
                                        <img src="img/formato3.png" class="rounded w-100 h-100" alt="">
                                        Formato 3    
                                    </a>
                                </div>
                            </div> 
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/axios.min.js"></script>
    <script src="js/toastr.min.js"></script>
    <script>
        var fotoPerfil = new Vue({
            el: '#fotoPerfil',
            data: {
                id: <?php echo $perfil['id'] ?>,
                foto: <?php
                        if (!empty($perfil['foto']))
                            echo "'perfiles/" . $perfil['foto'] . "'";
                        else
                            echo "'img/profile.png'";
                        ?>,
                imgData: '',
            },
            methods: {
                previewImage: function(event) {
                    //Referencia al DOM en el elemento input
                    var input = event.target;
                    //Aseguramos de tener un archivo antes de intentar leerlo
                    if (input.files && input.files[0]) {
                        //Crear un nuevo fileReader para leer la imagen y convertirla a formato base64
                        var reader = new FileReader();
                        //Definir una funcion de devolucion de llamada para ejecutarse cuando el filereader finalice 
                        reader.onload = (e) => {
                            this.imgData = e.target.result;
                            this.foto = input.files[0];
                            this.ActualizarFoto();
                        }
                        //Iniciar el trabajo del objeto reader: leer el archivo como una url de datos (formato base64)
                        reader.readAsDataURL(input.files[0]);
                    }
                },
                ActualizarFoto: function() {
                    let data = new FormData();
                    data.append('id', this.id);
                    data.append('foto', this.foto);

                    let config = {
                        method: 'post',
                        url: 'ApiRestFull/apiPersonas.php?action=photo',
                        data: data
                    };

                    axios.request(config)
                        .then((response) => {
                            toastr.success(response.data.message, "Foto perfil actualizada");
                        })
                        .catch((error) => {
                            toastr.danger(toastr.data.message, "Error foto perfil");
                        });

                },
            },
        });
    </script>
</body>

</html>