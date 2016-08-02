<?php
/**
 * Session Component Config
 *
 * Session Component Configuration file
 *
 * @package   SlaxWeb\Session
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.4
 */
/*
 * Session storage handler
 *
 * Available options are:
 * - native (default)
 * - memcache
 * - memcached
 * - mongo
 * - database
 * - null
 */
$configuration["storageHandler"] = "native";
