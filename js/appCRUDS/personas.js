var AppPersonas = new Vue({
    el: "#aplicacion",
    data: {
        personas: [],
        clickedPersona: {},
        newPersona: { identidad: "", fechanac: "", nombres: "", apellidos: "", sexo: "", estadocivil: "", casadocon: "", nacionalidad: "", resumen: "" },
    },
    mounted: function () {
        this.getAllPersons();
    },
    methods: {
        getAllPersons: function () {
            let config = {
                method: 'get',
                maxBodyLength: Infinity,
                url: 'http://localhost/CRUDPHP2024/ApiRestFull/apiPersona.php?action=read',
                headers: {}
            };

            axios.request(config)
                .then((response) => {
                    if (response.data.status != "ok") {
                        toastr["warning"](response.data.message, "Sin datos");
                        return;
                    }
                    AppPersonas.personas = response.data.personas;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        selectPersona: function (Persona) {
            this.clickedPersona = Persona;
        },
        limpiarDatos: function () {
            this.newPersona = { identidad: '', fechanac: '', nombres: '', apellidos: '', sexo: '', estadocivil: '', casadocon: '', nacionalidad: '', resumen: '' };
        },
        toFormData: function (obj) {
            var form_data = new FormData();
            for (var key in obj) {
                form_data.append(key, obj[key]);
            }
            return form_data;
        },
        validar: function (Persona) {
            var validacionExitosa = true;
            if (Persona.identidad == '') {
                alert("Por favor escriba el numero de identidad, no debe quedar vacio.");
                validacionExitosa = false;
            } else if (Persona.sexo == '') {
                alert("Por favor seleccione Femenino o Masculino, no debe quedar vacio.");
                validacionExitosa = false;
            } else if (Persona.nombres == '') {
                alert("Por favor escriba los nombres completos, no debe quedar vacio.");
                validacionExitosa = false;
            } else if (Persona.apellidos == '') {
                alert("Por favor escriba los apellidos, no debe quedar vacio.");
                validacionExitosa = false;
            } else if (Persona.nacionalidad == '') {
                alert("Por favor escriba la nacionalidad, no debe quedar vacio.");
                validacionExitosa = false;
            } else if (Persona.estadocivil == '') {
                alert("Por favor seleccione una opcion para el estado civil, no debe quedar vacio.");
                validacionExitosa = false;
            } else if ((Persona.estadocivil == 'Casado' || Persona.estadocivil == 'Unión Libre') && (Persona.casadocon == '')) {
                alert("Por favor seleccione una opcion para el nombre de la pareja, no debe quedar vacio.");
                validacionExitosa = false;
            }

            return validacionExitosa;
        },
        Insertar: function (Persona) {
            if (AppPersonas.validar(AppPersonas.newPersona) === true) {
                var formData = AppPersonas.toFormData(AppPersonas.newPersona);

                let config = {
                    method: 'post',
                    maxBodyLength: Infinity,
                    url: 'http://localhost/CRUDPHP2024/ApiRestFull/apiPersona.php?action=create',
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                };

                axios.request(config)
                    .then((response) => {
                        $('#addPersonModal').modal('hide');
                        if (response.data.status == 'ok') {
                            toastr["success"](response.data.message, "Guardar");
                            AppPersonas.personas.push(response.data.persona)
                            AppPersonas.limpiarDatos(AppPersonas.newCandidato);
                        } else {
                            toastr["warning"](response.data.message, "Sin guardar");
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        Actualizar: function(Persona){
            if (AppPersonas.validar(AppPersonas.clickedPersona) === true) {
                var formData = AppPersonas.toFormData(AppPersonas.clickedPersona);

                let config = {
                    method: 'post',
                    maxBodyLength: Infinity,
                    url: 'http://localhost/CRUDPHP2024/ApiRestFull/apiPersona.php?action=update',
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                };

                axios.request(config)
                    .then((response) => {
                        $('#UpdatePersonModal').modal('hide');
                        if (response.data.status == 'ok') {
                            toastr["success"](response.data.message, "Actualizar");
                            AppPersonas.getAllPersons();
                        } else {
                            toastr["warning"](response.data.message, "Sin actualizar");
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        Borrar: function(Persona){
            var formData = AppPersonas.toFormData(AppPersonas.clickedPersona);

                let config = {
                    method: 'post',
                    maxBodyLength: Infinity,
                    url: 'http://localhost/CRUDPHP2024/ApiRestFull/apiPersona.php?action=delete',
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                };

                axios.request(config)
                    .then((response) => {
                        $('#DeletePersonModal').modal('hide');
                        if (response.data.status == 'ok') {
                            toastr["success"](response.data.message, "Borrar");
                            AppPersonas.getAllPersons();
                        } else {
                            toastr["warning"](response.data.message, "Sin borrar");
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
        },

    },
});