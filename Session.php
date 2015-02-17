<?php
namespace SlaxWeb\Session;

// Start session
session_start();

/**
 * Session class that handles retrieving/storing session data.
 * Uses a sub-class to use as storage. Available storage methods are:
 * - normal PHP session storage
 * - database storage (to be done)
 * - memcached (to be done)
<<<<<<< HEAD
 * Copyright (c) 2013 Tomaz Lovrec (tomaz.lovrec@gmail.com)
=======
>>>>>>> 1ab7c95972f317968d078ec3f6827aa1e9e183ac
 *
 * @author Tomaz Lovrec <tomaz.lovrec@gmail.com>
 */
class Session
{
    /**
     * Session storage object
     *
     * @var object
     */
    protected $_storage = null;
    /**
     * Session id
     *
     * @var string
     */
    protected $_sessionId = '';
    /**
     * Expire time in seconds
     *
     * @var integer
     */
    protected $_expire = null;

    const SESSION_STORAGE_PHP       =   1;
    const SESSION_STORAGE_DB        =   2;
    const SESSION_STORAGE_MEMCACHED =   3;

    /**
     * Storage constants
     */
    const SESSION_STORAGE_PHP       =   1;
    const SESSION_STORAGE_DB        =   2;
    const SESSION_STORAGE_MEMCACHED =   3;


    /**
     * Default class constructor
     *
     * Sets the storage, checks for hijacks, and regenerates the session ID.
     *
     * @param $storage int Type of storage to use.
     *                      Default value: SESSION_STORAGE_PHP
     */
    public function __construct($storage = self::SESSION_STORAGE_PHP, $expire = 1800)
    {
        $this->_expire = $expire;
        // set the storage
        $this->setStorage($storage, false);
        // do some session checks
        $this->_checkSession();
        // regenerate and set new session ID
        $this->_setSessionId(false);
    }

    /**
     * Set the session storage
     *
     * Sets session storage and copies over all values from old storage if 
     * $copy is set to true.
     *
     * @param $storage int Session storage to set
     * @param $copy bool Copy the session values from old storage to new.
     *                      Default value: false
     */
    public function setStorage($storage, $copy = false)
    {
        // check if the variables should be copied
        $oldValues = null;
        if ($copy === true && $this->_storage !== null) {
            $oldValues = $this->_storage->getAllVariables();
        }

        // set the storage
        switch ($storage) {
            case self::SESSION_STORAGE_PHP:
                // set the PHP storage
                $this->_storage = new Storage\PhpStorage\PhpStorage();
                break;
            case self::SESSION_STORAGE_DB:
                $this->_storage = new Storage\DbStorage\DbStorage();
                break;
            case self::SESSION_STORAGE_MEMCACHED:
                $this->_storage = new Storage\Memcached\Memcached();
                break;
        }

        // if values should be copied, do it now
        if ($copy === true && $this->_storage !== null) {
            $this->_storage->setVariables($oldValues);
        }
    }

    /**
     * Gets the session varibale
     *
     * @param $name mixed Name of the session variable, if set to bool true,
     *                      returns all session values.
     *                      Default value: true
     * @return mixed Returns the session variable value
     */
    public function get($name = true)
    {
        if ($name === true) {
            // all session data is needed
            return $this->_storage->getAllVariables();
        }
        return $this->_storage->getVariable($name);
    }

    /**
     * Gets all the session variables
     *
     * This is an alias for method Session::getSession(true)
     *
     * @return array Returns the array containing all session variables
     */
    public function getAll()
    {
        return $this->_storage->getAllVariables();
    }

    /**
     * Set session variable
     *
     * @param $name mixed May be the name of the session variable to set,
     *                      or an array with key/value pairs
     * @param $value mixed If $name is a string, $value must be set to the value
     *                      that needs to be set. Default value: null
     */
    public function set($name, $value = null)
    {
        if (is_array($name) === true) {
            $this->_storage->setVariables($name);
        } else {
            $this->_storage->setVariable($name, $value);
        }
    }

    /**
     * Unset session variable
     *
     * @param $name mixed May be the name of the session variable to unset,
     *                      or an array of session names to unset.
     */
    public function remove($name)
    {
        if (is_array($name) === true) {
            foreach ($name as $n) {
                $this->_storage->removeVariable($n);
            }
        } else {
            $this->_storage->removeVariable($name);
        }
    }

    /**
     * Destroy session
     */
    public function destroy()
    {
        $this->_storage->destroySession();
    }

    /**
     * Regenerate session ID
     */
    public function regenerateId()
    {
        session_regenerate_id(true);
        // session data has been removed, refill it,
        // if PHP session storage is used
        if (method_exists($this->_storage, 'refillSession') === true) {
            $this->_storage->refillSession();
        }
        $this->_sessionId = session_id();
    }

    /**
     * Set session ID
     *
     * Sets the session ID to class property, and regenerates it on request.
     *
     * @param $regenerate bool Regenerate the session ID before setting it.
     */
    protected function _setSessionId($regenerate = false)
    {
        if ($regenerate === true) {
            // regenerate the session ID
            session_regenerate_id(true);
            // session data has been removed, refill it,
            // if PHP session storage is used
            if (method_exists($this->_storage, 'refillSession') === true) {
                $this->_storage->refillSession();
            }
        }
        // set the new session ID
        $this->_sessionId = session_id();
    }

    /**
     * Checks for the possibility of a hijack
     *
     * Checks the user agent and time of the session, if not set, it sets it.
     * On a failed check the session is immediately destroyed.
     * TODO:
     * - needs more aggresive anti-hijack checks
     */
    protected function _checkSession()
    {
        $userAgent = $this->_storage->getVariable('UserAgent');
        $lastTime = $this->_storage->getVariable('LastActiveTime');
        // check if the user agent has been set
        if ($userAgent !== false && $lastTime !== false) {
            // user agent is set, let's do the checks
            if ($userAgent === $_SERVER['HTTP_USER_AGENT'] && (time() - $lastTime > $this->_expire) === false) {
                // everything is fine, move on to the next check
            } else {
                // user agent is not ok, or session has expired -> destroy the session immediately
                $this->_storage->destroySession();
            }
        } else {
            // user agent has not been set, set it now
            $this->_storage->setVariable('UserAgent', $_SERVER['HTTP_USER_AGENT']);
        }
        $this->_storage->setVariable('LastActiveTime', time());
    }
}
