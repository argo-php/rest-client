<?php

namespace Argo\RestClient\Attributes;

/**
 * @api
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final readonly class Delete extends Route
{
    public function __construct(string $path, ?string $body = null)
    {
        parent::__construct('DELETE', $path, $body);
    }
}
