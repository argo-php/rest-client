<?php

namespace Argo\RestClient\Serializer;

use Argo\Serializer\Contract\DecoderInterface;
use Argo\Serializer\Contract\DenormalizerInterface;
use Argo\Serializer\Contract\EncoderInterface;
use Argo\Serializer\Contract\NormalizerInterface;
use Argo\Serializer\Contract\SerializerInterface;

interface RestClientSerializerInterface extends SerializerInterface, NormalizerInterface, DenormalizerInterface,
    EncoderInterface, DecoderInterface {}
