<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */

$dir = dirname(dirname(__DIR__));
//echo shell_exec("XDEBUG_CONFIG=\"remote_enable=0\" php -dxdebug.remote_enable=0 {$dir}/artisan migrate:refresh");

require "{$dir}/bootstrap/autoload.php";