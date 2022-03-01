<?php
session_start();
$page_id=677853896008902;
$POST_TITULO='Por qué debes adaptar tu sitio web a dispositivos móviles';
$POST_ENLACE='https://maymi.com.ve/blog/ver/id/por-qu-debes-adaptar-tu-sitio-web-a-dispositivos-mviles';
$POST_IMAGEN='https://maymi.com.ve/assets/img/20be935e39df8dfd2a273efdbabecb5d.jpg';
$POST_NOMBRE='Paco';
$POST_LEYENDA='Lo mejor de lo mejor.';
$POST_DESCRIPCION='Calidad suprema máxima del futuro más futuro de todos.';
?>
<html>
<head>
<meta charset="utf-8">
<title>Super Facebook - Publicar en página</title>
<style>
html{
margin:0;
padding:30px;
background-color:#F5F5F5;
}
body{
margin:0;
padding:40px;
background-color:#FFF;
box-shadow:0 3px 5px rgba(0,0,0,0.5);
}
p{
margin:0;
padding:10px 0;
}
</style>
</head>
<body>
<?php
require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

//Incluir datos secretos mágicos
include('./super-configuracion.php');

$fb = new Facebook\Facebook([
  'app_id' => $APP_ID,
  'app_secret' => $APP_SECRET,
  'default_graph_version' => $GRAPH_VERSION,
]);

// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken('EAAviiWxIpS4BANiV3txmHBAglZCWLV9TXL6QWQxIXyESokcoETZBeSINi05ppYzVZAYdTJQTBUbiHEHIvyJmY1NzxIhAQ51hurZAckqD2T08loJ2l6OBCBNerLEMxt0onXhAbZAnV9tDySuMdC956OWqNnvng65Kc2ZB78ZB0L05UZCMy9cKphDVcF03ZAbZAX2mXr52h6P4fUYzkDG2PLF1KA30t9LryGNPUpDPRMnZBMj1V753ahns8OJ');

try {
  $response = $fb->get('/me');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

echo '<p><b>Conectado como</b>: ' . $userNode->getName().'</p>';

if(false==$page_id){
echo '<p>Busca la página en la que quieres publicar.</p>
<p>Asigna su ID a la variable <b>$page_id</b>
<p>Eso lo haces en este mismo archivo <i>publicar-en-pagina.php</i> en la <i>línea 3</i>.</p>
<p>Simplemente abre el archivo en el editor de código y modifica esa línea.</p>';
}

$response = $fb->get('/me/accounts');

foreach ($response->getDecodedBody() as $allAccounts) {
    foreach ($allAccounts as $account ) { 
      if(isset($account['id'])){
        if ($account['id'] == $page_id) {
            $appAccessToken = $account['access_token'];
            echo '<p style="color:green;"><b>'.$account['name'].'</b> (<span style="color:#777;">$page_id='.$account['id'].';</span>)</p>'; 
        }else{
            echo '<p><b>'.$account['name'].'</b> (<span style="color:#777;">$page_id='.$account['id'].';</span>)</p>'; 
        }
      }
    }
}

//Publicar el super POST cuando haya una página seleccionada
if($page_id){

  try {


$usuario = "maymicov_cms";
$contrasena = "v16339V.";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
$servidor = "localhost";
$basededatos = "maymicov_cms";


$conexion = mysqli_connect( $servidor, $usuario, $contrasena );

  
$db = mysqli_select_db( $conexion, $basededatos );

$fecha = date('Y-m-j');
$nuevafecha = strtotime ( '-30 day' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );

$consulta="select * from hk_noticia  where date_add > '".$nuevafecha."' ORDER BY RAND() limit 6";
$consulta="select * from hk_noticia where estatus=1  ORDER BY RAND() limit 3";

//$resultado = mysqli_query( $conexion, $consulta );



while ($columna = mysqli_fetch_array( $resultado ))
{
 
   $url=$columna['titulo']." "."http://maymi.com.ve/blog/ver/id/".$columna['slug'] . "";

  //$params = array(
  //   "status" => $url
  //);


  $response = $fb->post(
          '/'.$page_id.'/feed',
          array(
              "message" => $columna['titulo'],
              "link" => "https://maymi.com.ve/blog/ver/id/".$columna['slug']
              //"picture" => $POST_IMAGEN,
              //"name" => $POST_NOMBRE,
              //"caption" => $POST_LEYENDA,
              //"description" => $POST_DESCRIPCION
          ),
          $appAccessToken
      );


}




      /*$response = $fb->post(
          '/'.$page_id.'/feed',
          array(
              "message" => $POST_TITULO,
              "link" => $POST_ENLACE
              //"picture" => $POST_IMAGEN,
              //"name" => $POST_NOMBRE,
              //"caption" => $POST_LEYENDA,
              //"description" => $POST_DESCRIPCION
          ),
          $appAccessToken
      );*/
      // Success
      $postId = $response->getGraphNode();
      echo '<p><b>Resultado:</b> Perfecto! Corre a mirar tu super página y verás que se ha publicado tu cosa.</p>';




  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo '<p>Error de Graph: ' . $e->getMessage().'</p>';
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo '<p>Error de Facebook SDK: ' . $e->getMessage().'</p>';
    exit;
  }

}

?>
</body>
</html>