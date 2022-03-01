<?php
session_start();

require_once __DIR__ . '/facebook-sdk-v5/autoload.php';

//Incluir datos secretos mágicos
include('./super-configuracion.php');

$fb = new Facebook\Facebook([
  'app_id' => $APP_ID,
  'app_secret' => $APP_SECRET,
  'default_graph_version' => $GRAPH_VERSION,
]);

$helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {

  // Esta token está muy bien, pero es de corta duración
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Gestor de autorizaciones super inteligente
  $oAuth2Client = $fb->getOAuth2Client();

  // Intercambiazo para obtener una token de larga duración
  $super_token = (string) $oAuth2Client->getLongLivedAccessToken( $_SESSION['facebook_access_token'] );
  $_SESSION['facebook_access_token'] = $super_token;

  header("Location: ./index.php");

}

?>