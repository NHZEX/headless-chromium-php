<?php

namespace HeadlessChromium\Communication;

use HeadlessChromium\Communication\Socket\SocketInterface;
use Psr\Log\LoggerInterface;

interface SocketCreateInterface
{
    /**
     * @param string               $url
     * @param LoggerInterface|null $logger
     * @return SocketInterface
     */
    public static function create(string $url, LoggerInterface $logger = null): SocketInterface;
}
