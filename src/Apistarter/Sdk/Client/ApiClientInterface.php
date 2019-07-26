<?php


namespace Apistarter\Sdk\Client;


use Exception;
use Symfony\Component\HttpFoundation\Response;

interface ApiClientInterface
{
    /**
     * @param string $method
     * @param $url
     * @param array|null $data
     * @return Response
     * @throws Exception
     */
    public function call(string $method, $url, array $data = null): Response;

    /**
     * @param string $url
     * @return Response
     * @throws Exception
     */
    public function get(string $url);

    /**
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exception
     */
    public function post(string $url, array $data);

}