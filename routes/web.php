<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 use App\viajes;
Route::get('/', function () {
    return view('portada');
});



// RFQs
Route::get('reporteRfqs', 'rfqController@reporteRfqs')->name('reporteRfqs');
Route::get('altaRfq', 'rfqController@altaRfq')->name('altaRfq');
Route::post('guardarRfq', 'rfqController@guardarRfq')->name('guardarRfq');
Route::get('modificarRfq/{idrfq}', 'rfqController@modificarRfq')->name('modificarRfq');
Route::post('guardarModificacionRfq', 'rfqController@guardarModificacionRfq')->name('guardarModificacionRfq');
Route::get('desactivarRfq/{idrfq}', 'rfqController@desactivarRfq')->name('desactivarRfq');
Route::get('activarRfq/{idrfq}', 'rfqController@activarRfq')->name('activarRfq');
Route::get('obtSucursales', 'rfqController@obtSucursales')->name('obtSucursales');
Route::get('obtCiudad', 'rfqController@obtCiudad')->name('obtCiudad');
Route::get('obtGcmidMarca', 'rfqController@obtGcmidMarca')->name('obtGcmidMarca');
Route::get('obtContactos', 'rfqController@obtContactos')->name('obtContactos');
Route::get('obtTipos', 'rfqController@obtTipos')->name('obtTipos');
Route::get('obtSkus', 'rfqController@obtSkus')->name('obtSkus');
Route::get('obtModelo', 'rfqController@obtModelo')->name('obtModelo');
Route::get('obtGenerico', 'rfqController@obtGenerico')->name('obtGenerico');
Route::get('obtGenericoSku', 'rfqController@obtGenericoSku')->name('obtGenericoSku');
Route::post('agregarProductosRfq', 'rfqController@agregarProductosRfq')->name('agregarProductosRfq');
Route::get('borrarProductosRfq', 'rfqController@borrarProductosRfq')->name('borrarProductosRfq');
Route::get('verFotosRfq', 'rfqController@verFotosRfq')->name('verFotosRfq');
Route::get('editarInfoEstatus', 'rfqController@editarInfoEstatus')->name('editarInfoEstatus');
Route::get('guardarDatosEstatus', 'rfqController@guardarDatosEstatus')->name('guardarDatosEstatus');
Route::get('editarInfoFecha', 'rfqController@editarInfoFecha')->name('editarInfoFecha');
Route::get('guardarDatosFecha', 'rfqController@guardarDatosFecha')->name('guardarDatosFecha');
Route::get('editarInfoObservacion', 'rfqController@editarInfoObservacion')->name('editarInfoObservacion');
Route::get('guardarDatosObservacion', 'rfqController@guardarDatosObservacion')->name('guardarDatosObservacion');
Route::get('detalleRfq/{idrfq}', 'rfqController@detalleRfq')->name('detalleRfq');
Route::get('comboSucursal', 'rfqController@comboSucursal')->name('comboSucursal');
Route::get('comboContacto', 'rfqController@comboContacto')->name('comboContacto');
Route::get('comboTipo', 'rfqController@comboTipo')->name('comboTipo');
Route::get('comboSKU', 'rfqController@comboSKU')->name('comboSKU');
Route::get('editarProductoRfq', 'rfqController@editarProductoRfq')->name('editarProductoRfq');
Route::post('/eliminarFoto1/{id}', 'rfqController@eliminarFoto1')->name('eliminarFoto1');
Route::post('/eliminarFoto2/{id}', 'rfqController@eliminarFoto2')->name('eliminarFoto2');
Route::post('/eliminarFoto3/{id}', 'rfqController@eliminarFoto3')->name('eliminarFoto3');
Route::post('/eliminarFoto4/{id}', 'rfqController@eliminarFoto4')->name('eliminarFoto4');
Route::post('/eliminarFoto5/{id}', 'rfqController@eliminarFoto5')->name('eliminarFoto5');
Route::post('guardarModifProd', 'rfqController@guardarModifProd')->name('guardarModifProd');
Route::post('guardarModifProdG', 'rfqController@guardarModifProdG')->name('guardarModifProdG');
Route::get('tablaActivos', 'rfqController@tablaActivos')->name('tablaActivos');
Route::get('tablaEntregados', 'rfqController@tablaEntregados')->name('tablaEntregados');
Route::get('tablaCancelados', 'rfqController@tablaCancelados')->name('tablaCancelados');

Route::get('reporteOrdenesCompra2', 'ordenCompraController@reporteOrdenesCompra2')->name('reporteOrdenesCompra2');
Route::get('tablaOrdenesActivas', 'ordenCompraController@tablaOrdenesActivas')->name('tablaOrdenesActivas');
Route::get('tablaOrdenesCanceladas', 'ordenCompraController@tablaOrdenesCanceladas')->name('tablaOrdenesCanceladas');

Route::get('editarProductoOrden', 'ordenCompraController@editarProductoOrden')->name('editarProductoOrden');
Route::post('guardarModifProductoOrden', 'ordenCompraController@guardarModifProductoOrden')->name('guardarModifProductoOrden');

Route::get('guardarCostoEnvio', 'partesVentaController@guardarCostoEnvio')->name('guardarCostoEnvio');
Route::get('borrarCostoEnvio','partesVentaController@borrarCostoEnvio')->name('borrarCostoEnvio');

//Rutas reporte de ventas (Excel) Servicios->Reportes->Reporte global de ventas
Route::get('reporteGlobalVentas', function(){ return view('reporteGlobalVentas');})->name('reporteGlobalVentas');
Route::get('reporteGVentas', 'reporteDeGastozController@reporteGVentas')->name('reporteGVentas');

Route::get('reporteGastosOperativos', 'reporteDeGastozController@reporteGastosOperativos')->name('reporteGastosOperativos');
Route::get('reporteCostoVenta', 'reporteDeGastozController@reporteCostoVenta')->name('reporteCostoVenta');
// Modal detalle
Route::get('modalDetalle', 'rfqController@modalDetalle')->name('modalDetalle');

//Cardex
Route::get('reporteCardex/{idrfq}', 'CardexController@show')->name('reporteCardex');
//Notificaciones
Route::get('modalNotificacion', 'rfqController@modalNotificacion')->name('modalNotificacion');
Route::get('visto', 'rfqController@visto')->name('visto');
Route::get('cerrar', 'rfqController@cerrar')->name('cerrar');

//Archivos Contables
Route::get('archivosContab', 'ArchivoscontableController@archivosContab')->name('archivosContab');
Route::get('altaContable', 'ArchivoscontableController@altaContable')->name('altaContable');
Route::post('guardarContable', 'ArchivoscontableController@guardarContable')->name('guardarContable');
Route::get('activarCont/{idac}', 'ArchivoscontableController@activarCont')->name('activarCont');
Route::get('desactivarCont/{idac}', 'ArchivoscontableController@desactivarCont')->name('desactivarCont');
Route::get('altaArchivos/{idac}', 'ArchivoscontableController@altaArchivos')->name('altaArchivos');
Route::post('editarContable', 'ArchivoscontableController@editarContable')->name('editarContable');
Route::post('guardarArchivos', 'ArchivoscontableController@guardarArchivos')->name('guardarArchivos');
Route::get('borrarDetalle', 'ArchivoscontableController@borrarDetalle' )->name('borrarDetalle');
Route::get('reporteDetalle', 'ArchivoscontableController@reporteDetalle')->name('reporteDetalle');

//Reporte SKU Cotizaciones
Route::get('reporteSKUCotizacion','reporteSKUServicioController@reporteSKUCotizacion')->name('reporteSKUCotizacion');

//Reporte SKU top Ventas
Route::get('reporteTopVentas','reporteSKUServicioController@reporteSKUTotalizado')->name('reporteTopVentas');
Route::get('reporteTopVentasFiltro','reporteSKUServicioController@reporteSKUTotalizadoFiltro')->name('reporteTopVentas.filtro');

//Reporte SKU Vendidos por servicio
Route::get('skuVendido', 'reporteSKUServicioController@skuVendido')->name('skuVendido');

//Otros Ingresos
Route::get('reporteOtrosing', 'OtrosIngresosController@reporteOtrosing')->name('reporteOtrosing');
Route::get('altaIngresos', 'OtrosIngresosController@altaIngresos')->name('altaIngresos');
Route::post('guardarIngreso', 'OtrosIngresosController@guardarIngreso')->name('guardarIngreso');
Route::get('editarIngreso/{idoi}', 'OtrosIngresosController@editarIngreso')->name('editarIngreso');
Route::post('modificarIngreso', 'OtrosIngresosController@modificarIngreso')->name('modificarIngreso');
Route::get('eliminarIngreso/{idoi}', 'OtrosIngresosController@eliminarIngreso')->name('eliminarIngreso');
Route::get('restaurarIngreso/{idoi}', 'OtrosIngresosController@restaurarIngreso')->name('restaurarIngreso');

//CRUD CITAS
Route::get('altaCitas','CitasController@altaCitas')->name('altaCitas');
Route::get('comboSucursales','CitasController@comboSucursales')->name('comboSucursales');
Route::get('comboSucursalesActivo','CitasController@comboSucursalesActivo')->name('comboSucursalesActivo');
Route::get('comboCon','CitasController@comboCon')->name('comboCon');
Route::get('comboSucEditar','CitasController@comboSucEditar')->name('comboSucEditar');
Route::get('comboConEditar','CitasController@comboConEditar')->name('comboConEditar');
Route::POST('GuardarCitas','CitasController@GuardarCitas')->name('GuardarCitas');
Route::get('reporteCitas','CitasController@reporteCitas')->name('reporteCitas');
Route::get('reporteCitasAsignada','CitasController@reporteCitasAsignada')->name('reporteCitasAsignada');
Route::get('reporteCitasCreada','CitasController@reporteCitasCreada')->name('reporteCitasCreada');
Route::get('detalleCitas/{idCita}','CitasController@detalleCitas')->name('detalleCitas');
Route::get('modificarCita/{idCita}','CitasController@modificarCita')->name('modificarCita');
Route::get('modificarCitaAsignada/{idCita}','CitasController@modificarCitaAsignada')->name('modificarCitaAsignada');
Route::POST('editarCitas','CitasController@editarCitas')->name('editarCitas');
Route::POST('editarCitasAsignada','CitasController@editarCitasAsignada')->name('editarCitasAsignada');
Route::get('eliminarCitas/{idCita}','CitasController@eliminarCitas')->name('eliminarCitas');
Route::get('restaurarCitas/{idCita}','CitasController@restaurarCitas')->name('restaurarCitas');
Route::get('sucursalCita','CitasController@sucursalCita')->name('sucursalCita');
Route::get('enviarCorreo','CitasController@enviarCorreo')->name('enviarCorreo');
Route::get('editarModal','CitasController@editarModal')->name('editarModal');
Route::get('infoCita','CitasController@infoCita')->name('infoCita');

//CRUD USUARIOS
Route::get('AltaUsuarios','UsuariosController@AltaUsuarios')->name('AltaUsuarios');
Route::POST('GuardarUsuarios','UsuariosController@GuardarUsuarios')->name('GuardarUsuarios');
Route::get('ReporteUsuarios','UsuariosController@ReporteUsuarios')->name('ReporteUsuarios');
Route::get('modificarUsuario/{idu}','UsuariosController@modificarUsuario')->name('modificarUsuario');
Route::POST('editarUsuario','UsuariosController@editarUsuario')->name('editarUsuario');
Route::get('eliminarUsuario/{idu}','UsuariosController@eliminarUsuario')->name('eliminarUsuario');
Route::get('restaurarUsuario/{idu}','UsuariosController@restaurarUsuario')->name('restaurarUsuario');
Route::get('asignarUsuarios','UsuariosController@asignarUsuarios')->name('asignarUsuarios');
Route::get('borrarUsuariosAsignados','UsuariosController@borrarUsuariosAsignados')->name('borrarUsuariosAsignados');

//CRUD TIPOS DE GASTO
Route::get('AltaTipoGastos','TipoGastosController@AltaTipoGastos')->name('AltaTipoGastos');
Route::POST('GuardarTipoGastos','TipoGastosController@GuardarTipoGastos')->name('GuardarTipoGastos');
Route::get('ReporteTipoGastos','TipoGastosController@ReporteTipoGastos')->name('ReporteTipoGastos');
Route::get('modificarTipoGastos/{idTipoGastos}','TipoGastosController@modificarTipoGastos')->name('modificarTipoGastos');
Route::POST('editarTipoGastos','TipoGastosController@editarTipoGastos')->name('editarTipoGastos');
Route::get('eliminarTipoGastos/{idTipoGastos}','TipoGastosController@eliminarTipoGastos')->name('eliminarTipoGastos');
Route::get('restaurarTipoGastos/{idTipoGastos}','TipoGastosController@restaurarTipoGastos')->name('restaurarTipoGastos');


/////CRUD CLIENTES
Route::get('altaClientes','ClientesController@altaClientes')->name('altaClientes');
Route::POST('GuardarClientes','ClientesController@GuardarClientes')->name('GuardarClientes');
Route::get('consultaClientes','ClientesController@consultaClientes')->name('consultaClientes');
Route::get('reporteClientesAbajo','ClientesController@reporteClientesAbajo')->name('reporteClientesAbajo');
Route::get('eliminarClientes','ClientesController@eliminarClientes')->name('eliminarClientes');
Route::get('modificaClientes/{idc}','ClientesController@modificaClientes')->name('modificaClientes');
Route::POST('modificaClientes2','ClientesController@modificaClientes2')->name('modificaClientes2');
// REPORTE RECEPCION DE EQUIPOS 
Route::get('reporteEquiposServicios','ReporteRecepciondeEquipoController@reporteEquiposServicios')->name('reporteEquiposServicios');
Route::get('dataReporteEquiposServicios','ReporteRecepciondeEquipoController@dataReporteEquiposServicios')->name('dataReporteEquiposServicios');
Route::get('reporteEquiposServiciosAbajo','ReporteRecepciondeEquipoController@reporteEquiposServiciosAbajo')->name('reporteEquiposServiciosAbajo');
Route::POST('excelEquiposServicios','ReporteRecepciondeEquipoController@excelEquiposServicios')->name('excelEquiposServicios');
Route::post('excelEquiposServicios','ReporteRecepciondeEquipoController@excelEquiposServicios')->name('excelEquiposServicios');
//---------------------------------------
Route::get('reporteEquiposPorTecnico','reporteEquiposPorTecnicoController@reporteEquiposPorTecnico')->name('reporteEquiposPorTecnico');
Route::get('App/reporteEquiposPorTecnico','reporteEquiposPorTecnicoController@reporteEquiposPorTecnico_app')->name('reporteEquiposPorTecnico_app');
Route::get('reporteEquiposPorTecnicoAbajo','reporteEquiposPorTecnicoController@reporteEquiposPorTecnicoAbajo')->name('reporteEquiposPorTecnicoAbajo');
Route::POST('excelEquiposPorTecnico','reporteEquiposPorTecnicoController@excelEquiposPorTecnico')->name('excelEquiposPorTecnico');
Route::post('excelEquiposPorTecnico','reporteEquiposPorTecnicoController@excelEquiposPorTecnico')->name('excelEquiposPorTecnico');

/////CRUD Sucursales
Route::get('altaSucursales','SucursalesController@altaSucursales')->name('altaSucursales');
Route::POST('GuardarSucursales','SucursalesController@GuardarSucursales')->name('GuardarSucursales');
Route::get('reporteSucursales','SucursalesController@reporteSucursales')->name('reporteSucursales');
Route::get('reporteSucursalesAbajo','SucursalesController@reporteSucursalesAbajo')->name('reporteSucursalesAbajo');
Route::get('eliminarSucursales/{idSucursal}','SucursalesController@eliminarSucursales')->name('eliminarSucursales');
Route::POST('editarSucursales','SucursalesController@editarSucursales')->name('editarSucursales');
Route::get('modificarSucursales/{idSucursal}','SucursalesController@modificarSucursales')->name('modificarSucursales');
Route::get('restaurarSucursales/{idSucursal}','SucursalesController@restaurarSucursales')->name('restaurarSucursales');
Route::get('contactoSuc','SucursalesController@contactoSuc')->name('contactoSuc');
Route::get('borrarCont','SucursalesController@borrarCont')->name('borrarCont');


Route::get('reporteAbiertasPrueba','SucursalesController@reporteAbiertasPrueba')->name('reporteAbiertasPrueba');
Route::get('reporteCerradasPrueba','SucursalesController@reporteCerradasPrueba')->name('reporteCerradasPrueba');
Route::get('reporteSeguimiento','SucursalesController@reporteSeguimiento')->name('reporteSeguimiento');
Route::POST('anexgridCreadas','SucursalesController@anexgridCreadas')->name('anexgridCreadas');



//CRUD BANCOS
Route::get('altaBancos','BancosController@altaBancos')->name('altaBancos');
Route::POST('GuardarBancos','BancosController@GuardarBancos')->name('GuardarBancos');
Route::get('reporteBancos','BancosController@reporteBancos')->name('reporteBancos');
Route::get('eliminarBancos/{idb}','BancosController@eliminarBancos')->name('eliminarBancos');
Route::get('restaurarBancos/{idb}','BancosController@restaurarBancos')->name('restaurarBancos');
Route::get('modificarBancos/{idb}','BancosController@modificarBancos')->name('modificarBancos');
Route::POST('editarBancos','BancosController@editarBancos')->name('editarBancos');

Route::get('AltaMarcas','MarcasController@AltaMarcas')->name('AltaMarcas');
Route::POST('GuardarMarcas','MarcasController@GuardarMarcas')->name('GuardarMarcas');

//CRUD VEHICULOS
Route::get('AltaVehiculos','VehiculosController@AltaVehiculos')->name('AltaVehiculos');
Route::POST('GuardarVehiculos','VehiculosController@GuardarVehiculos')->name('GuardarVehiculos');
Route::get('ReporteVehiculos','VehiculosController@ReporteVehiculos')->name('ReporteVehiculos');
Route::get('modificarVehiculos/{idVehiculo}','VehiculosController@modificarVehiculos')->name('modificarVehiculos');
Route::POST('editarVehiculos','VehiculosController@editarVehiculos')->name('editarVehiculos');
Route::get('eliminarVehiculos/{idVehiculo}','VehiculosController@eliminarVehiculos')->name('eliminarVehiculos');
Route::get('restaurarVehiculos/{idVehiculo}','VehiculosController@restaurarVehiculos')->name('restaurarVehiculos');

/////MOVIMIENTOS
Route::get('altaMovimientos','MovimientosController@altaMovimientos')->name('altaMovimientos');
Route::POST ('GuardarMov','MovimientosController@GuardarMov')->name('GuardarMov');
Route::get('reporteMovimientos','MovimientosController@reporteMovimientos')->name('reporteMovimientos');
Route::get('modificarMovimientos/{idm}','MovimientosController@modificarMovimientos')->name('modificarMovimientos');
Route::POST('editarMovimientos','MovimientosController@editarMovimientos')->name('editarMovimientos');
Route::get('eliminarMovimientos/{idm}','MovimientosController@eliminarMovimientos')->name('eliminarMovimientos');
Route::get('restaurarMovimientos/{idm}','MovimientosController@restaurarMovimientos')->name('restaurarMovimientos');

/////MARCAS
Route::get('altaMarcas','MarcasController@altaMarcas')->name('altaMarcas');
Route::POST ('GuardarMarcas','MarcasController@GuardarMarcas')->name('GuardarMarcas');
Route::get('reporteMarcas','MarcasController@reporteMarcas')->name('reporteMarcas');
Route::get('modificarMarcas/{idMarca}','MarcasController@modificarMarcas')->name('modificarMarcas');
Route::POST('editarMarcas','MarcasController@editarMarcas')->name('editarMarcas');
Route::get('eliminarMarcas/{idMarca}','MarcasController@eliminarMarcas')->name('eliminarMarcas');
Route::get('restaurarMarcas/{idMarca}','MarcasController@restaurarMarcas')->name('restaurarMarcas');

/////Tipos
Route::get('altaTipoEquipos','TipoEquiposController@altaTipoEquipos')->name('altaTipoEquipos');
Route::POST ('GuardarTipoEquipos','TipoEquiposController@GuardarTipoEquipos')->name('GuardarTipoEquipos');
Route::get('reporteTipoEquipos','TipoEquiposController@reporteTipoEquipos')->name('reporteTipoEquipos');
Route::get('modificarTipoEquipos/{idTipoEquipo}','TipoEquiposController@modificarTipoEquipos')->name('modificarTipoEquipos');
Route::POST('editarTipoEquipos','TipoEquiposController@editarTipoEquipos')->name('editarTipoEquipos');
Route::get('eliminarTipoEquipos/{idTipoEquipo}','TipoEquiposController@eliminarTipoEquipos')->name('eliminarTipoEquipos');
Route::get('restaurarTipoEquipos/{idTipoEquipo}','TipoEquiposController@restaurarTipoEquipos')->name('restaurarTipoEquipos');

/////Subtipos
Route::get('altaSubtipoEquipos','SubtipoEquiposController@altaSubtipoEquipos')->name('altaSubtipoEquipos');
Route::POST ('GuardarSubtipoEquipos','SubtipoEquiposController@GuardarSubtipoEquipos')->name('GuardarSubtipoEquipos');
Route::get('reporteSubtipoEquipos','SubtipoEquiposController@reporteSubtipoEquipos')->name('reporteSubtipoEquipos');
Route::get('modificarSubtipoEquipos/{idTipoEquipo}','SubtipoEquiposController@modificarSubtipoEquipos')->name('modificarSubtipoEquipos');
Route::POST('editarSubtipoEquipos','SubtipoEquiposController@editarSubtipoEquipos')->name('editarSubtipoEquipos');
Route::get('eliminarSubtipoEquipos/{idTipoEquipo}','SubtipoEquiposController@eliminarSubtipoEquipos')->name('eliminarSubtipoEquipos');
Route::get('restaurarSubtipoEquipos/{idTipoEquipo}','SubtipoEquiposController@restaurarSubtipoEquipos')->name('restaurarSubtipoEquipos');
// Route::get('pdfSubtipoEquipos','SubtipoEquiposController@crearPdf')->name('pdfSubtipoEquipos');
// route::get('qrcode','SubtipoEquiposController@make');


/////SERVICIOS
Route::get('altaServicios','ServiciosController@altaServicios')->name('altaServicios');
Route::POST('GuardarServicios','ServiciosController@GuardarServicios')->name('GuardarServicios');
Route::get('reporteServicios','ServiciosController@reporteServicios')->name('reporteServicios');
Route::get('modificarServicios/{idServicio}','ServiciosController@modificarServicios')->name('modificarServicios');
Route::POST('editarServicios','ServiciosController@editarServicios')->name('editarServicios');
Route::get('eliminarServicios/{idServicio}','ServiciosController@eliminarServicios')->name('eliminarServicios');
Route::get('restaurarServicios/{idServicio}','ServiciosController@restaurarServicios')->name('restaurarServicios');

/////CRUD VIAJES
//Salida de viaje
Route::get('altaSalidaViajes','ViajesController@altaSalidaViajes')->name('altaSalidaViajes');
Route::POST('GuardarSalidaViajes','ViajesController@GuardarSalidaViajes')->name('GuardarSalidaViajes');
//Finalizar viaje
Route::get('altaRegresoViajes/{idViaje}','ViajesController@altaRegresoViajes')->name('altaRegresoViajes');
Route::POST('GuardarRegresoViajes','ViajesController@GuardarRegresoViajes')->name('GuardarRegresoViajes');
//Mostrar detalle de los viajes
Route::get('detalleViajes/{idViaje}','ViajesController@detalleViajes')->name('detalleViajes');
//reporte de viajes
Route::get('reporteViajes','ViajesController@reporteViajes')->name('reporteViajes');
Route::get('reporteViajesAbajo','ViajesController@reporteViajesAbajo')->name('reporteViajesAbajo');
Route::POST('filtroViajes','ViajesController@filtroViajes')->name('filtroViajes');
//modificar salida
Route::get('modificarSalidaViajes/{idViaje}','ViajesController@modificarSalidaViajes')->name('modificarSalidaViajes');
Route::POST('editarSalidaViajes','ViajesController@editarSalidaViajes')->name('editarSalidaViajes');
//eliminar Salida
Route::get('eliminarSalidaViajes/{idViaje}','ViajesController@eliminarSalidaViajes')->name('eliminarSalidaViajes');
Route::get('restaurarSalidaViajes/{idViaje}','ViajesController@restaurarSalidaViajes')->name('restaurarSalidaViajes');
//modificar salida
Route::get('modificarViajes/{idViaje}','ViajesController@modificarViajes')->name('modificarViajes');
Route::POST('editarViajes','ViajesController@editarViajes')->name('editarViajes');
Route::get('comboca','ViajesController@comboca')->name('comboca');
Route::get('asignarServicioAviaje','ViajesController@asignarServicioAviaje')->name('asignarServicioAviaje');
Route::get('eliminarServicioAviaje','ViajesController@eliminarServicioAviaje')->name('eliminarServicioAviaje');


////////FACTURAS

Route::get('altaFacturas','facturasController@altaFacturas')->name('altaFacturas');
Route::POST('GuardarFacturas','facturasController@GuardarFacturas')->name('GuardarFacturas');
Route::get('reporteFacturas','facturasController@reporteFacturas')->name('reporteFacturas');
Route::get('reporteFacturasAbajo','facturasController@reporteFacturasAbajo')->name('reporteFacturasAbajo');
Route::get('eliminarFacturas/{idFactura}','facturasController@eliminarFacturas')->name('eliminarFacturas');
Route::get('restaurarFacturas/{idFactura}','facturasController@restaurarFacturas')->name('restaurarFacturas');
Route::get('modificarFacturas/{idFactura}','facturasController@modificarFacturas')->name('modificarFacturas');
Route::get('activarServicio/{idFactura}','facturasController@activarServicio')->name('activarServicio');
Route::get('cerrarServicio/{idFactura}','facturasController@cerrarServicio')->name('cerrarServicio');
Route::POST('get-data-facturas','facturasController@getDataFacturas');
Route::POST('get-data-facturas-abajo','facturasController@getDataFacturasAbajo');
Route::POST('editarFacturas','facturasController@editarFacturas')->name('editarFacturas');
Route::get('comboca1','facturasController@comboca1')->name('comboca1');
Route::get('comboCotizaciones','facturasController@comboCotizaciones')->name('comboCotizaciones');
Route::get('asignarCotizacion','facturasController@asignarCotizacion')->name('asignarCotizacion');
Route::get('ordenDeCompra','facturasController@ordenDeCompra')->name('ordenDeCompra');
Route::get('agregarOrden','facturasController@agregarOrden')->name('agregarOrden');
Route::post('agregarArchivos','facturasController@agregarArchivos')->name('agregarArchivos');
Route::get('borrarArchivosFac','facturasController@borrarArchivosFac')->name('borrarArchivosFac');
Route::POST('excelOC','facturasController@excelOC')->name('excelOC');
Route::get('agregarDatosPago','facturasController@agregarDatosPago')->name('agregarDatosPago');
Route::get('borrarDatosPago','facturasController@borrarDatosPago')->name('borrarDatosPago');
Route::get('editarInfoDatosPago','facturasController@editarInfoDatosPago')->name('editarInfoDatosPago');
Route::get('guardarDatosPagoEdit','facturasController@guardarDatosPagoEdit')->name('guardarDatosPagoEdit');

Route::get('downloadPago','facturasController@downloadPago')->name('downloadPago');

Route::get('/export', 'HomeController@export');
Route::get('/expor', 'HomeController@expor');

//Reporte facturas de Pruebas (New)
Route::get('reporteFacturacion','facturasController@reporteFacturacion')->name('reporteFacturacion');

Route::get('agregarVendedorPorcentaje','facturasController@agregarVendedorPorcentaje')->name('agregarVendedorPorcentaje');
Route::get('borrarVendedorPorcentaje','facturasController@borrarVendedorPorcentaje')->name('borrarVendedorPorcentaje');
//Archivos facturas

Route::get('formulario', 'archivosController@index');

///// RECEPCIÓN DE EQUIPOS
Route::get('altaEquipos','EquiposController@altaEquipos')->name('altaEquipos');
Route::POST('GuardarEquipos','EquiposController@GuardarEquipos')->name('GuardarEquipos');
Route::get('reporteEquipos','EquiposController@reporteEquipos')->name('reporteEquipos');
Route::get('reporteEquiposAbajo','EquiposController@reporteEquiposAbajo')->name('reporteEquiposAbajo');
Route::get('reporteGCM','EquiposController@reporteGCM')->name('reporteGCM');
Route::get('reporteCYM','EquiposController@reporteCYM')->name('reporteCYM');
Route::get('reporteGCMyCYM','EquiposController@reporteGCMyCYM')->name('reporteGCMyCYM');
Route::get('modificarEquipos/{idEquipos}','EquiposController@modificarEquipos')->name('modificarEquipos');
Route::POST('editarEquipos','EquiposController@editarEquipos')->name('editarEquipos');
Route::get('eliminarEquipos/{idEquipos}','EquiposController@eliminarEquipos')->name('eliminarEquipos');
Route::get('restaurarEquipos/{idEquipos}','EquiposController@restaurarEquipos')->name('restaurarEquipos');
Route::get('comboca1','EquiposController@comboca1')->name('comboca1');
Route::get('comboca2','EquiposController@comboca2')->name('comboca2');
Route::get('comboca3','EquiposController@comboca3')->name('comboca3');
Route::get('generagcmid','EquiposController@generagcmid')->name('generagcmid');
Route::get('generafolio','EquiposController@generafolio')->name('generafolio');
Route::get('generacym','EquiposController@generacym')->name('generacym');
Route::get('generasurja','EquiposController@generasurja')->name('generasurja');
Route::get('info','EquiposController@info')->name('info');
Route::get('dividirEquipos/{idEquipos}','EquiposController@dividirEquipos')->name('dividirEquipos');
Route::get('altaDividirParteAbajo','EquiposController@altaDividirParteAbajo')->name('altaDividirParteAbajo');
Route::POST('guardarParteCreada','EquiposController@guardarParteCreada')->name('guardarParteCreada');
Route::get('editarParteAbajo','EquiposController@editarParteAbajo')->name('editarParteAbajo');
Route::POST('guardarEdicionParte','EquiposController@guardarEdicionParte')->name('guardarEdicionParte');
Route::get('borrarParteCreada','EquiposController@borrarParteCreada')->name('borrarParteCreada');
Route::get('serviciosParteCreada','EquiposController@serviciosParteCreada')->name('serviciosParteCreada');
Route::get('comboServTaller','EquiposController@comboServTaller')->name('comboServTaller');
Route::get('preciosServicios','EquiposController@preciosServicios')->name('preciosServicios');
Route::get('preciosRefacciones','EquiposController@preciosRefacciones')->name('preciosRefacciones');
Route::get('asignarServicioTallerParte','EquiposController@asignarServicioTallerParte')->name('asignarServicioTallerParte');
Route::get('borrarServicioReparacionParte','EquiposController@borrarServicioReparacionParte')->name('borrarServicioReparacionParte');
Route::get('agregarRefaccionParte','EquiposController@agregarRefaccionParte')->name('agregarRefaccionParte');
Route::get('comboMarcas','EquiposController@comboMarcas')->name('comboMarcas');
Route::get('comboTipoRefaccionesParte','EquiposController@comboTipoRefaccionesParte')->name('comboTipoRefaccionesParte');
Route::get('comboRefacciones','EquiposController@comboRefacciones')->name('comboRefacciones');
Route::get('comboTipoRefaccionCambio','EquiposController@comboTipoRefaccionCambio')->name('comboTipoRefaccionCambio');
Route::get('comboRefaccionesCambio','EquiposController@comboRefaccionesCambio')->name('comboRefaccionesCambio');
Route::get('datosRefaccion','EquiposController@datosRefaccion')->name('datosRefaccion');
Route::get('datosRefaccion2','EquiposController@datosRefaccion2')->name('datosRefaccion2');
Route::get('eliminarCambio','EquiposController@eliminarCambio')->name('eliminarCambio');
Route::get('borrarRefaccionParte','EquiposController@borrarRefaccionParte')->name('borrarRefaccionParte');
Route::get('pdfQrParte/{idParteServicios}','EquiposController@pdfQrParte')->name('pdfQrParte');
Route::get('qrcodeEquipo','EquiposController@qrcodeEquipo');
Route::get('nuevaRefaccion','EquiposController@nuevaRefaccion')->name('nuevaRefaccion');
Route::POST('filtroEquipos','EquiposController@filtroEquipos')->name('filtroEquipos');
Route::POST('filtroEquiposAbajo','EquiposController@filtroEquiposAbajo')->name('filtroEquiposAbajo');
Route::get('pdfEquipoReparado','EquiposController@pdfEquipoReparado')->name('pdfEquipoReparado');
Route::post('excelEquipos','EquiposController@excelEquipos')->name('excelEquipos');
Route::post('excelEquiposEmpresa','EquiposController@excelEquiposEmpresa')->name('excelEquiposEmpresa');
Route::post('excelTaller','EquiposController@excelTaller')->name('excelTaller');
Route::get('activarEquipo/{idEquipos}','EquiposController@activarEquipo')->name('activarEquipo');
Route::get('cerrarEquipo/{idEquipos}','EquiposController@cerrarEquipo')->name('cerrarEquipo');


// reporte por empresa
Route::get('reportePorEmpresa','EquiposController@reportePorEmpresa')->name('reportePorEmpresa');
Route::get('dataReportePorEmpresa','EquiposController@dataReportePorEmpresa')->name('dataReportePorEmpresa');
Route::get('reportePorEmpresaAbajo','EquiposController@reportePorEmpresaAbajo')->name('reportePorEmpresaAbajo');
Route::get('detallePorEmpresa/{idEquipos}','EquiposController@detallePorEmpresa')->name('detallePorEmpresa');
Route::get('detallePorParte','EquiposController@detallePorParte')->name('detallePorParte');

// reporte por taller
Route::get('reportePorTaller','EquiposController@reportePorTaller')->name('reportePorTaller');
Route::get('reportePorTallerAbajo','EquiposController@reportePorTallerAbajo')->name('reportePorTallerAbajo');
Route::get('comboModelos','EquiposController@comboModelos')->name('comboModelos');
Route::get('descargarPDF','EquiposController@descargarPDF')->name('descargarPDF');
Route::get('modeloSel','EquiposController@modeloSel')->name('modeloSel');
Route::get ('/header','header@header')->name('header');
Route::POST('filtroTaller','EquiposController@filtroTaller')->name('filtroTaller');








// CRUD ESTATUS DE REPARACIÓN
Route::get('altaEstatusReparaciones','estatusReparacionesController@altaEstatusReparaciones')->name('altaEstatusReparaciones');
Route::POST('guardarEstatusReparaciones','estatusReparacionesController@guardarEstatusReparaciones')->name('guardarEstatusReparaciones');
Route::get('reporteEstatusReparaciones','estatusReparacionesController@reporteEstatusReparaciones')->name('reporteEstatusReparaciones');
Route::get('modificarEstatus/{idEstatus}','estatusReparacionesController@modificarEstatus')->name('modificarEstatus');
Route::POST('editarEstatus','estatusReparacionesController@editarEstatus')->name('editarEstatus');
Route::get('eliminarEstatus/{idEstatus}','estatusReparacionesController@eliminarEstatus')->name('eliminarEstatus');
Route::get('restaurarEstatus/{idEstatus}','estatusReparacionesController@restaurarEstatus')->name('restaurarEstatus');

// CRUD TALLERES
Route::get('altaTaller','talleresController@altaTaller')->name('altaTaller');
Route::POST('guardarTaller','talleresController@guardarTaller')->name('guardarTaller');
Route::get('reporteTaller','talleresController@reporteTaller')->name('reporteTaller');
Route::get('modificarTaller/{idTaller}','talleresController@modificarTaller')->name('modificarTaller');
Route::POST('editarTaller','talleresController@editarTaller')->name('editarTaller');
Route::get('eliminarTaller/{idTaller}','talleresController@eliminarTaller')->name('eliminarTaller');
Route::get('restaurarTaller/{idTaller}','talleresController@restaurarTaller')->name('restaurarTaller');

// CRUD TIPO REFACCIONES
Route::get('altaTipoRef','tipoRefController@altaTipoRef')->name('altaTipoRef');
Route::POST('guardarTipoRef','tipoRefController@guardarTipoRef')->name('guardarTipoRef');
Route::get('reporteTipoRef','tipoRefController@reporteTipoRef')->name('reporteTipoRef');
Route::get('modificarTipoRef/{idTipoRefacciones}','tipoRefController@modificarTipoRef')->name('modificarTipoRef');
Route::POST('editarTipoRef','tipoRefController@editarTipoRef')->name('editarTipoRef');
Route::get('eliminarTipoRef/{idTipoRefacciones}','tipoRefController@eliminarTipoRef')->name('eliminarTipoRef');
Route::get('restaurarTipoRef/{idTipoRefacciones}','tipoRefController@restaurarTipoRef')->name('restaurarTipoRef');


// CRUD SERVICIOS TALLER
Route::get('altaServTaller','servicioTallerController@altaServTaller')->name('altaServTaller');
Route::POST('guardarServTaller','servicioTallerController@guardarServTaller')->name('guardarServTaller');
Route::get('reporteServTaller','servicioTallerController@reporteServTaller')->name('reporteServTaller');
Route::get('modificarServTaller/{idServicioTaller}','servicioTallerController@modificarServTaller')->name('modificarServTaller');
Route::POST('editarServTaller','servicioTallerController@editarServTaller')->name('editarServTaller');
Route::get('eliminarServTaller/{idServicioTaller}','servicioTallerController@eliminarServTaller')->name('eliminarServTaller');
Route::get('restaurarServTaller/{idServicioTaller}','servicioTallerController@restaurarServTaller')->name('restaurarServTaller');


// CRUD PROVEEDORES
Route::get('altaProveedores','proveedoresController@altaProveedores')->name('altaProveedores');
Route::POST('guardarProveedores','proveedoresController@guardarProveedores')->name('guardarProveedores');
Route::get('reporteProveedores','proveedoresController@reporteProveedores')->name('reporteProveedores');
Route::get('modificarProveedores/{idProveedor}','proveedoresController@modificarProveedores')->name('modificarProveedores');
Route::POST('editarProveedores','proveedoresController@editarProveedores')->name('editarProveedores');
Route::get('eliminarProveedores/{idProveedor}','proveedoresController@eliminarProveedores')->name('eliminarProveedores');
Route::get('restaurarProveedores/{idProveedor}','proveedoresController@restaurarProveedores')->name('restaurarProveedores');
Route::get('agregarCuentas','proveedoresController@agregarCuentas')->name('agregarCuentas');
Route::get('borrarCuenta','proveedoresController@borrarCuenta')->name('borrarCuenta');
Route::POST('guardarCuentasProveedor','proveedoresController@guardarCuentasProveedor')->name('guardarCuentasProveedor');

// CRUD REFACCIONES
Route::get('altaRefacciones','refaccionesController@altaRefacciones')->name('altaRefacciones');
Route::POST('guardarRefacciones','refaccionesController@guardarRefacciones')->name('guardarRefacciones');
Route::get('reporteRefacciones','refaccionesController@reporteRefacciones')->name('reporteRefacciones');
Route::get('modificarRefacciones/{idRefaccion}','refaccionesController@modificarRefacciones')->name('modificarRefacciones');
Route::POST('editarRefacciones','refaccionesController@editarRefacciones')->name('editarRefacciones');
Route::get('eliminarRefacciones/{idRefaccion}','refaccionesController@eliminarRefacciones')->name('eliminarRefacciones');
Route::get('restaurarRefacciones/{idRefaccion}','refaccionesController@restaurarRefacciones')->name('restaurarRefacciones');
// Route::get('crearRefacciones','refaccionesController@crearRefacciones')->name('crearRefacciones');
Route::get('mensaje','refaccionesController@mensaje')->name('mensaje');
Route::POST('filtroRefacciones','refaccionesController@filtroRefacciones')->name('filtroRefacciones');
/*
Route::POST('editarSalidaViajes','ViajesController@editarSalidaViajes')->name('editarSalidaViajes');
Route::POST('editarEntradaViajes','ViajesController@editarEntradaViajes')->name('editarEntradaViajes');
Route::get('modificarViajes/{idViaje}','ViajesController@modificarViajes')->name('modificarViajes');
Route::POST('modificarViajes2','ViajesController@modificarViajes2')->name('modificarViajes2');
Route::POST('editarViajes','ViajesController@editarViajes')->name('editarViajes');
Route::get('modificarEntregaViajes/{idViaje}','ViajesController@modificarEntregaViajes')->name('modificarEntregaViajes');
Route::POST('editarEntregaViajes','ViajesController@editarEntregaViajes')->name('editarEntregaViajes');
*/

// C IMAGENES
Route::get('/automatizacion','ImgController@automatizacion')->name('automatizacion');
Route::get('/portada','ImgController@portada')->name('portada');
Route::get('/ventas','ImgController@ventas')->name('ventas');
Route::get('/husky','ImgController@husky')->name('husky');
Route::get('/productos2','ImgController@productos2')->name('productos2');


////Inicio
Route::get ('/principal','principal@principal')->name('principal');
Route::get('/Inicio','InicioController@Inicio')->name('Inicio');

Route::get('/iniciop','InicioController@iniciop')->name('iniciop');
Route::get('/ventaRefacciones','InicioController@ventaRefacciones')->name('ventaRefacciones');
Route::get('/solucionesHusky','InicioController@solucionesHusky')->name('solucionesHusky');
Route::get('/productos','InicioController@productos')->name('productos');
Route::get('/contacto','InicioController@contacto')->name('contacto');

//ASIGNACIONES
Route::get('asignacion/{idFactura}','AsignacionController@asignacion')->name('asignacion');
Route::get('reporteAsignacion','AsignacionController@reporteAsignacion')->name('asignacion');
Route::get('carrito','AsignacionController@carrito')->name('carrito');
Route::get('borraAsignacion','AsignacionController@borraAsignacion')->name('borraAsignacion');
Route::get('borraReporte','AsignacionController@borraReporte')->name('borraReporte');
Route::get('reporteFacturasAsignadas','AsignacionController@reporteFacturasAsignadas')->name('reporteFacturasAsignadas');
Route::get('reporteFacturasAbajoAsignadas','AsignacionController@reporteFacturasAbajoAsignadas')->name('reporteFacturasAbajoAsignadas');


//CRUD MARCAS
Route::get('altaAlcances','alcancesController@altaAlcances')->name('altaAlcances');
Route::POST('guardarAlcance','alcancesController@guardarAlcance')->name('guardarAlcance');
Route::get('reporteAlcances','alcancesController@reporteAlcances')->name('reporteAlcances');
Route::get('modificarAlcances/{idAlcance}','alcancesController@modificarAlcances')->name('modificarAlcances');
Route::POST('editarAlcances','alcancesController@editarAlcances')->name('editarAlcances');
Route::get('eliminarAlcances/{idAlcance}','alcancesController@eliminarAlcances')->name('eliminarAlcances');
Route::get('restaurarAlcances/{idAlcance}','alcancesController@restaurarAlcances')->name('restaurarAlcances');

 ///////////////////LOGIN///////////////////////////7
Route::get('login','login@login')->name('login');
Route::POST('validauser','login@validauser')->name('validauser');
Route::get('principal','login@principal')->name('principal');
Route::get('cerrarsesionuser','login@cerrarsesionuser')->name('cerrarsesionuser');

 ///////////////////LOGIN PROVEEDORES///////////////////////////
 Route::get('accesoProveedores','loginProv@accesoProveedores')->name('accesoProveedores');
 Route::POST('validauserProv','loginProv@validauserProv')->name('validauserProv');
 Route::get('/inicioProveedor','inicioProvController@inicioProveedor')->name('inicioProveedor');
 Route::get('cuentasProveedor','inicioProvController@cuentasProveedor')->name('cuentasProveedor');
 Route::get('principalProv','loginProv@principalProv')->name('principalProv');
 Route::get('cerrarsesionprov','loginProv@cerrarsesionprov')->name('cerrarsesionprov');
 Route::get('ocProv','inicioProvController@ocProv')->name('ocProv');
 Route::get('subirArProv/{idOrden}','proveedoresController@subirArProv')->name('subirArProv');
 Route::get('consultaOrdenesCompra','inicioProvController@consultaOrdenesCompra')->name('consultaOrdenesCompra');


//CRUD INVESTIGACION DE PROSPECTOS
Route::get('altaProspectos','invProspectosController@altaProspectos')->name('altaProspectos');
Route::POST('guardarProspectos','invProspectosController@guardarProspectos')->name('guardarProspectos');
Route::get('reporteProspectos','invProspectosController@reporteProspectos')->name('reporteProspectos');
Route::get('modificarProspectos/{idInvProspectos}','invProspectosController@modificarProspectos')->name('modificarProspectos');
Route::POST('editarProspectos','invProspectosController@editarProspectos')->name('editarProspectos');
Route::get('editarSucursalProspecto','invProspectosController@editarSucursalProspecto')->name('editarSucursalProspecto');
Route::get('eliminarProspectos/{idInvProspectos}','invProspectosController@eliminarProspectos')->name('eliminarProspectos');
Route::get('restaurarProspectos/{idInvProspectos}','invProspectosController@restaurarProspectos')->name('restaurarProspectos');
Route::get('comboSuc','invProspectosController@comboSuc')->name('comboSuc');
Route::get('edicionSuc','invProspectosController@edicionSuc')->name('edicionSuc');
Route::get('obtenerId','invProspectosController@obtenerId')->name('obtenerId');
Route::get('reporteProspectosAbajo','invProspectosController@reporteProspectosAbajo')->name('reporteProspectosAbajo');
Route::get('editarSucursalProspecto','invProspectosController@editarSucursalProspecto')->name('editarSucursalProspecto');



// REPORTE DE ACTIVIDADES
Route::get('reporteDeActividades','reporteActividadesController@reporteDeActividades')->name('reporteDeActividades');
Route::get('reporteSeguimientoInicio','reporteActividadesController@reporteSeguimientoInicio')->name('reporteSeguimientoInicio');
Route::get('reporteCitasAsignadas','reporteActividadesController@reporteCitasAsignadas')->name('reporteCitasAsignadas');
Route::get('reporteCitasAsignadasAbajo','reporteActividadesController@reporteCitasAsignadasAbajo')->name('reporteCitasAsignadasAbajo');
Route::get('reporteCitasCreadas','reporteActividadesController@reporteCitasCreadas')->name('reporteCitasCreadas');
Route::get('reporteCitasCreadasAbajo','reporteActividadesController@reporteCitasCreadasAbajo')->name('reporteCitasCreadasAbajo');
Route::get('reporteProspectosAct','reporteActividadesController@reporteProspectosAct')->name('reporteProspectosAct');
Route::get('reporteProspectosActAbajo','reporteActividadesController@reporteProspectosActAbajo')->name('reporteProspectosActAbajo');
Route::get('reportePublicidadAct','reporteActividadesController@reportePublicidadAct')->name('reportePublicidadAct');
Route::get('reportePublicidadActAbajo','reporteActividadesController@reportePublicidadActAbajo')->name('reportePublicidadActAbajo');
Route::POST('filtroProspectosAct','reporteActividadesController@filtroProspectosAct')->name('filtroProspectosAct');
Route::POST('filtroProspectosActAbajo','reporteActividadesController@filtroProspectosActAbajo')->name('filtroProspectosActAbajo');
Route::POST('filtroPublicidadAct','reporteActividadesController@filtroPublicidadAct')->name('filtroPublicidadAct');
Route::POST('filtroPublicidadActAbajo','reporteActividadesController@filtroPublicidadActAbajo')->name('filtroPublicidadActAbajo');
Route::POST('filtroCitasAsignadasAct','reporteActividadesController@filtroCitasAsignadasAct')->name('filtroCitasAsignadasAct');
Route::POST('filtroCitasAsignadasActAbajo','reporteActividadesController@filtroCitasAsignadasActAbajo')->name('filtroCitasAsignadasActAbajo');
Route::POST('filtroCitasCreadasAct','reporteActividadesController@filtroCitasCreadasAct')->name('filtroCitasCreadasAct');
Route::POST('filtroCitasCreadasActAbajo','reporteActividadesController@filtroCitasCreadasActAbajo')->name('filtroCitasCreadasActAbajo');

//CRUD SERVICIOS PUBLICIDAD
Route::get('altaServPublicidad','servPublicidadController@altaServPublicidad')->name('altaServPublicidad');
Route::POST('guardarServPublicidad','servPublicidadController@GuardarServPublicidad')->name('guardarServPublicidad');
Route::get('reporteServPublicidad','servPublicidadController@reporteServPublicidad')->name('reporteServPublicidad');
Route::get('reporteServPublicidadAbajo','servPublicidadController@reporteServPublicidadAbajo')->name('reporteServPublicidadAbajo');
Route::get('modificarServPublicidad/{idServPublicidad}','servPublicidadController@modificarServPublicidad')->name('modificarServPublicidad');
Route::POST('editarServPublicidad','servPublicidadController@editarServPublicidad')->name('editarServPublicidad');
Route::get('eliminarServPublicidad/{idServPublicidad}','servPublicidadController@eliminarServPublicidad')->name('eliminarServPublicidad');
Route::get('restaurarServPublicidad/{idServPublicidad}','servPublicidadController@restaurarServPublicidad')->name('restaurarServPublicidad');

// COTIZACIONES
Route::get('cotizaciones','cotizacionesController@cotizaciones')->name('cotizaciones');
Route::get('altaCotizacion','cotizacionesController@altaCotizacion')->name('altaCotizacion');
Route::get('generarNumeroCotizacion','cotizacionesController@generarNumeroCotizacion');
Route::get('comboContactosCotizacion','cotizacionesController@comboContactosCotizacion');
Route::get('comboEquiposCotizacion','cotizacionesController@comboEquiposCotizacion');
Route::POST('guardarCotizacion','cotizacionesController@guardarCotizacion')->name('guardarCotizacion');
Route::get('reporteVersionesCotizacion','cotizacionesController@reporteVersionesCotizacion')->name('reporteVersionesCotizacion');
Route::get('reporteCotizacionSucursal','cotizacionesController@reporteCotizacionSucursal');
Route::get('actualizarCotizacion','cotizacionesController@actualizarCotizacion')->name('actualizarCotizacion');
Route::get('modificarMontoPartidaCotizacion','cotizacionesController@modificarMontoPartidaCotizacion')->name('modificarMontoPartidaCotizacion');
Route::post('guardarModificacionMontoPartidaCotizacion','cotizacionesController@guardarModificacionMontoPartidaCotizacion')->name('guardarModificacionMontoPartidaCotizacion');
Route::get('rollbackCotizacion','cotizacionesController@rollbackCotizacion')->name('rollbackCotizacion');
Route::get('editarCotizacion','cotizacionesController@editarCotizacion')->name('editarCotizacion');
Route::get('generarNumeroCotizacionEditar','cotizacionesController@generarNumeroCotizacionEditar');
Route::POST('guardarEditarCotizacion','cotizacionesController@guardarEditarCotizacion')->name('guardarEditarCotizacion');
Route::get('reparaciones','cotizacionesController@reparaciones')->name('reparaciones');
Route::get('cotizacionEquipoNuevo','cotizacionesController@cotizacionEquipoNuevo')->name('cotizacionEquipoNuevo');
Route::get('cotizacionRefurbished','cotizacionesController@cotizacionRefurbished')->name('cotizacionRefurbished');
Route::get('guardarReparacion','cotizacionesController@guardarReparacion');
Route::post('reporteDeReparacionesAbajo','cotizacionesController@reporteDeReparacionesAbajo');
Route::post('borrarReparacion','cotizacionesController@borrarReparacion')->name('borrarReparacion');
Route::get('editarAsignacionEquiposCotizacion','cotizacionesController@editarAsignacionEquiposCotizacion')->name('editarAsignacionEquiposCotizacion');
Route::get('editarEquiposEnCotizacion','cotizacionesController@editarEquiposEnCotizacion');
Route::POST('guardarModificacionCotizacion','cotizacionesController@guardarModificacionCotizacion')->name('guardarModificacionCotizacion');
Route::get('cancelarVersion','cotizacionesController@cancelarVersion')->name('cancelarVersion');
Route::POST('anexgridVersionesCotizacion','cotizacionesController@anexgridVersionesCotizacion')->name('anexgridVersionesCotizacion');
Route::POST('anexgridVersionesCanceladas','cotizacionesController@anexgridVersionesCanceladas')->name('anexgridVersionesCanceladas');
Route::POST('anexgridVersionesCotizacionConFiltros','cotizacionesController@anexgridVersionesCotizacionConFiltros')->name('anexgridVersionesCotizacionConFiltros');
Route::POST('anexgridVersionesCanceladasConFiltros','cotizacionesController@anexgridVersionesCanceladasConFiltros')->name('anexgridVersionesCanceladasConFiltros');
Route::get('verCotizacion','cotizacionesController@verCotizacion')->name('verCotizacion');
Route::get('pdfCotizacion','cotizacionesController@pdfCotizacion')->name('pdfCotizacion');
Route::get('comboPartes','cotizacionesController@comboPartes')->name('comboPartes');
Route::get('precioParte','cotizacionesController@precioParte')->name('precioParte');
Route::get('modeloParte','cotizacionesController@modeloParte')->name('modeloParte');
Route::get('descripcionParte','cotizacionesController@descripcionParte')->name('descripcionParte');
Route::get('fechaActualizacionParte','cotizacionesController@fechaActualizacionParte')->name('fechaActualizacionParte');
Route::get('notaInternaYskuEquivalente','cotizacionesController@notaInternaYskuEquivalente')->name('notaInternaYskuEquivalente');
Route::get('semanasEntregaParte','cotizacionesController@semanasEntregaParte')->name('semanasEntregaParte');
Route::get('agregarRefaccionParteCotizaciones','cotizacionesController@agregarRefaccionParteCotizaciones')->name('agregarRefaccionParteCotizaciones');
Route::get('agregarPaqueteCotizacio','cotizacionesController@agregarPaqueteCotizacio')->name('agregarPaqueteCotizacio');
Route::get('borrarRefaccionParteCotizacion','cotizacionesController@borrarRefaccionParteCotizacion')->name('borrarRefaccionParteCotizacion');
Route::get('generarPartida','cotizacionesController@generarPartida')->name('generarPartida');
Route::get('refaccionesAsignadasCotizacion','cotizacionesController@refaccionesAsignadasCotizacion')->name('refaccionesAsignadasCotizacion');
Route::get('detallePartidasCotizacion','cotizacionesController@detallePartidasCotizacion')->name('detallePartidasCotizacion');
Route::get('validarCotizacionesErroneas','cotizacionesController@validarCotizacionesErroneas')->name('validarCotizacionesErroneas');
Route::get('creandoIdCotizacion','cotizacionesController@creandoIdCotizacion')->name('creandoIdCotizacion');
Route::get('preciosRefaccionesCot','cotizacionesController@preciosRefaccionesCot')->name('preciosRefaccionesCot');
Route::get('cancelarAltaVersion','cotizacionesController@cancelarAltaVersion')->name('cancelarAltaVersion');
Route::get('reactivarCotizacion','cotizacionesController@reactivarCotizacion')->name('reactivarCotizacion');
// COTIZACIONES DE SERVICIOS
Route::get('cotizacionServicios','cotizacionServiciosController@cotizacionServicios')->name('cotizacionServicios');
Route::get('altaCotizacionServicios','cotizacionServiciosController@altaCotizacionServicios')->name('altaCotizacionServicios');
Route::get('generarNumeroCotizacionServicios','cotizacionServiciosController@generarNumeroCotizacionServicios');
Route::POST('guardarCotizacionServicios','cotizacionServiciosController@guardarCotizacionServicios')->name('guardarCotizacionServicios');
Route::get('reporteVersionesServiciosCotizacion','cotizacionServiciosController@reporteVersionesServiciosCotizacion')->name('reporteVersionesServiciosCotizacion');
Route::get('actualizarCotizacionServicios','cotizacionServiciosController@actualizarCotizacionServicios')->name('actualizarCotizacionServicios');
Route::get('modificarMontoPartidaCotizacionServicios','cotizacionServiciosController@modificarMontoPartidaCotizacionServicios')->name('modificarMontoPartidaCotizacionServicios');
Route::get('guardarModificacionMontoPartidaCotizacionServicios','cotizacionServiciosController@guardarModificacionMontoPartidaCotizacionServicios')->name('guardarModificacionMontoPartidaCotizacionServicios');
Route::get('rollbackCotizacionServicios','cotizacionServiciosController@rollbackCotizacionServicios')->name('rollbackCotizacionServicios');
Route::get('editarCotizacionServicios','cotizacionServiciosController@editarCotizacionServicios')->name('editarCotizacionServicios');
Route::POST('guardarEditarCotizacionServicios','cotizacionServiciosController@guardarEditarCotizacionServicios')->name('guardarEditarCotizacionServicios');
Route::get('asignarSercicioCotizacion','cotizacionServiciosController@asignarSercicioCotizacion');
Route::get('borrarServicio','cotizacionServiciosController@borrarServicio')->name('borrarServicio');
Route::POST('guardarModificacionCotizacionServicios','cotizacionServiciosController@guardarModificacionCotizacionServicios')->name('guardarModificacionCotizacionServicios');
Route::get('cancelarVersionServicios','cotizacionServiciosController@cancelarVersionServicios')->name('cancelarVersionServicios');
Route::POST('anexgridVersionesServiciosCotizacion','cotizacionServiciosController@anexgridVersionesServiciosCotizacion')->name('anexgridVersionesServiciosCotizacion');
Route::POST('anexgridVersionesServiciosCanceladas','cotizacionServiciosController@anexgridVersionesServiciosCanceladas')->name('anexgridVersionesServiciosCanceladas');
Route::POST('anexgridVersionesServiciosCotizacionConFiltros','cotizacionServiciosController@anexgridVersionesServiciosCotizacionConFiltros')->name('anexgridVersionesServiciosCotizacionConFiltros');
Route::POST('anexgridVersionesServiciosCanceladasConFiltros','cotizacionServiciosController@anexgridVersionesServiciosCanceladasConFiltros')->name('anexgridVersionesServiciosCanceladasConFiltros');
Route::get('verCotizacionServicios','cotizacionServiciosController@verCotizacionServicios')->name('verCotizacionServicios');
Route::get('pdfCotizacionServicios','cotizacionServiciosController@pdfCotizacionServicios')->name('pdfCotizacionServicios');
Route::get('detalleServicio','cotizacionServiciosController@detalleServicio')->name('detalleServicio');
Route::get('montoServicio','cotizacionServiciosController@montoServicio')->name('montoServicio');
Route::get('unidadMedida','cotizacionServiciosController@unidadMedida')->name('unidadMedida');
Route::get('creandoIdCotizacionServicios','cotizacionServiciosController@creandoIdCotizacionServicios')->name('creandoIdCotizacionServicios');
Route::get('cancelarAltaVersionServicios','cotizacionServiciosController@cancelarAltaVersionServicios')->name('cancelarAltaVersionServicios');

//SERVIVIOS TECNICOS
Route::get('altaServicioTecnico','serviciosTecnicosController@altaServicioTecnico')->name('altaServicioTecnico');
Route::POST('guardarServicioTecnico','serviciosTecnicosController@guardarServicioTecnico')->name('guardarServicioTecnico');
Route::get('reporteServiciosTecnicos','serviciosTecnicosController@reporteServiciosTecnicos')->name('reporteServiciosTecnicos');
Route::get('modificarServicioTecnico/{idServicioTecnico}','serviciosTecnicosController@modificarServicioTecnico')->name('modificarServicioTecnico');
Route::POST('editarServicioTecnico','serviciosTecnicosController@editarServicioTecnico')->name('editarServicioTecnico');
Route::get('eliminarServicioTecnico/{idServicioTecnico}','serviciosTecnicosController@eliminarServicioTecnico')->name('eliminarServicioTecnico');
Route::get('restaurarServicioTecnico/{idServicioTecnico}','serviciosTecnicosController@restaurarServicioTecnico')->name('restaurarServicioTecnico');

//ENTRADAS ALMACEN///
Route::get('altaEntradaAlmacen','EntradaAlmacenController@altaEntradaAlmacen')->name('altaEntradaAlmacen');
Route::post('obtenerOCproveedor','EntradaAlmacenController@obtenerOCproveedor')->name('obtenerOCproveedor');
Route::post('obtenerEmpresaAndProvedor','EntradaAlmacenController@obtenerEmpresaAndProvedor')->name('obtenerEmpresaAndProvedor');
Route::post('obtenerNotasEntradaOC','EntradaAlmacenController@obtenerNotasEntradaOC')->name('obtenerNotasEntradaOC');
Route::post('obtenerProductosNE','EntradaAlmacenController@obtenerProductosNE')->name('obtenerProductosNE');
Route::post('obtenerDatosProductos','EntradaAlmacenController@obtenerDatosProductos')->name('obtenerDatosProductos');
Route::post('crearEntradaAlmacen','EntradaAlmacenController@crearEntradaAlmacen')->name('crearEntradaAlmacen');
Route::post('agregarProductosAentrada','EntradaAlmacenController@agregarProductosAentrada')->name('agregarProductosAentrada');
Route::post('obtener_datos_entrada','EntradaAlmacenController@obtener_datos_entrada')->name('obtener_datos_entrada');
Route::post('editarProductoEntrada','EntradaAlmacenController@editarProductoEntrada')->name('editarProductoEntrada');
Route::post('anexgrid_get_agregadosAentrada','EntradaAlmacenController@anexgrid_get_agregadosAentrada')->name('anexgrid_get_agregadosAentrada');
Route::post('eliminarProductoEntrada','EntradaAlmacenController@eliminarProductoEntrada')->name('eliminarProductoEntrada');
Route::get('reporteEntradaAlmacen','EntradaAlmacenController@reporteEntradaAlmacen')->name('reporteEntradaAlmacen');
Route::post('anexgrid_get_reporteProductosPorEntrada','EntradaAlmacenController@anexgrid_get_reporteProductosPorEntrada')->name('anexgrid_get_reporteProductosPorEntrada');
Route::post('generarEtiquetaEntradaAlmacen','EntradaAlmacenController@generarEtiquetaEntradaAlmacen')->name('generarEtiquetaEntradaAlmacen');
Route::post('otras_entradas_almacen','EntradaAlmacenController@otras_entradas_almacen')->name('otras_entradas_almacen');

// ASGNACION DE ENTRADAS ALMACEN A SERVICIOS
Route::get('altaAsignacionProductosServicios','asignacionServiciosEntradasAlmacenController@altaAsignacionProductosServicios')->name('altaAsignacionProductosServicios');
Route::get('asignacionProductosServiciosModal','asignacionServiciosEntradasAlmacenController@asignacionProductosServiciosModal')->name('asignacionProductosServiciosModal');
Route::get('reporteAsignacionProductosServicios','asignacionServiciosEntradasAlmacenController@reporteAsignacionProductosServicios')->name('reporteAsignacionProductosServicios');
Route::post('obtenerOrdenesCompra','asignacionServiciosEntradasAlmacenController@obtenerOrdenesCompra')->name('obtenerOrdenesCompra');
Route::post('obtenerProductosSinServicio','asignacionServiciosEntradasAlmacenController@obtenerProductosSinServicio')->name('obtenerProductosSinServicio');
Route::post('obtenerDatosProductosSinServicio','asignacionServiciosEntradasAlmacenController@obtenerDatosProductosSinServicio')->name('obtenerDatosProductosSinServicio');
Route::post('crearGrupoAsignacion','asignacionServiciosEntradasAlmacenController@crearGrupoAsignacion')->name('crearGrupoAsignacion');
Route::post('consultaProductosAsignados','asignacionServiciosEntradasAlmacenController@consultaProductosAsignados')->name('consultaProductosAsignados');
Route::post('consultaProductosAsignadosPorOC','asignacionServiciosEntradasAlmacenController@consultaProductosAsignadosPorOC')->name('consultaProductosAsignadosPorOC');
Route::post('eliminarAsignacion','asignacionServiciosEntradasAlmacenController@eliminarAsignacion')->name('eliminarAsignacion');
Route::post('anexgrid_get_reporteProductos_asignados_servicio','asignacionServiciosEntradasAlmacenController@anexgrid_get_reporteProductos_asignados_servicio')->name('anexgrid_get_reporteProductos_asignados_servicio');
Route::post('anexgrid_get_reporteProductos_asignados_a_oc','asignacionServiciosEntradasAlmacenController@anexgrid_get_reporteProductos_asignados_a_oc')->name('anexgrid_get_reporteProductos_asignados_a_oc');
Route::post('anexgrid_get_reporteProductos_otras_salidas','asignacionServiciosEntradasAlmacenController@anexgrid_get_reporteProductos_otras_salidas')->name('anexgrid_get_reporteProductos_otras_salidas');
Route::get('reporteRastreoAsignacion_productos','asignacionServiciosEntradasAlmacenController@reporteRastreoAsignacion_productos')->name('reporteRastreoAsignacion_productos');
Route::post('obtenerOrdenesCompra_destino/{id}','asignacionServiciosEntradasAlmacenController@obtenerOrdenesCompra_destino')->name('obtenerOrdenesCompra_destino');
Route::get('consulta_asignaciones_oc/','asignacionServiciosEntradasAlmacenController@consulta_asignaciones_oc')->name('consulta_asignaciones_oc');
Route::get('consulta_rastreo_asignaciones/{id}','asignacionServiciosEntradasAlmacenController@consulta_rastreo_asignaciones')->name('consulta_rastreo_asignaciones');
Route::get('consulta_detalle_rastreo_asignacion/{id}','asignacionServiciosEntradasAlmacenController@consulta_detalle_rastreo_asignacion')->name('consulta_detalle_rastreo_asignacion');
Route::post('obetener_cantidad_solicitada_en_oc','asignacionServiciosEntradasAlmacenController@obetener_cantidad_solicitada_en_oc')->name('obetener_cantidad_solicitada_en_oc');
Route::get('obtener_cantidad_solicitada_servicio','asignacionServiciosEntradasAlmacenController@obtener_cantidad_solicitada_servicio')->name('obtener_cantidad_solicitada_servicio');
Route::get('otras_salidas_almacen','asignacionServiciosEntradasAlmacenController@otras_salidas_almacen')->name('otras_salidas_almacen');

/// MARCAS REFACCIONES //
Route::get('altaMarcasRefaccion','MarcasRefaccionController@altaMarcasRefaccion')->name('altaMarcasRefaccion');
Route::get('reporteMarcasRefaccion','MarcasRefaccionController@reporteMarcasRefaccion')->name('reporteMarcasRefaccion');
Route::POST('guardarMarcasRefaccion','MarcasRefaccionController@guardarMarcasRefaccion')->name('guardarMarcasRefaccion');
Route::POST('editarMarcaRefaccion','MarcasRefaccionController@editarMarcaRefaccion')->name('editarMarcaRefaccion');
Route::get('modificarMarcaRefaccion/{idMarcaRefa}','MarcasRefaccionController@modificarMarcaRefaccion')->name('modificarMarcaRefaccion');
Route::get('eliminarMarcaRefaccion/{idMarcaRefa}','MarcasRefaccionController@eliminarMarcaRefaccion')->name('eliminarMarcaRefaccion');
Route::get('restaurarMarcaRefaccion/{idMarcaRefa}','MarcasRefaccionController@restaurarMarcaRefaccion')->name('restaurarMarcaRefaccion');

/// PARTES VENTAS ///
Route::get('altaPartesVenta','partesVentaController@altaPartesVenta')->name('altaPartesVenta');
Route::get('reportePartesVenta','partesVentaController@reportePartesVenta')->name('reportePartesVenta');
Route::get('modificarPartesVenta/{idPartesVenta}','partesVentaController@modificarPartesVenta')->name('modificarPartesVenta');
Route::get('eliminarPartesVenta/{idPartesVenta}','partesVentaController@eliminarPartesVenta')->name('eliminarPartesVenta');
Route::get('restaurarPartesVenta/{idPartesVenta}','partesVentaController@restaurarPartesVenta')->name('restaurarPartesVenta');
Route::POST('guardarParteVenta','partesVentaController@guardarParteVenta')->name('guardarParteVenta');
Route::POST('editarPartesVenta','partesVentaController@editarPartesVenta')->name('editarPartesVenta');
Route::POST('filtroPartesVenta','partesVentaController@filtroPartesVenta')->name('filtroPartesVenta');
Route::POST('filtroEquiposVenta','partesVentaController@filtroEquiposVenta')->name('filtroEquiposVenta');
Route::get('comboTipoRefaccion','partesVentaController@comboTipoRefaccion')->name('comboTipoRefaccion');
Route::get('generarCodigo','partesVentaController@generarCodigo')->name('generarCodigo');
//Route::get('/excel', 'partesVentaController@excel');

// PAQUETES
Route::get('altaPaquetes','paquetesController@altaPaquetes')->name('altaPaquetes');
Route::POST('guardarPaquetes','paquetesController@guardarPaquetes')->name('guardarPaquetes');
Route::get('reportePaquetes','paquetesController@reportePaquetes')->name('reportePaquetes');
Route::get('modificarPaquetes/{idPaquete}','paquetesController@modificarPaquetes')->name('modificarPaquetes');
Route::POST('editaPaquetes','paquetesController@editaPaquetes')->name('editaPaquetes');
Route::get('eliminarPaquetes/{idPaquete}','paquetesController@eliminarPaquetes')->name('eliminarPaquetes');
Route::get('restaurarPaquetes/{idPaquete}','paquetesController@restaurarPaquetes')->name('restaurarPaquetes');
Route::get('agregarPartePaquete/{idPaquete}','paquetesController@agregarPartePaquete')->name('agregarPartePaquete');
Route::get('altaAgregarPaq','paquetesController@altaAgregarPaq')->name('altaAgregarPaq');
Route::get('guardarPac','paquetesController@guardarPac')->name('guardarPac');
Route::get('borrarPartePaq','paquetesController@borrarPartePaq')->name('borrarPartePaq');
Route::get('borrarPartePaq2','paquetesController@borrarPartePaq2')->name('borrarPartePaq2');
Route::get('agregarPac','paquetesController@agregarPac')->name('agregarPac');
Route::get('detallePac','paquetesController@detallePac')->name('detallePac');
Route::get('comboTipoRef','paquetesController@comboTipoRef')->name('comboTipoRef');
Route::get('comboNumeroSKU','paquetesController@comboNumeroSKU')->name('comboNumeroSKU');
Route::get('serie','paquetesController@serie')->name('serie');
Route::get('modelo','paquetesController@modelo')->name('modelo');
Route::get('codigo','paquetesController@codigo')->name('codigo');
Route::get('precioVentaPe','paquetesController@precioVentaPe')->name('precioVentaPe');
Route::get('precioVentaDol','paquetesController@precioVentaDol')->name('precioVentaDol');



Route::resource('index','partesVentaController');



Route::get('reporteZing','ejemploController@reporteZing')->name('reporteZing');
Route::get('ejemploAnexgrid','ejemploController@ejemploAnexgrid')->name('ejemploAnexgrid');
Route::get('equiposData','EquiposController@equiposData')->name('equiposData');
Route::POST('filtro','ejemploController@filtroEjemplo')->name('filtro');

// @RodrigoXdn
Route::get('xdn','XdnController@index')->name('xdn');
Route::post('excel','XdnController@excel')->name('excel');

// ASIGNACION PARTES
Route::get('asignacion','AsignacionPiezasController@asignacionPiezas')->name('asignacion');
Route::get('informacionCotizacion','AsignacionPiezasController@informacionCotizacion')->name('informacionCotizacion');
Route::get('servicioAsignado','AsignacionPiezasController@servicioAsignado')->name('servicioAsignado');
Route::get('detalleServicioPiezas','AsignacionPiezasController@detalleServicioPiezas')->name('detalleServicioPiezas');
Route::get('combiarInputs','AsignacionPiezasController@combiarInputs')->name('combiarInputs');
Route::get('guardarAsignacion','AsignacionPiezasController@guardarAsignacion')->name('guardarAsignacion');
Route::get('comboCodigoPiezas','AsignacionPiezasController@comboCodigoPiezas')->name('comboCodigoPiezas');
Route::get('cambiarBoton','AsignacionPiezasController@cambiarBoton')->name('cambiarBoton');
Route::get('cambiarPendiente','AsignacionPiezasController@cambiarPendiente')->name('cambiarPendiente');
Route::get('borrarAsignacion','AsignacionPiezasController@borrarAsignacion')->name('borrarAsignacion');
Route::get('comboMarcaPiezas','AsignacionPiezasController@comboMarcaPiezas')->name('comboMarcaPiezas');
Route::get('comboTipoRefPiezas','AsignacionPiezasController@comboTipoRefPiezas')->name('comboTipoRefPiezas');
Route::get('comboRefaccionPiezas','AsignacionPiezasController@comboRefaccionPiezas')->name('comboRefaccionPiezas');


// AREAS
Route::get('altaAreas','areasController@altaAreas')->name('altaAreas');
Route::POST ('guardarAreas','areasController@guardarAreas')->name('guardarAreas');
Route::get('reporteAreas','areasController@reporteAreas')->name('reporteAreas');
Route::get('modificarAreas/{idArea}','areasController@modificarAreas')->name('modificarAreas');
Route::POST('editarAreas','areasController@editarAreas')->name('editarAreas');
Route::get('eliminarAreas/{idArea}','areasController@eliminarAreas')->name('eliminarAreas');
Route::get('restaurarAreas/{idArea}','areasController@restaurarAreas')->name('restaurarAreas');

// ACTIVIDADES
Route::get('altaActividades','actividadesController@altaActividades')->name('altaActividades');
Route::POST ('guardarActividades','actividadesController@guardarActividades')->name('guardarActividades');
Route::get('reporteActividades','actividadesController@reporteActividades')->name('reporteActividades');
Route::get('modificarActividades/{idActividad}','actividadesController@modificarActividades')->name('modificarActividades');
Route::POST('editarActividades','actividadesController@editarActividades')->name('editarActividades');
Route::get('eliminarActividades/{idActividad}','actividadesController@eliminarActividades')->name('eliminarActividades');
Route::get('restaurarActividades/{idActividad}','actividadesController@restaurarActividades')->name('restaurarActividades');

// SEGUIMIENTO DE ACTIVIDADES
Route::get('altaSeg','seguimientoActController@altaSeg')->name('altaSeg');
Route::POST ('guardarSeg','seguimientoActController@guardarSeg')->name('guardarSeg');
Route::get('modificarSeg/{idSegActividad}','seguimientoActController@modificarSeg')->name('modificarSeg');
Route::POST('editarSeg','seguimientoActController@editarSeg')->name('editarSeg');
Route::get('eliminarSeg/{idSegActividad}','seguimientoActController@eliminarSeg')->name('eliminarSeg');
Route::get('restaurarSeg/{idSegActividad}','seguimientoActController@restaurarSeg')->name('restaurarSeg');
Route::get('seguimientoUsuarios','seguimientoActController@seguimientoUsuarios')->name('seguimientoUsuarios');
Route::get('borrarSeg','seguimientoActController@borrarSeg')->name('borrarSeg');
Route::get('detalleSeg/{idSegActividad}','seguimientoActController@detalleSeg')->name('detalleSeg');
Route::get('seguimientos/{idSegActividad}','seguimientoActController@seguimientos')->name('seguimientos');
Route::POST('seguimientoPorUsuario','seguimientoActController@seguimientoPorUsuario')->name('seguimientoPorUsuario');
Route::get('borrarSegUs','seguimientoActController@borrarSegUs')->name('borrarSegUs');
Route::get('archivosSeg','seguimientoActController@archivosSeg')->name('archivosSeg');
Route::POST('guardarArchivosEvidencia','seguimientoActController@guardarArchivosEvidencia')->name('guardarArchivosEvidencia');
Route::get('comboActividades','seguimientoActController@comboActividades')->name('comboActividades');
Route::get('infoUsuarios','seguimientoActController@infoUsuarios')->name('infoUsuarios');
Route::get('modalSeguimientos','seguimientoActController@modalSeguimientos')->name('modalSeguimientos');
Route::get('archivosSeg2','seguimientoActController@archivosSeg2')->name('archivosSeg2');

// Route::get('reporteSeguimientoVista','seguimientoActController@reporteSeguimientoVista')->name('reporteSeguimientoVista');
// Route::get('reporteCerradas','seguimientoActController@reporteCerradas')->name('reporteCerradas');
// Route::get('reporteAbiertas','seguimientoActController@reporteAbiertas')->name('reporteAbiertas');
// Route::get('reporteExpiran','seguimientoActController@reporteExpiran')->name('reporteExpiran');
// Route::get('reporteSegConFiltro','seguimientoActController@reporteSegConFiltro')->name('reporteSegConFiltro');

Route::get('reporteFiltrosVista','seguimientoActController@reporteFiltrosVista')->name('reporteFiltrosVista');
Route::POST('anexgridSeguimiento','seguimientoActController@anexgridSeguimiento')->name('anexgridSeguimiento');
Route::POST('anexgridSeguimientoConFiltro','seguimientoActController@anexgridSeguimientoConFiltro')->name('anexgridSeguimientoConFiltro');
Route::get('reporteSeguimientoVista','seguimientoActController@reporteSeguimientoVista')->name('reporteSeguimientoVista');

Route::get('agregarUsuariosAct/{idSegActividad}','seguimientoActController@agregarUsuariosAct')->name('agregarUsuariosAct');
Route::POST('anexgridSeguimientoAsignadas','seguimientoActController@anexgridSeguimientoAsignadas')->name('anexgridSeguimientoAsignadas');
Route::get('correoUsuario','seguimientoActController@correoUsuario')->name('correoUsuario');
Route::get('agregarEquiposActividad','seguimientoActController@agregarEquiposActividad')->name('agregarEquiposActividad');
Route::get('borrarEquipoActividad','seguimientoActController@borrarEquipoActividad')->name('borrarEquipoActividad');
Route::get('reporteActividadesSeg','seguimientoActController@reporteActividadesSeg')->name('reporteActividadesSeg');
Route::get('reporteActividadesSegAbajo','seguimientoActController@reporteActividadesSegAbajo')->name('reporteActividadesSegAbajo');
Route::get('reporteTipoSeg','seguimientoActController@reporteTipoSeg')->name('reporteTipoSeg');
Route::get('reporteTrabajadorSeg','seguimientoActController@reporteTrabajadorSeg')->name('reporteTipoSeg');
Route::get('reporteEmpresaSeg','seguimientoActController@reporteEmpresaSeg')->name('reporteTipoSeg');
Route::get('reportePorAct','seguimientoActController@reportePorAct')->name('reportePorAct');
Route::get('reporteAvance','seguimientoActController@reporteAvance')->name('reporteAvance');


//CRUD CUENTAS
Route::get('altaCuentas','cuentasController@altaCuentas')->name('altaCuentas');
Route::POST('guardarCuentas','cuentasController@guardarCuentas')->name('guardarCuentas');
Route::get('reporteCuentas','cuentasController@reporteCuentas')->name('reporteCuentas');
Route::get('reporteCuentasAbajo','cuentasController@reporteCuentasAbajo')->name('reporteCuentasAbajo');
Route::get('eliminarCuentas/{idCuenta}','cuentasController@eliminarCuentas')->name('eliminarCuentas');
Route::get('restaurarCuentas/{idCuenta}','cuentasController@restaurarCuentas')->name('restaurarCuentas');
Route::get('modificarCuentas/{idCuenta}','cuentasController@modificarCuentas')->name('modificarCuentas');
Route::POST('editarCuentas','cuentasController@editarCuentas')->name('editarCuentas');
Route::get('agregarCuentaProv','cuentasController@agregarCuentaProv')->name('agregarCuentaProv');
Route::get('borrarCuentaProv','cuentasController@borrarCuentaProv')->name('borrarCuentaProv');


//PDF Y XML ORDEN DE COMPRAS
Route::get('pdfOrdenCompras/{idOrden}','pdfXmlOrdenComprasController@pdfOrdenCompras')->name('pdfOrdenCompras');
Route::post('encriptarIdPdf','pdfXmlOrdenComprasController@encriptarIdPdf')->name('encriptarIdPdf');
Route::POST('guardarXmlAndPdf','pdfXmlOrdenComprasController@guardarXmlAndPdf')->name('guardarXmlAndPdf');
Route::POST('guardarComplementoXmlAndPdf','pdfXmlOrdenComprasController@guardarComplementoXmlAndPdf')->name('guardarComplementoXmlAndPdf');
Route::get('confirmarOrdenCompra/{idOrden}/{siNo}','pdfXmlOrdenComprasController@confirmarOrdenCompra')->name('confirmarOrdenCompra');
Route::POST('guardarConfirmacionOrdenCompra','pdfXmlOrdenComprasController@guardarConfirmacionOrdenCompra')->name('guardarConfirmacionOrdenCompra');

///ORDEN COMPRA
Route::get('altaOrdenCompra','ordenCompraController@altaOrdenCompra')->name('altaOrdenCompra');
Route::get('obtenerCuentas','ordenCompraController@obtenerCuentas')->name('obtenerCuentas');
Route::get('obtenerSku','ordenCompraController@obtenerSku')->name('obtenerSku');
Route::get('obtenerPartesVenta','ordenCompraController@obtenerPartesVenta')->name('obtenerPartesVenta');
Route::get('obtenerEquiposEnFactura','ordenCompraController@obtenerEquiposEnFactura')->name('obtenerEquiposEnFactura');
Route::get('obtenerPartesEquiposEnFactura','ordenCompraController@obtenerPartesEquiposEnFactura')->name('obtenerPartesEquiposEnFactura');
Route::get('reporteOrdenesCompra','ordenCompraController@reporteOrdenesCompra')->name('reporteOrdenesCompra');
Route::get('reporteOrdenesCompraAbajo','ordenCompraController@reporteOrdenesCompraAbajo')->name('reporteOrdenesCompraAbajo');
Route::POST('anexgridOrdenesCompra','ordenCompraController@anexgridOrdenesCompra')->name('anexgridOrdenesCompra');
Route::POST('anexgridOrdenesCompraAbajo','ordenCompraController@anexgridOrdenesCompraAbajo')->name('anexgridOrdenesCompraAbajo');
Route::POST('guardarOrdenCompra','ordenCompraController@guardarOrdenCompra')->name('guardarOrdenCompra');
Route::get('eliminarOrden/{idOrden}','ordenCompraController@eliminarOrden')->name('eliminarOrden');
Route::get('modificarOrden/{idOrden}','ordenCompraController@modificarOrden')->name('modificarOrden');
Route::POST('editarOrdenCompra','ordenCompraController@editarOrdenCompra')->name('editarOrdenCompra');
Route::get('registrarProductos','ordenCompraController@registrarProductos')->name('registrarProductos');
Route::get('restaurarOrden/{idOrden}','ordenCompraController@restaurarOrden')->name('restaurarOrden');
Route::get('cargarModalDetalleOrden','ordenCompraController@cargarModalDetalleOrden')->name('cargarModalDetalleOrden');
Route::get('reporteProductosEnOC','ordenCompraController@reporteProductosEnOC')->name('reporteProductosEnOC');
Route::get('reporteProductosEnNotaEntrada','ordenCompraController@reporteProductosEnNotaEntrada')->name('reporteProductosEnNotaEntrada');
Route::get('consultaProductosOC','ordenCompraController@consultaProductosOC')->name('consultaProductosOC');
Route::post('consultaParaExcelOC','ordenCompraController@consultaParaExcelOC')->name('consultaParaExcelOC');
Route::post('excelOrdenesCompra','ordenCompraController@excelOrdenesCompra')->name('excelOrdenesCompra');
Route::post('consultaSumaTotales','ordenCompraController@consultaSumaTotales')->name('consultaSumaTotales');
Route::post('anexgrid_get_ProductosOC','ordenCompraController@anexgrid_get_ProductosOC')->name('anexgrid_get_ProductosOC');
Route::get('validar_oc_mov_internos','ordenCompraController@validar_oc_mov_internos')->name('validar_oc_mov_internos');

// NOTAS DE ENTRADA
Route::get('notasEntrada/{idOrden}','notasEntradaController@notasEntrada')->name('notasEntrada');
Route::get('consultaNotasEntrada/{idOrden}','notasEntradaController@consultaNotasEntrada')->name('consultaNotasEntrada');
Route::post('crearNotaEntrada','notasEntradaController@crearNotaEntrada')->name('crearNotaEntrada');
Route::post('getProductosOC','notasEntradaController@getProductosOC')->name('getProductosOC');
Route::post('agregarProductoNotaEntrada','notasEntradaController@agregarProductoNotaEntrada')->name('agregarProductoNotaEntrada');
Route::post('eliminarProductoNotaEntrada','notasEntradaController@eliminarProductoNotaEntrada')->name('eliminarProductoNotaEntrada');
Route::post('guardarDatosNotaEntrada','notasEntradaController@guardarDatosNotaEntrada')->name('guardarDatosNotaEntrada');
Route::post('eliminarNotaEntrada','notasEntradaController@eliminarNotaEntrada')->name('eliminarNotaEntrada');
Route::get('consultaProdcutosPorNotaEntrada/{idNotaEntrada}','notasEntradaController@consultaProdcutosPorNotaEntrada')->name('consultaProdcutosPorNotaEntrada');
Route::post('actualizarFechasNotaEntrada','notasEntradaController@actualizarFechasNotaEntrada')->name('actualizarFechasNotaEntrada');
Route::post('subirEvidenciaPago','notasEntradaController@subirEvidenciaPago')->name('subirEvidenciaPago');
Route::post('guardarMontosComplementos','notasEntradaController@guardarMontosComplementos')->name('guardarMontosComplementos');
Route::post('subirPDFyXML','notasEntradaController@subirPDFyXML')->name('subirPDFyXML');
Route::post('guardarDetallesEntrega','notasEntradaController@guardarDetallesEntrega')->name('guardarDetallesEntrega');
Route::get('reportePagosProveedores','notasEntradaController@reportePagosProveedores')->name('reportePagosProveedores');
Route::get('consultaReportePagosProveedores','notasEntradaController@consultaReportePagosProveedores')->name('consultaReportePagosProveedores');
Route::post('reiniciarCamposNotaEntrada','notasEntradaController@reiniciarCamposNotaEntrada')->name('reiniciarCamposNotaEntrada');
Route::get('consultaDetallesNotaEntrada/{idNotaEntrada}','notasEntradaController@consultaDetallesNotaEntrada')->name('consultaDetallesNotaEntrada');


Route::get('comboCuentaProv','ordenCompraController@comboCuentaProv')->name('comboCuentaProv');
Route::get('inicialesProv','ordenCompraController@inicialesProv')->name('inicialesProv');
Route::get('infoSkuOrden','ordenCompraController@infoSkuOrden')->name('infoSkuOrden');
Route::get('infoServicioOrden','ordenCompraController@infoServicioOrden')->name('infoServicioOrden');
Route::get('cambiarCantidad','ordenCompraController@cambiarCantidad')->name('cambiarCantidad');
Route::get('cantidadSolicitada','ordenCompraController@cantidadSolicitada')->name('cantidadSolicitada');
Route::get('cambiarBotonOrden','ordenCompraController@cambiarBotonOrden')->name('cambiarBotonOrden');
Route::get('cambiarComboProducto','ordenCompraController@cambiarComboProducto')->name('cambiarComboProducto');
Route::get('borrarProductoOrden','ordenCompraController@borrarProductoOrden')->name('borrarProductoOrden');
Route::get('modificarFechaEntregaProveedor','ordenCompraController@modificarFechaEntregaProveedor')->name('modificarFechaEntregaProveedor');
Route::get('guardarModificacionFechaEntregaProveedor','ordenCompraController@guardarModificacionFechaEntregaProveedor')->name('guardarModificacionFechaEntregaProveedor');


//CRUD Gastos
Route::get('altaGastos', 'gastosController@create')->name('altaGastos');
Route::POST('guardarGastos', 'gastosController@store')->name("guardarGasto");
Route::get('editarGastos/{idGasto}/{idFactura}', 'gastosController@edit')->name("EditarGasto");
Route::POST('actualizarGastos/{id}', 'gastosController@update')->name("ActualizarGasto");
Route::POST('comprobanteGastos/{id}', 'gastosController@upload')->name("SubirComprobante");
Route::get('descargar_archivo_gasto/{name}', 'gastosController@download')->name("DescargarComprobante");
Route::post('bajaLogicaComprobante/{id}', 'gastosController@baja_logica_comprobante')->name("bajaLogicaComprobante");
Route::get('reporteGastos','gastosController@reporte_gastos')->name('reporteGastos');
Route::get('App/reporteGastos','gastosController@app_reporte_gastos')->name('app_reporteGastos');
Route::POST('get-data-gastos','gastosController@getDataGastos');
Route::POST('get-data','gastosController@getDataGastosAbajo')->name('getDataGastosAbajo');
Route::get('reporteGastosAbajo','gastosController@reporteGastosAbajo')->name('reporteGastosAbajo');
Route::POST('cuentas_empresa','gastosController@cuentas')->name('cuentasEmpresa');
Route::get('excel_gastos/{fecha_inicio}/{fecha_fin}/{nombreGasto}/{beneficiario}/{empresa}/{cuenta}/{cliente}/{sucursal}/{referencia}','gastosController@excel_gastos')->name('excelGastos');
Route::POST('gastos_totales','gastosController@gastos_totales')->name('gastosTotales');
Route::GET('baja_logica/{id}/{id_factura}', 'gastosController@baja_logica')->name('baja_logica');
Route::POST('empresa_gasto', 'gastosController@empresa_gasto')->name('empresaGasto');
Route::POST('cuenta_gasto', 'gastosController@cuenta_gasto')->name('cuentaGasto');

//Reporte de Utilidades
Route::get('consultaUtilidades', 'utilidadController@consulta')->name('consultaUtilidades');
Route::get('reporteUtilidad', 'utilidadController@reporte')->name('reporteUtilidad');
Route::post('reporteUtilidadFiltro', 'utilidadController@filtro')->name('reporteUtilidadFiltro');
Route::post('reporteUtilidadFiltroFolio', 'utilidadController@filtrofolio')->name('reporteUtilidadFiltroFolio');
Route::post('reporteUtilidadFiltroCliente', 'utilidadController@filtrocliente')->name('reporteUtilidadFiltroCliente');
Route::post('reporteUtilidadFiltroSucursal', 'utilidadController@filtrosucursal')->name('reporteUtilidadFiltroSucursal');
Route::get('reporteUtilidad3', 'utilidad3Controller@reporte')->name('reporteUtilidad3');
Route::post('reporteUtilidad3Filtro', 'utilidad3Controller@filtro')->name('reporteUtilidad3Filtro');
/////
Route::get('reporteUtilidadGeneral','reporteUtilidadGeneralController@reporteUtilidadGeneral')->name('reporteUtilidadGeneral');
Route::get('reporteUtilidadesAbajo','reporteUtilidadGeneralController@reporteUtilidadesAbajo')->name('reporteUtilidadesAbajo');
Route::POST('excelUtilidadGeneral','reporteUtilidadGeneralController@excelUtilidadGeneral')->name('excelUtilidadGeneral');

Route::post('excelUtilidadServicio','facturasController@excelUtilidadServicio')->name('excelUtilidadServicio');
Route::post('excelGastosServicio','facturasController@excelGastoServicio')->name('excelGastosServicio');
/////
//ReportesExcel
Route::get('ServGExcel','gastosController@gastoServExcel')->name('ServGExcel');
Route::get('ServUExcel','facturasController@UtExcel')->name('ServUExcel');
Route::get('UtilidadesRep','utilidad3Controller@UtilidadRep')->name('UtilidadesRep');

//////CRUD ENTRADAS ALMACEN DE ORDENES DE COMPRA
Route::get('altaEntradaOrdenCompra', 'EntradaOrdenCompraController@altaEntradaOrdenCompra');
Route::get('comboNumeroOrden', 'EntradaOrdenCompraController@comboNumeroOrden');
Route::get('infoOrdenCompra', 'EntradaOrdenCompraController@infoOrdenCompra');
Route::get('productosOrdenCompra', 'EntradaOrdenCompraController@productosOrdenCompra');
Route::get('datosProducto', 'EntradaOrdenCompraController@datosProducto');
Route::get('datosProducto2', 'EntradaOrdenCompraController@datosProducto2');
Route::POST('guardarEntradaOrden', 'EntradaOrdenCompraController@guardarEntradaOrden')->name('guardarEntradaOrden');
Route::get('reporteEntradaOrdenCompra', 'EntradaOrdenCompraController@reporteEntradaOrdenCompra')->name('reporteEntradaOrdenCompra');
Route::POST('filtroEntradaOrden', 'EntradaOrdenCompraController@filtroEntradaOrden')->name('filtroEntradaOrden');
Route::get('modificarEntradaOrden/{idEntradaOrden}', 'EntradaOrdenCompraController@modificarEntradaOrden')->name('modificarEntradaOrden');
Route::POST('editarEntradaOrden', 'EntradaOrdenCompraController@editarEntradaOrden')->name('editarEntradaOrden');
Route::get('eliminarEntradaOrden/{idEntradaOrden}', 'EntradaOrdenCompraController@eliminarEntradaOrden')->name('eliminarEntradaOrden');
Route::get('restaurarEntradaOrden/{idEntradaOrden}', 'EntradaOrdenCompraController@restaurarEntradaOrden')->name('restaurarEntradaOrden');

//Rutas para gastos operativos
Route::get('gastosOperativos', 'gastosOperativosController@index')->name('GastosOperativos');
Route::get('gastosOperativosFecha', 'gastosOperativosController@show')->name('GastosOperativosFecha');
Route::POST('get-data-gastos-operativos','gastosOperativosController@getDataGastos');
Route::POST('get-data-gastos-operativos/{fechaInicio}/{fechaFin}','gastosOperativosController@getDataGastos');
Route::get('altaGastosOperativos', 'gastosOperativosController@create')->name('altaGastosOperativos');
Route::POST('guardarGastosOperativos', 'gastosOperativosController@store')->name("guardarGastoOperativo");
Route::get('editarGastosOperativos/{idGasto}', 'gastosOperativosController@edit')->name("EditarGasto");
Route::POST('actualizarGastosOperativos/{id}', 'gastosOperativosController@update')->name("ActualizarGastoOperativo");
Route::POST('comprobanteGastosOperativos/{id}', 'gastosOperativosController@upload')->name("SubirComprobanteOperativo");
Route::get('descargar_archivo_gastoOperativo/{name}', 'gastosOperativosController@download')->name("DescargarComprobanteOperativo");
Route::post('bajaLogicaComprobanteOperativo/{id}', 'gastosOperativosController@baja_logica_comprobante')->name("bajaLogicaComprobanteOperativo");
Route::post('gastosOperativosEmpresa/{id}', 'gastosOperativosController@gastosOperativosEmpresa')->name("GastosOperativosEmpresa");
Route::GET('baja_logica_go/{id}', 'gastosOperativosController@baja_logica')->name('baja_logica_go');
Route::get('excel_gastos_operativos/{fechaInicio}/{fechaFin}/{filtro}/{value}','gastosOperativosController@exportar_excel_gastosOperativos')->name('excelGastosOperativos');
Route::get('gastosOperativos2', 'gastosOperativosController@ind')->name('GastosOperativos2');

//Cuentas contables 
Route::get('reporteCuentasContables','cuentasContablesController@reporteCuentasContables')->name('reporteCuentasContables');
Route::post('excelCuentasContables','cuentasContablesController@excelCuentasContables')->name('excelCuentasContables');
Route::POST('excelCuentasContables','cuentasContablesController@excelCuentasContables')->name('excelCuentasContables');
Route::get('reporteCuentasContablesAbajo','cuentasContablesController@reporteCuentasContablesAbajo')->name('reporteCuentasContablesAbajo');
Route::get('cuentasgcm','cuentasContablesController@cuentasgcm')->name('cuentasgcm');
Route::get('cuentascym','cuentasContablesController@cuentascym')->name('cuentacym');
Route::get('cuentassurja','cuentasContablesController@cuentassurja')->name('cuentassurja');


// Rutas extra para gastos
Route::get('cuentasporEmpresa/{empresa}', 'gastosController@cuentasEmpresa')->name('cuentasporEmpresa');
Route::get('serviciosList/{id}', 'gastosOperativosController@getServiciosList')->name('getServiciosList');
Route::get('gastoList/{idGasto}', 'gastosOperativosController@getGastosList')->name('getGastosList');
Route::get('deleteGastoServicio/{idGasto}', 'gastosOperativosController@deleteGastoServicio')->name('deleteGastoServicio');
Route::post('asignarServicioGasto', 'gastosOperativosController@asignarServicioGasto')->name('asignarServicioGasto');

//SKU Servicio
Route::get('reporteSKUServicio','reporteSKUServicioController@reporteSKUServicio')->name('reporteSKUServicio');
Route::get('reporteSKUServicioAbajo','reporteSKUServicioController@reporteSKUServicioAbajo')->name('reporteSKUServicioAbajo');
Route::POST('excelSKU','reporteSKUServicioController@excelSKU')->name('excelSKU');
Route::get('peticionSKU', 'reporteSKUServicioController@peticionSKU')->name('peticionSKU');  //← PDF reporte 
// * Rutas para las comisiones
Route::get('reportecomisiones', function(){ return view('reporteComisiones');})->name('reportecomisiones');

// * Rutas para los reportes de ventas por mes
Route::get('reporteventas', 'VentasPorMesController@index')->name('ventasmesindex');
Route::get('reporteventasdata/{month}/{year}/{filterEmpresa}/{filterVendedor}/{filter}', 'VentasPorMesController@getVentasData')->name('ventasmesdata');
Route::get('reporteventasdataexport/{month}/{year}/{filterEmpresa}/{filterVendedor}/{filter}', 'VentasPorMesController@excel_reporte_ventas')->name('ventasmesexport');
Route::get('reporteventasempresa/{month}/{year}/{filter}', 'VentasPorMesController@getEmpresaReport');
Route::get('reporteventasvendedor/{month}/{year}/{filter}', 'VentasPorMesController@getVendedoresReport');
Route::get('reporteventasempresatotal/{month}/{year}/{filter}', 'VentasPorMesController@getEmpresaTotal');
Route::get('reporteventasvendedortotal/{month}/{year}/{filter}', 'VentasPorMesController@getVendedoresTotal');

// RANGO Comisiones
Route::get('rangocomisiones', 'comisionesController@indexrango')->name('indexrango');
Route::post('storerango', 'comisionesController@storerango')->name('storerango');
Route::get('createrango', 'comisionesController@createrango')->name('createrango');
Route::get('editrango', 'comisionesController@editrango')->name('editrango');
Route::get('updaterango/{idrango}', 'comisionesController@updaterango')->name('updaterango');
Route::get('deleterango', 'comisionesController@deleterango')->name('deleterango');


// Comisiones
Route::post('crearComisiones', 'comisionesController@crearComision')->name('crearComision');
Route::get('eliminarComision/{idComision}','comisionesController@deletecomision')->name('deletecomision');
Route::get('modificarComisiones/{idComision}','comisionesController@modificarComisiones')->name('modificarComisiones');
Route::post('get-data-comisiones','comisionesController@getDataFacturas');

//* reporte de gastos
Route::get('reportegastos','reporteDeGastozController@reporteGastoz')->name('reportegastos');
Route::get('reporteGastozAbajo','reporteDeGastozController@reporteGastozAbajo')->name('reporteGastozAbajo');
Route::POST('excelReporteGastoz','reporteDeGastozController@excelReporteGastoz')->name('excelReporteGastoz');
// * reporte de gastos opcion 2
Route::get('reporteGeneralGastos','reporteGastosGeneralController@reporteGastosGeneral')->name('reporteGeneralGastos');
Route::get('reporteGeneralGastosAbajo','reporteGastosGeneralController@reporteGeneralGastosAbajo')->name('reporteGeneralGastosAbajo');
Route::POST('excelGastosGeneral','reporteGastosGeneralController@excelGastosGeneral')->name('excelGastosGeneral');
//*Catalogo Archivos Unicos
Route::get('/archivosUnicos','ArchivosUnicosController@index')->name('archivosUnicos');
Route::get('altaArchivoUnico','ArchivosUnicosController@AltaArchivoUnico')->name('altaArchivoUnico');
Route::post('guardarArchivoUnico','ArchivosUnicosController@GuardarArchivoUnico')->name('guardarArchivoUnico');
Route::get('reporteArchivoUnico','ArchivosUnicosController@reporteArchivoUnico')->name('reporteArchivoUnico');
Route::get('verArchivoUnico','ArchivosUnicosController@editarArUn')->name('verArchivoUnico');
Route::POST('editarArchivoUnico','ArchivosUnicosController@editarArchivoUnico')->name('editarArchivoUnico');
Route::get('eliminarArchivoUnico/{id}','ArchivosUnicosController@desactivarArchivoUnico')->name('eliminarArchivoUnico');
Route::get('obtenerArchivoUnico/{id}','ArchivosUnicosController@obtenerArchivoUnico')->name('obtenerArchivoUnico');

// Pagina Web
Route::get('iniciopagina', function () {
    return view('paginaweb.iniciopagina');
})-> name('iniciopagina');

Route::get('reparacion', function () {
    return view('paginaweb.reparacion');
})-> name('reparacion');

Route::get('logicos', function () {
    return view('paginaweb.logicos');
})-> name('logicos');

Route::get('monitores', function () {
    return view('paginaweb.monitores');
})-> name('monitores');

Route::get('robot', function () {
    return view('paginaweb.robot');
})-> name('robot');

Route::get('soldadoras', function () {
    return view('paginaweb.soldadoras');
})-> name('soldadoras');

Route::get('torsion', function () {
    return view('paginaweb.torsion');
})-> name('torsion');

Route::get('hidraulica', function () {
    return view('paginaweb.hidraulica');
})-> name('hidraulica');

Route::get('cilindro', function () {
    return view('paginaweb.cilindro');
})-> name('cilindro');

Route::get('muñeca', function () {
    return view('paginaweb.muñeca');
})-> name('muñeca');

Route::get('bomba', function () {
    return view('paginaweb.bomba');
})-> name('bomba');

Route::get('puntas', function () {
    return view('paginaweb.puntas');
})-> name('puntas');

Route::get('servo', function () {
    return view('paginaweb.servo');
})-> name('servo');

Route::get('motores', function () {
    return view('paginaweb.motores');
})-> name('motores');

Route::get('Husos', function () {
    return view('paginaweb.Husos');
})-> name('Husos');

Route::get('tornillos', function () {
    return view('paginaweb.tornillos');
})-> name('tornillos');

Route::get('ingenieria', function () {
    return view('paginaweb.ingenieria');
})-> name('ingenieria');

Route::get('gestion', function () {
    return view('paginaweb.gestion');
})-> name('gestion');

Route::get('compromiso', function () {
    return view('paginaweb.compromiso');
})-> name('compromiso');

Route::get('administracion', function () {
    return view('paginaweb.administracion');
})-> name('administracion');

Route::get('stamp', function () {
    return view('paginaweb.stamp');
})-> name('stamp');

Route::get('valvulas', function () {
    return view('paginaweb.valvulas');
})-> name('valvulas');

Route::get('industrias', function () {
    return view('paginaweb.industrias');
})-> name('industrias');

Route::get('sobrenosotros', function () {
    return view('paginaweb.sobrenosotros');
})-> name('sobrenosotros');

Route::get('ubicaciones', function () {
    return view('paginaweb.ubicaciones');
})-> name('ubicaciones');

Route::get('principalesoem', function () {
    return view('paginaweb.principalesoem');
})-> name('principalesoem');

Route::get('contactanos', function () {
    return view('paginaweb.contactanos');
})-> name('contactanos');

Route::get('controlador', function () {
    return view('paginaweb.controlador');
})-> name('controlador');

Route::get('principalpagina', function () {
    return view('paginaweb.principalpagina');
})-> name('principalpagina');

Route::get('productospagina', 'ProductosController@productos' )-> name('productos');
Route::get('servicios', 'ServiciosPaginaController@servicios')-> name('servicios');

Route::get('productos/detalle/{partesventa}', 'ProductosController@detalle' )-> name('detalle');

Route::get('/producto/{pdf}', 'ProductosController@downloadFile')->name('downpdf');

Route::get('correo','EmailPaginaController@correo')->name('correo');
