<?php
namespace SlaxWeb\Session\Storage\Memcached;

/**
 * Memcached session storage handling class.
 * Enables retrieving data from the Memcached session storage, writing data to it etc.
 * Uses the \Session\Storage\iStorage interface
 * Copyright (c) 2015 Tomaz Lovrec (tomaz.lovrec@gmail.com)
 *
 * @author Tomaz Lovrec <tomaz.lovrec@gmail.com>
 */
class Memcached extends \SlaxWeb\Session\Storage\PhpStorage\PhpStorage implements \SlaxWeb\Session\Storage\iStorage
{
    protected $_variables = array();

    /**
     * Default class constructor
     */
    public function __construct($host = "127.0.0.1", $port = "11211")
    {
        ini_set("session.save_handler", "memcached");
        ini_set("session.save_path", "{$host}:{$port}");

        parent::__construct();
    }
}
