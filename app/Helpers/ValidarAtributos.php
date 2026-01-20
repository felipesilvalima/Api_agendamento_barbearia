<?php declare(strict_types=1); 

namespace App\Helpers;

class ValidarAtributos
{
    public  static function validarAtributos(?string $atributos_soliciatados, array $atributos_permitidos)
    {
        if($atributos_soliciatados != null){
            
            $atributos_soliciatados = explode(',',$atributos_soliciatados);

            foreach($atributos_soliciatados as $atributo)
            {
                if(!in_array($atributo, $atributos_permitidos))
                {
                    abort(422, 'O Atributo '. $atributo. ' Não é permitido');
                }
            }
            
            $atributos_validados = implode(',',$atributos_soliciatados);
            return $atributos_validados;

        }
        
    }

    public  static function validarAtributosCondicao(?string $atributos_soliciatados, array $atributos_permitidos)
    {
        if($atributos_soliciatados != null){
            
            $atributos_soliciatados = explode(";",$atributos_soliciatados);

            foreach($atributos_soliciatados as $atributo)
            {
                $atributo = explode(':',$atributo);

                if($atributo[0] !== null)
                {
                    if(!in_array($atributo[0], $atributos_permitidos))
                    {
                        abort(422, 'O Atributo '. $atributo[0]. ' Não é permitido');
                    }
                }
                    else
                    {
                        break;
                    }
                

            }
    
            return $atributos_soliciatados;

        }
        
        
        
    }
}