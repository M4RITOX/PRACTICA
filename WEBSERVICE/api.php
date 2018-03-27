<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../WEBSERVICE/dbconfig.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

$app = new \Slim\App($c);

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

//METODOS PARA EL ACCESO A USUARIO


//METODO getUsers UTILIZANDO GET!!!! (AL SER INFO DELICADA SE DEBE HACER CON POST)
$app->get('/getUsers/{json}',function(Request $request,Response $response, array $args){
    $cuerpo = $args['json'];
    
    
    
    $parametroUno = $request->getAttribute('json');
    
    //vamos a ver si los parametros del get se pueden enviar en un solo json
    $contenido = json_decode($args['json'],true);
    //echo $contenido["pUser"].$contenido["pPass"];
    //echo $contenido["pPass"];
    
    //echo 'ABC';
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        
        /*Esto si sirve pero vamos a probar con llamada directa a STORED PROCEDURES
        $sql = "SELECT * FROM USUARIO"; //aca tiene que ir el stored procedure
        */
        
        
        //LLAMADA DIRECTA A STORED PROCEDURES.
        $sql = "exec getUsers ?,?";
        
        
        
        $stmt = $conn->prepare($sql); //aca vendria a ser que la conexion a la bd se prepara para ejecutar el sql
        $stmt->bindParam(1,$contenido["pUser"]);
        $stmt->bindParam(2,$contenido["pPass"]);
        
        
        $stmt->execute(); //aca se ejecuta pero para ver el contenido es necesario el fetchALL
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ); //aca esta el array de resultado, el cual preferiblemente se debe convertir a JSON
        //IMPORTANTISIMO HACER EL fecthALL con el atributo PDO::FETCH_OBJ
        $conn = null;
        echo json_encode($resultado);
        
        
        
        
    }catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
        
    }
    
    
    
    
});

//METODO getUser utilizando POST!!!!
$app->post('/getUser',function(Request $request,Response $response){
    //primero vamos con la obtencion de parametros
    $pUserName = $request->getParam('pUserName');
    $pContra = $request->getParam('pContra');
    
    
    //ya tenemos los parametros, los siguiente es realizar la conexion con la BD y la llamada al stored procedure
    
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec getUsers ?,?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(1,$pUserName);
        $stmt->bindParam(2,$pContra);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        $conn = null;
        echo json_encode($resultado);
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
    }
    
    
    
});


//METODO addUsers
//Este necesita ed un POST para proteger los parametros/argumentos, de igual forma tiene una rutina de callback y segun el resultado
//se puede realizar debug y control de excepciones
//Recordar activar en la insercion la opcion de SET NO COUNT


$app->post('/addUsers',function(Request $request,Response $response){
    
   //aca viene el cuerpo de la funcion, primero viene la obtencion de parametros
    //en este caso la funcion de agregar nuevos usuarios necesita los siguientes parametros: userName,contra,nombre,ApellidoU,ApellidoD,correo
    $pUserName = $request->getParam('pUserName');
    $pContra = $request->getParam('pContra');
    $pNombre = $request->getParam('pNombre');
    $pApellidoU = $request->getParam('pApellidoU');
    $pApellidoD = $request->getParam('pApellidoD');
    $pCorreo = $request->getParam('pCorreo');
    
    //Ok una vez que se tienen los parametros viene el try catch para 
        try{
            //viene la conexion con la bd
            $bd = new dbconnection();
            $conn = $bd->connect();
            
            //listo tenemos la conexion con la bd, ahora vamos preparando la llamada al stored procedure
            $sql = "exec addUsers ?,?,?,?,?,?";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1,$pUserName);
            $stmt->bindParam(2,$pContra);
            $stmt->bindParam(3,$pNombre);
            $stmt->bindParam(4,$pApellidoU);
            $stmt->bindParam(5,$pApellidoD);
            $stmt->bindParam(6,$pCorreo);
            
            $stmt->execute();
            
            $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;
            echo json_encode($resultado);
            
        }
        catch(PDOException $e){
            echo '{"error":{"text":'.$e->getMessage()."}}";
        }
    
});


//METODOS CLIENTS

//METODO GET CLIENT(GET)

$app->get('/getClients/{json}',function(Request $request,Response $response, array $args){
    //$cuerpo = $args['json'];
    
    
    
    //$parametroUno = $request->getAttribute('json');
    
    //vamos a ver si los parametros del get se pueden enviar en un solo json
    $contenido = json_decode($args['json'],true);
    //echo $contenido["pUser"].$contenido["pPass"];
    //echo $contenido["pPass"];
    
    //echo 'ABC';
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        
        /*Esto si sirve pero vamos a probar con llamada directa a STORED PROCEDURES
        $sql = "SELECT * FROM USUARIO"; //aca tiene que ir el stored procedure
        */
        
        
        //LLAMADA DIRECTA A STORED PROCEDURES.
        $sql = "exec getClientes ?";
        
        
        
        $stmt = $conn->prepare($sql); //aca vendria a ser que la conexion a la bd se prepara para ejecutar el sql
        $stmt->bindParam(1,$contenido["pCedCliente"]);
        //$stmt->bindParam(2,$contenido["pPass"]);
        
        
        $stmt->execute(); //aca se ejecuta pero para ver el contenido es necesario el fetchALL
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ); //aca esta el array de resultado, el cual preferiblemente se debe convertir a JSON
        //IMPORTANTISIMO HACER EL fecthALL con el atributo PDO::FETCH_OBJ
        $conn = null;
        echo json_encode($resultado);
        
        
        
        
    }catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
        
    }
    
    
    
    
});


//METODO ADDCLIENT(POST)

$app->post('/addClient',function(Request $request,Response $response){
    
    
    $pCedCliente = $request->getParam('pCedCliente');
    $pNombreCliente = $request->getParam('pNombre');
    $pApellidoClienteU = $request->getParam('pApellidoClienteU');
    $pApellidoClienteD = $request->getParam('pApellidoClienteD');
    $pTelefono = $request->getParam('pTelefono');
    
    try{
        $bd = new dbconnection();
        $conn = $bd->connect();
        
        $sql = "exec addClientes ?,?,?,?,?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(1,$pCedCliente);
        $stmt->bindParam(2,$pNombreCliente);
        $stmt->bindParam(3,$pApellidoClienteU);
        $stmt->bindParam(4,$pApellidoClienteD);
        $stmt->bindParam(5,$pTelefono);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($resultado);
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
        
        
    }
    
    
    
    
});

//METODOS SERVICES

//GET SERVICES (TODOS)
$app->get('/getServices',function(Request $request,Response $response){
    //dado que los va a devolver todos, no hace falta pasarle parametros
    //entra al try catch de una
    
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec getServices";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
    }
});

//ADD SERVICE (POST)
$app->post('/addServices',function(Request $request,Response $response){
    $pNombreServicio = $request->getParam('pNombreServicio');
    $pDescripcionServicio = $request->getParam('pDescripcionServicio');
    
    try{
        $bd = new dbconnection();
        $conn = $bd->connect();
        
        $sql = "exec addService ?,?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1,$pNombreServicio);
        $stmt->bindParam(2,$pDescripcionServicio);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
        
        
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
    }
    
});

//METODOS SERVICIOXCLIENTE

//GET SERVICIOSXCLIENTE SEGUN CEDULA

$app->get('/getServicesClient/{json}',function(Request $request,Response $response,array $args){
    
    
    $contenido = json_decode($args['json'],true);
    
    //Ya se han obtenido los parametros 
    //ahora vamos con la llamada al stored procedure
    try{
        
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec getServicesxClient ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1,$contenido["pFKcedCliente"]);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
        
        
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
    }
    
    
    
});

$app->post('/addServicesClient',function(Request $request,Response $response){
    $pFKcodigoServicio = $request->getParam('pFKcodigoServicio');
    $pFKcedCliente = $request->getParam('pFKcedCliente');
    $pSaldo = $request->getParam('pSaldo');
    
    //una vez que se han pbtenido los parametros se debe proceder a llamar al stored procedure
    
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec addServicexClient ?,?,?";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(1,$pFKcodigoServicio);
        $stmt->bindParam(2,$pFKcedCliente);
        $stmt->bindParam(3,$pSaldo);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
        
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage()."}}";
    }
    
    
});

//METODOS FACTURA


//METODO MAKE BILL (POST)

$app->post('/makeBill',function(Request $request,Response $response){
    
    //vamos con la obtencion de parametros
    //son un monton asi que vamos ahi
    $pFKuserName = $request->getParam('pFKuserName');
    $pNombreUsuario = $request->getParam('pNombreUsuario');
    $pApellidoU = $request->getParam('pApellidoU');
    $pApellidoD = $request->getParam('pApellidoD');
    $pFKcedCliente = $request->getParam('pFKcedCliente');
    $pNombreCliente = $request->getParam('pNombreCliente');
    $pApellidoClienteU = $request->getParam('pApellidoClienteU');
    $pApellidoClienteD = $request->getParam('pApellidoClienteD');
    $pTelefono = $request->getParam('pTelefono');
    $pFKcodigoServicio = $request->getParam('pFKcodigoServicio');
    $pAbono = $request->getParam('pAbono');
    $pDia = $request->getParam('pDia');
    $pMes = $request->getParam('pMes');
    $pAnio = $request->getParam('pAnio');
    
    //Listo con la obtencion de parametros, ahora sigue la conexion y ejecucion del st
    
    try{
        
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec makeBill ?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(1,$pFKuserName);
        $stmt->bindParam(2,$pNombreUsuario);
        $stmt->bindParam(3,$pApellidoU);
        $stmt->bindParam(4,$pApellidoD);
        $stmt->bindParam(5,$pFKcedCliente);
        $stmt->bindParam(6,$pNombreCliente);
        $stmt->bindParam(7,$pApellidoClienteU);
        $stmt->bindParam(8,$pApellidoClienteD);
        $stmt->bindParam(9,$pTelefono);
        $stmt->bindParam(10,$pFKcodigoServicio);
        $stmt->bindParam(11,$pAbono);
        $stmt->bindParam(12,$pDia);
        $stmt->bindParam(13,$pMes);
        $stmt->bindParam(14,$pAnio);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage().'}}';
    }
    
    
});

//METODO CANCELAR FACTURA (POST)



$app->post('/cancelBill',function(Request $request,Response $response){
   //vamos obteniendo los parametros para cancelar la factura, en este caso seria el idFactura
    $pIdFactura = $request->getParam('pIdFactura');
    
    //viene try catch
    try{
        $db = new dbconnection();
        $conn = $db->connect();
        
        $sql = "exec cancelBill ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1,$pIdFactura);
        
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo json_encode($resultado);
        
    }
    catch(PDOException $e){
        echo '{"error":{"text":'.$e->getMessage().'}}';
    }
    
});

$app->run();

