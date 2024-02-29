<div id="infoFactura">
    <div class="sub-title">* Monto factura:</div>
    <div>
        {{Form::text('montoFactura',$consultaOrden->total,['class' => 'form-control', 'readonly','id'=>'montoFactura'])}}    
        {{Form::hidden('numeroFactura',$consultaOrden->codigoOrden,['class' => 'form-control', 'readonly'])}}    
        {{Form::hidden('moneda',$consultaOrden->moneda,['class' => 'form-control', 'readonly'])}}    
    </div>
    <div class="sub-title">Tipo de moneda:</div>
    <div>
        @if($consultaOrden->moneda=='MXN')
            {{Form::radio('moneda','MXN',true,['disabled'])}} MXN&nbsp;&nbsp;&nbsp;
            {{Form::radio('moneda','USD', false, ['disabled'])}} USD&nbsp;&nbsp;&nbsp;
        @else
            {{Form::radio('moneda','MXN', false, ['disabled'])}} MXN&nbsp;&nbsp;&nbsp;
            {{Form::radio('moneda','USD',true, ['disabled'])}} USD&nbsp;&nbsp;&nbsp;
        @endif
    </div>
    <div class="sub-title">* Archivo de factura:</div>
    <div  id="imagenPDF">
        <a target="_blank" href="{{asset('archivos/'.$consultaOrden->cotizacionProveedor)}}">
        @if(strpos($consultaOrden->cotizacionProveedor,'pdf'))
            <img src="{{asset('img/iconpdfdown.png')}}" height=100 width=100>
        @else
            <!-- <img src="{{asset('/iconpdfdown.png')}}" height=80 width=80> -->
            <img src="{{asset ('public/archivos/'.$consultaOrden->cotizacionProveedor)}}" height=80 width=80>
        @endif
        </a>
        
    </div>
</div>