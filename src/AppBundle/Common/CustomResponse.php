<?php
/**
 * Created by PhpStorm.
 * User: Donevil
 * Date: 14.11.2018.
 * Time: 7:38
 */

namespace AppBundle\Common;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomResponse extends JsonResponse
{
    public function __construct($data = null, $success = true)
    {
        $headers = array(
            'Access-Control-Allow-Origin' => 'http://localhost:8080',
            "Access-Control-Allow-Credentials" => "true",
            "Access-Control-Allow-Methods" => "GET,HEAD,OPTIONS,POST,PUT",
            "Access-Control-Allow-Headers"=> "Access-Control-Allow-Headers, Origin, Authorization, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers"
        );

        parent::__construct($data, $success == true ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR, $headers, false);
    }
}