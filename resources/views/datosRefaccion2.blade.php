<div class="row">
    <div class="col-sm-6">
        <div class="sub-title">Foto placa: </div>
        <div>
        <center>
        @if($consultaRefacciones->fotoPlaca =="" || $consultaRefacciones->fotoPlaca =="Sin archivo")
            <div style="color:red" >Sin foto</div></center>
        @else
            <div><a target="_blank"
                    href="{{asset ('public/archivos/'.$consultaRefacciones->fotoPlaca)}}"><img 
                        src="{{asset ('public/archivos/'.$consultaRefacciones->fotoPlaca)}}" height=120
                        width=120></a></div>
                        
        @endif
        </div>
    </div>
    <div class="col-sm-6">
        <div class="sub-title">Foto principal: </div>
        <div>
        <center>
        @if($consultaRefacciones->fotoPrincipal =="" || $consultaRefacciones->fotoPrincipal =="Sin archivo")
            <div style="color:red" >Sin foto</div>
            </center>
        @else
            <div><a target="_blank"
                    href="{{asset ('public/archivos/'.$consultaRefacciones->fotoPrincipal)}}"><img
                        src="{{asset ('public/archivos/'.$consultaRefacciones->fotoPrincipal)}}" height=120
                        width=120></a></div>
        @endif
        </div>
    </div>                                            
</div>                                    

