<script src="{!! asset('assets/js/jquery-1.10.2.js')!!}"></script>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th>Nombre del archivo</th>
                <th>Detalle</th>
                <th>Archivo</th>
            </tr>
        </thead>
        <tbody>

            <tr>

                <td align="center">{{$consulta->archivo1}}</td>


                @if($consulta->detalleArchivo1 == '')
                <td align="center">Sin detalle</td>
                @else
                <td align="center">{{$consulta->detalleArchivo1}}</td>
                @endif


                <td>
                        <center>
                        @if($consulta->archivo1=='Sin archivo' || $consulta->archivo1=='')
                        <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                        @else
                            @if(($consulta->verArchivo1 == 'Solo yo' && $sidu == $consulta->idu) || ($stipo == 'Administrador') || ($consulta->verArchivo1 == 'Todos'))
                            <a target="_blank" href="{{asset('archivos/'.$consulta->archivo1)}}">
                                <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                            </a>
                            @else
                                @if($consulta->verArchivo1 == 'Mi area' && $stipo == $consulta->tipousuario)
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->archivo1)}}">
                                    <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                                    </a>
                                @else
                                    <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                                    <br>
                                    <font SIZE=2 color ="red">Sin permiso para ver.</font>
                                @endif
                            @endif
                        @endif
                        </center>
                </td>

            </tr>
            <tr>
                <td align="center">{{$consulta->archivo2}}</td>

                @if($consulta->detalleArchivo2 == '')
                <td align="center">Sin detalle</td>
                @else
                <td align="center">{{$consulta->detalleArchivo2}}</td>
                @endif

                <td>
                        <center>
                        @if($consulta->archivo2=='Sin archivo' || $consulta->archivo2=='')
                        <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                        @else
                            @if(($consulta->verArchivo2 == 'Solo yo' && $sidu == $consulta->idu) || ($stipo == 'Administrador') || ($consulta->verArchivo2 == 'Todos'))
                            <a target="_blank" href="{{asset('archivos/'.$consulta->archivo2)}}">
                                <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                            </a>
                            @else
                                @if($consulta->verArchivo2 == 'Mi area' && $stipo == $consulta->tipousuario)
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->archivo2)}}">
                                    <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                                    </a>
                                @else
                                    <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                                    <br>
                                    <font SIZE=2 color ="red">Sin permiso para ver.</font>
                                @endif
                            @endif
                        @endif
                        </center>
                </td>
            </tr>
            <tr>
                <td align="center">{{$consulta->archivo3}}</td>

                @if($consulta->detalleArchivo3 == '')
                <td align="center">Sin detalle</td>
                @else
                <td align="center">{{$consulta->detalleArchivo3}}</td>
                @endif

                <td>
                        <center>
                        @if($consulta->archivo3=='Sin archivo' || $consulta->archivo3=='')
                        <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                        @else
                            @if(($consulta->verArchivo3 == 'Solo yo' && $sidu == $consulta->idu) || ($stipo == 'Administrador') || ($consulta->verArchivo3 == 'Todos'))
                            <a target="_blank" href="{{asset('archivos/'.$consulta->archivo3)}}">
                                <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                            </a>
                            @else
                                @if($consulta->verArchivo3 == 'Mi area' && $stipo == $consulta->tipousuario)
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->archivo3)}}">
                                    <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                                    </a>
                                @else
                                    <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                                    <br>
                                    <font SIZE=2 color ="red">Sin permiso para ver.</font>
                                @endif
                            @endif
                        @endif
                        </center>
                    
                </td>
            </tr>
            <tr>
                <td align="center">{{$consulta->archivo4}}</td>

                @if($consulta->detalleArchivo4 == '')
                <td align="center">Sin detalle</td>
                @else
                <td align="center">{{$consulta->detalleArchivo4}}</td>
                @endif

                <td>
                        <center>
                        @if($consulta->archivo4=='Sin archivo' || $consulta->archivo4=='')
                        <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                        @else
                            @if(($consulta->verArchivo4 == 'Solo yo' && $sidu == $consulta->idu) || ($stipo == 'Administrador') || ($consulta->verArchivo4 == 'Todos'))
                            <a target="_blank" href="{{asset('archivos/'.$consulta->archivo4)}}">
                                <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                            </a>
                            @else
                                @if($consulta->verArchivo4 == 'Mi area' && $stipo == $consulta->tipousuario)
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->archivo4)}}">
                                    <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                                    </a>
                                @else
                                    <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                                    <br>
                                    <font SIZE=2 color ="red">Sin permiso para ver.</font>
                                @endif
                            @endif
                        @endif
                        </center>
                </td>
            </tr>
            <tr>
                <td align="center">{{$consulta->archivo5}}</td>

                @if($consulta->detalleArchivo5 == '')
                <td align="center">Sin detalle</td>
                @else
                <td align="center">{{$consulta->detalleArchivo5}}</td>
                @endif

                <td>
                     <center>
                        @if($consulta->archivo5=='Sin archivo' || $consulta->archivo5=='')
                        <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                        @else
                            @if(($consulta->verArchivo5 == 'Solo yo' && $sidu == $consulta->idu) || ($stipo == 'Administrador') || ($consulta->verArchivo5 == 'Todos'))
                            <a target="_blank" href="{{asset('archivos/'.$consulta->archivo5)}}">
                                <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                            </a>
                            @else
                                @if($consulta->verArchivo5 == 'Mi area' && $stipo == $consulta->tipousuario)
                                    <a target="_blank" href="{{asset('archivos/'.$consulta->archivo5)}}">
                                    <img src="{{asset('img/iconoDown.png')}}" height=30 width=30>
                                    </a>
                                @else
                                    <img src="{{asset('img/sinArch.png')}}" height="20" width="20">
                                    <br>
                                    <font SIZE=2 color ="red">Sin permiso para ver.</font>
                                @endif
                            @endif
                        @endif
                        </center>
                </td>
            </tr>
        </tbody>
    </table>
</div>