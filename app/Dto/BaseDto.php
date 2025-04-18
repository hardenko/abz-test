<?php

namespace App\Dto;

abstract class BaseDto
{
    abstract public static function fromArray(array $params): self;
}
