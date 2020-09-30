<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TiempoExtension extends AbstractExtension
{
    const CONFIG = [
      'formato' => 'd/m/Y H:m:s'
    ];
    public function getFilters()
    {
        return [new TwigFilter('tiempo', [$this, 'formatearTiempo'])];
    }

    public function formatearTiempo($fecha, $config = []){
      $config = array_merge(self::CONFIG, $config);
      $fechaActual = new \DateTime();
      $fechaFormateada = $fecha->format($config['formato']);

      $secDiff = $fechaActual->getTimestamp() - $fecha->getTimestamp();

      if($secDiff < 60){
        $fechaFormateada = 'Creado hace unos segundos';
      } elseif ($secDiff < 3600) {
        $fechaFormateada = 'Creado hace '.round($secDiff / 60)." minutos";
      } elseif ($secDiff > 3600 && $secDiff < 86400) {
        $fechaFormateada = 'Creado hace '.round($secDiff / 3600)." horas";
      } elseif ($secDiff > 86400) {
        $fechaFormateada = 'Creado hace '.round($secDiff / 86400)." días";
      }

      return $fechaFormateada;
    }
}
