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

/*
 * Session configuration
 *
 * @see http://php.net/session.configuration for options
 *
 * "session." part is ommited for convenience.
 */
$configuration["options"] = [
    "cache_limiter"             =>  "", // use "0" to prevent headers from being sent entirely
    "cookie_domain"             =>  "",
    "cookie_httponly"           =>  "",
    "cookie_lifetime"           =>  "0",
    "cookie_path"               =>  "/",
    "cookie_secure"             =>  "",
    "entropy_file"              =>  "",
    "entropy_length"            =>  "0",
    "gc_divisor"                =>  "100",
    "gc_maxlifetime"            =>  "1440",
    "gc_probability"            =>  "1",
    "hash_bits_per_character"   =>  "4",
    "hash_function"             =>  "0",
    "name"                      =>  "PHPSESSID",
    "referer_check"             =>  "",
    "serialize_handler"         =>  "php",
    "use_cookies"               =>  "1",
    "use_only_cookies"          =>  "1",
    "use_trans_sid"             =>  "0",
    "upload_progress.enabled"   =>  "1",
    "upload_progress.cleanup"   =>  "1",
    "upload_progress.prefix"    =>  "upload_progress_",
    "upload_progress.name"      =>  "PHP_SESSION_UPLOAD_PROGRESS",
    "upload_progress.freq"      =>  "1%",
    "upload_progress.min-freq"  =>  "1",
    "url_rewriter.tags"         =>  "a=href,area=href,frame=src,form=,fieldset="
];
