<?php

declare( strict_types = 1 );

namespace Ocolin\Calix\Axos\Tests;

use PHPUnit\Framework\TestCase;
use Ocolin\Calix\Axos\Client;

class TestTest extends TestCase
{
    public static Client $api;

    public static function setUpBeforeClass(): void
    {
        self::$api = new Client();
    }
}