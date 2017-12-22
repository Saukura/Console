#!/usr/bin/env php
<?php

require "vendor/autoload.php";

/**
 * To find out are you in CLI or not
 */
if (PHP_SAPI != "cli") {
    exit;
}

/**
 *
 */
(new \Command\Console())->run();