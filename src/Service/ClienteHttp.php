<?php

namespace App\Service;

use App\Repository\CategoriaRepository;
use Exception;
use Symfony\Component\HttpClient\HttpClient;


// Esta clase obtendrá el código de respuesta para una URL con tal de hacer una 
// validación propia
class ClienteHttp {

  private $clienteHttp;
  public function __construct()
  {
    $this->clienteHttp = HttpClient::create();
  }

  public function getResponseCode(string $url){
    $resCode = null;

    try {
      $res = $this->clienteHttp->request('GET', $url);
      $resCode = $res->getStatusCode();
    } catch(Exception $e){
      $resCode = null;
    }    

    return $resCode;
  }
}