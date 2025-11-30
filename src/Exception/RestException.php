<?php

namespace Argo\RestClient\Exception;

use Psr\Http\Message\ResponseInterface;

/**
 * @api
 */
class RestException extends \RuntimeException
{
    public function __construct(
        private readonly ResponseInterface $response,
        ?\Throwable $previous = null,
    ) {
        $response->getReasonPhrase();

        parent::__construct($response->getReasonPhrase(), $this->response->getStatusCode(), $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
