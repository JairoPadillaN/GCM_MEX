<style>
    table{
        width: 100%;
        border: solid 1px black;
        /* text-align:justify */
        font-family: DejaVu Sans, sans-serif;
    }
    td{
        /* border: solid 1px black; */
    }
    span{
        /* background-color:red; */
        width: 350px;
        /* height:1em; */
        display:inline-block;
    }
</style>

<span>
    <font size="0">
    <table>
        <tbody>
            <tr>
                <td colspan=2 style="text-align:center">
                    <div>
                        {{$registros->empresa}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align:justify;padding-left: 5px;">
                    <div>
                        SKU: {{$registros->sku}}
                    </div>
                    <div>
                        Descripción: {{$registros->descripcion}}
                    </div>
                    <div>
                        OC: {{$registros->ordenCompra}}
                    </div>
                    <div>
                        Fecha entrada: {{$registros->fechaEntrada}}
                    </div>
                </td>
                <td style="text-align:right;padding-right: 5px;">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(135)->generate($texto)) !!} ">
                </td>
            </tr>
            <tr>
                <td colspan=2 style="text-align:center">
                    <div>
                        {{$registros->numeroSerie}}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    </font>
</span>
