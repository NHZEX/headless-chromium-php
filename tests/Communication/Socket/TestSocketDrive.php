<?php

namespace HeadlessChromium\Test\Communication\Socket;

use HeadlessChromium\Communication\Socket\MockSocket;
use HeadlessChromium\Communication\Socket\SocketInterface;
use HeadlessChromium\Communication\SocketCreateInterface;
use Psr\Log\LoggerInterface;

class TestSocketDrive extends MockSocket implements SocketCreateInterface
{
    public static function create(string $url, LoggerInterface $logger = null): SocketInterface
    {
        return new self();
    }
}
