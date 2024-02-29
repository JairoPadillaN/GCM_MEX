$("#guardar").click(function(){
        console.log("faaaaaaaaaaaaaaf");
		$("#formularioABC").validate({
            ignore: [],
			rules: {
                idc: {required:true},
                idSucursal: {required:true},
                complementoGCMid: {required:true},
                marca: {required:true},
                vistaSuperior: {required:true},
                textoQr: {required:true},
			},
			messages: 
			{
                idc: {required: 'Elige un cliente'},
                idSucursal: {required: 'Elige una sucursal'},
                complementoGCMid: {required: 'El campo es requeridoooo'},
                marca: {required: 'El campo es requeridoooo'},
                vistaSuperior: {required: 'El campo es requeridoooo'},
                textoQr: {required: 'El campo es requeridoooo'}
			}
		});
		
	});