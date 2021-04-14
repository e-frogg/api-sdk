<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 20:21
 */

namespace Apistarter\Sdk\Request;


use Apistarter\Sdk\Client\SdkRequestDecoratorTrait;
use Apistarter\Sdk\Exception\BadRequestException;
use Apistarter\Sdk\Exception\BadResponseException;
use Apistarter\Sdk\Exception\Custom\SdkCustomException;
use Apistarter\Sdk\Exception\Custom\ValidationException;
use Apistarter\Sdk\Exception\GuzzleException;
use Apistarter\Sdk\Exception\NotApiResponseException;
use Apistarter\Sdk\Exception\NotEncodableValueException;
use Apistarter\Sdk\Exception\NotFoundException;
use Apistarter\Sdk\Exception\NotNormalizableException;
use Apistarter\Sdk\Exception\RequestException;
use Apistarter\Sdk\Exception\SdkErrorData;
use Apistarter\Sdk\Exception\SdkException;
use Apistarter\Sdk\Exception\ServerException;
use Apistarter\Sdk\Exception\UnauthorizedException;
use Apistarter\Sdk\Exception\UnknownException;
use Apistarter\Sdk\Model\SdkModel;
use Apistarter\Sdk\Response\AbstractResponse;
use Apistarter\Sdk\Client\SdkClientInterface;
use Exception;

abstract class AbstractRequest extends SdkModel implements SdkRequestInterface
{
    use SdkRequestDecoratorTrait;

    // -------- debut a configurer -------------
    //    protected static $responseClass = MyResponse::class;
    //    protected static $endpoint='/xxx';
    //    protected static $queryParameters = [];
    //    protected static $uriVars = [];
    //    protected static $customExceptions;
    // -------- fin a configurer -------------

    /**
     * @var string
     * nom de la classe générée à partir de la réponse
     * ex : GetMarginProfileResponse / MarginProfile
     */
    protected static $responseClass = AbstractResponse::class;

    /**
     * route l'api
     * @var string
     *
     * ex :  /margin_profiles/{idMarginProfile}
     */
    protected static $endpoint = '';

    /**
     * paramètres qui seront remontés dans la query string
     * @var string[]
     *
     * ex :
     *     ['sort','q']
     *     => /margin_profiles/?sort=xxx&q=toto
     *
     */
    protected static $queryParameters = [];

    /**
     * variables remplacées dans l'url
     * @var string[]
     *
     * ex :
     *    ['id_margin_profile']
     *    /margin_profiles/{id_margin_profile}
     *
     */
    protected static $uriVars = [];

    /**
     * liste des classes d'exception custom
     * @var array
     */
    protected static $customExceptions = [];

    protected static $defaultCustomExceptions = [
        ValidationException::class,
    ];

    /**
     * Attention, request tout en snake case !!!
     * @var string
     */
    protected static $property_case = self::SNAKE_CASE;

    /**
     * non stricte sur les données contenues ici
     * @var bool
     */
    protected static $strict_property_case = false;

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /** @var string */
    protected static $method;

    /** @var string */
    protected static $format = 'json';

    /**
     * @return array
     */
    public static function getCustomExceptions(): array
    {
        return array_merge(static::$defaultCustomExceptions, static::$customExceptions);
    }


    /**
     * @return string
     */
    public function getFormat(): string
    {
        return static::$format;
    }


    public function getMethod()
    {
        return static::$method;
    }

    final public function getUrl()
    {
        $endpoint = str_replace(
            array_keys(static::$uriVars),
            array_map([$this, "__get"], array_values(static::$uriVars)),
            $this->getEndPoint()
        );

        $parameters = $this->getQueryParametersValues();
        if (!empty($parameters)) {
            return $endpoint . '?' . http_build_query($parameters);
        }
        return $endpoint;
    }

    public function getQueryParametersValues()
    {
        $params = $this->getQueryParameters();
        if (!empty($params)) {
            return array_filter(array_combine($params, array_map([$this, '__get'], $params)));
        }
        return [];
    }

    public function getEndPoint($decorated = true)
    {
        if (false === $decorated) {
            return static::$endpoint;
        }
        return $this->decorateEndpoint(static::$endpoint);
    }

    public function getResponseClass()
    {
        return static::$responseClass;
    }

    /**
     * @return string[]
     */
    public function getQueryParameterNames(){
        return static::$queryParameters;
    }

    public function getQueryParameters()
    {
        return $this->decorateQueryParameters(static::$queryParameters);
    }
    /** @noinspection PhpDocRedundantThrowsInspection */

    /**
     * @param SdkClientInterface $client
     * @return null|object
     * @throws SdkException
     * @throws BadRequestException
     * @throws BadResponseException
     * @throws GuzzleException
     * @throws NotApiResponseException
     * @throws NotEncodableValueException
     * @throws NotFoundException
     * @throws NotNormalizableException
     * @throws RequestException
     * @throws ServerException
     * @throws UnauthorizedException
     * @throws UnknownException
     */
    public function execute(SdkClientInterface $client)
    {
        return $client->execute($this);
    }

    /**
     * @param SdkErrorData $sdkErrorData
     * @param Exception|null $previous
     * @throws SdkCustomException
     */
    public function dispatchCustomException(SdkErrorData $sdkErrorData, Exception $previous = null)
    {
        foreach (static::getCustomExceptions() as $exceptionClass) {
            if ($sdkErrorData->uuid === constant($exceptionClass . '::uuid')) {
                /** @var SdkCustomException $exception */
                $exception = new $exceptionClass($sdkErrorData->message, $sdkErrorData->code_error, $previous);
                // forward les infos de l'exceptoin précédente
                if ($previous instanceof SdkException) {
                    if ($previous->hasRequest()) {
                        $exception->setRequest($previous->getRequest());
                    }
                    if ($previous->hasResponse()) {
                        $exception->setResponse($previous->getResponse());
                    }
                    if ($previous->hasResponseBody()) {
                        $exception->setResponseBody($previous->getResponseBody());
                    }
                    if ($previous->hasResponseData()) {
                        $exception->setResponseData($previous->getResponseData());
                    }
                }
                throw $exception;
            }
        }
    }

    public function decorateEndpoint(string $endpoint)
    {
        foreach ($this->requestDecorators as $decorator) {
            $endpoint = $decorator->decorateEndpoint($endpoint, $this);
        }
        return $endpoint;
    }

    private function decorateQueryParameters(array $queryParameters)
    {
        foreach ($this->requestDecorators as $decorator) {
            $queryParameters = $decorator->decorateQueryParameters($queryParameters, $this);
        }
        return $queryParameters;
    }

}