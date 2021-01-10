<?php

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Test\Communication\Socket\Textalk;

require __DIR__ . '/../vendor/autoload.php';

BrowserFactory::setDefaultSocketDrive(Textalk::class);
