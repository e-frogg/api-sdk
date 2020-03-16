<?php


namespace Apistarter\Sdk\Client;


use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait ApiClientTrait
{


    /**
     * @param string $url
     * @return Response
     * @throws Exception
     */
    public function get($url)
    {
        return $this->call(Request::METHOD_GET, $url);
    }

    /**
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exception
     */
    public function post($url, array $data)
    {
        return $this->call(Request::METHOD_POST, $url, $data);
    }

}