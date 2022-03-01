<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

//Donde coño estamos?
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off'){
$protocolo = 'http://';
}else{
$protocolo = 'https://';
}
$super_url = $protocolo . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

//Incluir datos secretos mágicos
include('./super-configuracion.php');

$fb = new Facebook\Facebook([
  'app_id' => $APP_ID,
  'app_secret' => $APP_SECRET,
  'default_graph_version' => $GRAPH_VERSION,
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'publish_actions', 'manage_pages', 'publish_pages'];
$loginUrl = $helper->getLoginUrl($super_url . '/facebook-connect-callback.php', $permissions);

//Hay que conectarse o no?
if(isset($_SESSION['facebook_access_token'])){
	echo '
	<p><b>Super Facebook</b> ya está <span style="color:green">conectado</span>.<p>
	<p><b>Super nota:</b> Recuerda que esta token <i>(autorización de acceso)</i> está guardada en una $_SESSION. Cuando termines la sesión se habrá perdido y tendrás que buscar otra. En tu propósito del día a día, lo normal es que decidas guardar esta token en otro sitio. Una base de datos o un fichero de texto, por ejemplo.</p>
	<p><b>Token actual:</b> <i>'.$_SESSION['facebook_access_token'].'</i></p>
	<h1>Ejemplos prefabricados</h1>
	<h2>Super Publicar en Página</h2>
	<p>Demo para que publiques en las páginas que administras. Esto te puede ser útil para crear un programa automatizado que publique tus asuntos en facebook sin tener que estar dándole al dedo todo el día.</p>
	<p><b>Ver:</b> <a href="./publicar-en-pagina.php">Super Publicar en Página</a>.</p>
	';
}else{
	echo '<a href="' . $loginUrl . '">Conectar con mi super Facebook</a>';
}

?>