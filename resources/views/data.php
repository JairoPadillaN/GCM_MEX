<?php
require_once 'anexgrid.php';

try
{
    $anexGrid = new AnexGrid();
    
    /* Si es que hay filtro, tenemos que crear un WHERE dinámico */
    $wh = "id > 0";
    
    foreach($anexGrid->filtros as $f)
    {
        if($f['columna'] == 'Nombre') $wh .= " AND CONCAT(Nombre, ' ', Apellido) LIKE '%" . addslashes ($f['valor']) . "%'";
        if($f['columna'] == 'Correo') $wh .= " AND Correo LIKE '%" . addslashes ($f['valor']) . "%'";
        if($f['columna'] == 'Sexo' && $f['valor'] != '') $wh .= " AND Sexo = '" . addslashes ($f['valor']) . "'";
        if($f['columna'] == 'Profesion_id' && $f['valor'] != '') $wh .= " AND Profesion_id = '" . addslashes ($f['valor']) . "'";
    }
    
    /* Nos conectamos a la base de datos */
    $db = new PDO("mysql:dbname=pruebasgermancontrol;host=mysql.germancontrolmotion.com;charset=utf8", "ashley2347378_admin", "ashley2347347341");
    //$db = new PDO("mysql:dbname=anexsoft_anexgrid;host=localhost;charset=utf8", "anexsoft_admin", "aspodiaowpdas234" );
    
    /* Nuestra consulta dinámica */
    $registros = $db->query("
        SELECT * FROM empleados
        WHERE $wh ORDER BY $anexGrid->columna $anexGrid->columna_orden
        LIMIT $anexGrid->pagina,$anexGrid->limite")->fetchAll(PDO::FETCH_ASSOC
     );
    
    $total = $db->query("
        SELECT COUNT(*) Total
        FROM empleados
        WHERE $wh
    ")->fetchObject()->Total;
    
    foreach($registros as $k => $r)
    {
        $profesion = $db->query("SELECT * FROM profesiones p WHERE p.id = " . $r['Profesion_id'])
                        ->fetch(PDO::FETCH_ASSOC);
        
        $registros[$k]['Profesion'] = $profesion;
    }

    header('Content-type: application/json');
    print_r($anexGrid->responde($registros, $total));
}
catch(PDOException $e)
{
    echo $e->getMessage();
}