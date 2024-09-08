var CRUDPerson = new Vue({
  el: "#aplicacion",
  data: {
    personas: [],
    newPersona: {
      identidad: '',
      fechanac: '',
      nombres: '',
      apellidos: '',
      sexo: '',
      estadocivil: '',
      casadocon: '',
      nacionalidad: '',
      resumen: '',
    },
    clickedPersona: {},
  },
  mounted: function () {
    this.getAllPersons();
  },
  methods: {      
    getAllPersons: function () {
      let config = {
        method: "get",
        maxBodyLength: Infinity,
        url: "ApiRestFull/apiPersonas.php?action=read",
        headers: {},
      };
      var obj = this;
      axios
        .request(config)
        .then((response) => {
          if (response.data.message != "ok") {
            alert(response.data.message);
            return;
          }
          obj.personas = response.data.personas;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    selectPersona: function(persona){
      this.clickedPersona = persona;
    },
    insertar: function(persona){
      if(this.validar(persona)===true){
        var formData = this.toFormData(this.newPersona)
        let config = {
          method: 'post',
          maxBodyLength: Infinity,
          url: 'ApiRestFull/apiPersonas.php?action=create',
          data : formData
        };
        var obj = this;
        axios.request(config)
        .then((response) => {
          $('#createPersonaModal').modal('hide');
          toastr.info(response.data.message, "Registro creado");
          obj.personas.push(response.data.persona);
          obj.limpiarDatos(obj.newPersona);
        })
        .catch((error) => {
          console.log(error);
        });
      }
    },
    validar:function(persona){
      var validacionExitosa = true;
      if(persona.identidad == ''){
        toastr.warning("Por favor escriba el numero de identidad, este no debe quedar vacio")
        validacionExitosa = false;
      }else if(persona.nombres == ''){
        toastr.warning("Por favor escriba los nombres de la persona, este dato no debe quedar vacio", "Falta un dato");
        validacionExitosa = false;
      }else if(persona.apellidos == ''){
        toastr.warning("Por favor escriba los apellidos de la persona, este dato no debe quedar vacio", "Falta un dato");
        validacionExitosa = false;
      }else if(persona.fechanac == ''){
        toastr.warning("Por favor escriba la fecha de nacimiento de la persona, este dato no debe quedar vacio", "Falta un dato");
        validacionExitosa = false;
      }else if(persona.sexo == ''){
        toastr.warning("Por favor indique si es Femenino o Masculino, este dato no debe quedar vacio", "Falta un dato");
        validacionExitosa = false;
      }else if(persona.estadocivil == ''){
        toastr.warning("Por favor seleccione el estado civil de la persona, este dato no debe quedar vacio", "Falta un dato");
        validacionExitosa = false;
      }
      return validacionExitosa;
    },
    toFormData: function(obj){
      var form_data = new FormData()
      for(var key in obj){
        form_data.append(key, obj[key])
      }
      return form_data;
    },
    limpiarDatos: function(){
      this.newPersona = {
        identidad: '',
        fechanac: '',
        nombres: '',
        apellidos: '',
        sexo: '',
        estadocivil: '',
        casadocon: '',
        nacionalidad: '',
        resumen: '',
      };
    },
    actualizar: function(persona){
      if(this.validar(persona)===true){
        var formData = this.toFormData(this.clickedPersona);
        let config = {
          method: 'post',
          maxBodyLength: Infinity,
          url: 'ApiRestFull/apiPersonas.php?action=update',
          data : formData
        };
        var obj = this;
        axios.request(config)
        .then((response) => {
          toastr.success(response.data.message, "Registro actualizado");
          $('#EditPersonaModal').modal('hide');
          obj.getAllPersons();
        })
        .catch((error) => {
          console.log(error);
        });
      }
    },
    eliminar: function(persona){
      var formData = this.toFormData(this.clickedPersona);
      let config = {
        method: 'post',
        maxBodyLength: Infinity,
        url: 'ApiRestFull/apiPersonas.php?action=delete',
        data : formData
      };
      var obj = this;
      axios.request(config)
      .then((response) => {
        toastr.success(response.data.message, "Registro borrado");
        $('#DeletePersonaModal').modal('hide');
        obj.getAllPersons();
      })
      .catch((error) => {
        console.log(error);
      });
    },
  },
});
