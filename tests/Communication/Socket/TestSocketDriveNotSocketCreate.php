<?php

namespace HeadlessChromium\Test\Communication\Socket;

use HeadlessChromium\Communication\Socket\MockSocket;
use HeadlessChromium\Communication\Socket\SocketInterface;
use HeadlessChromium\Communication\SocketCreateInterface;
use Psr\Log\LoggerInterface;

class TestSocketDriveNotSocketCreate implements SocketCreateInterface
{

    public static function create(string $url, LoggerInterface $logger = null): SocketInterface
    {
        return new class extends MockSocket
        {
        };
    }
}
