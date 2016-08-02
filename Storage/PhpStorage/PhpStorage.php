<?php
namespace SlaxWeb\Session\Storage\PhpStorage;

/**
 * Default PHP storage handling class.
 * Enables retrieving data from the PHP session storage, writing data to it, etc.
 * Uses the \Session\Storage\iStorage interface
 * Copyright (c) 2013 Tomaz Lovrec (tomaz.lovrec@gmail.com)
 *
 * @author Tomaz Lovrec <tomaz.lovrec@gmail.com>
 */
class PhpStorage implements \SlaxWeb\Session\Storage\iStorage
{
    protected $_variables = array();
    protected $_config = array();

    /**
     * Default class constructor
     */
    public function __construct($config)
    {
        // Set the config
        $this->_config = $config;
        // Init the session
        $this->_init();
        // copy whole session to a local property
        $this->_getVariables();
    }

    /**
     * Get the session variable.
     *
     * @param $name mixed Name of the session variable or array of session variables
     * @return mixed Returns the value of a session variable, or false if it was not found
     *
     * TODO:
     * - serialize if serializable
     */
    public function getVariable($name)
    {
        if (is_array($name) === true) {
            $data = array();
            foreach ($name as $n) {
                $data[$n] = isset($this->_variables[$n]) ? $this->_variables[$n] : false;
            }
            return $data;
        }
        return isset($this->_variables[$name]) ? $this->_variables[$name] : false;
    }

    /**
     * Get all session variables.
     *
     * @return array Returns all session variables as an array
     */
    public function getAllVariables()
    {
        return $this->_variables;
    }

    /**
     * Set session variable
     *
     * @param $name string Name of the session variable
     * @param $value mixed Value of the session variable
     *
     * TODO:
     * - unserialize if it's srialized
     */
    public function setVariable($name, $value)
    {
        // set the local property
        $this->_variables[$name] = $value;
        // also set the superglobal for persistance across refreshes
        $_SESSION[$name] = $value;
    }

    /**
     * Set multiple session variables
     *
     * @param $variables array Array of the session variables that need to be set
     * @param bool Returns false if par
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $name => $value) {
            $this->setVariable($name, $value);
        }
    }

    /**
     * Remove session variable
     *
     * @param $name string Name of the session variable
     */
    public function removeVariable($name)
    {
        // remove from property
        unset($this->_variables[$name]);
        // also remove from superglobal
        unset($_SESSION[$name]);
    }

    /**
     * Remove all session variables
     */
    public function removeAllVariables()
    {
        // remove from property
        $this->_variables = array ();
        // also remove from superglobal
        $_SESSION = array ();
    }

    /**
     * Destroys the session and removes all variables
     */
    public function destroySession()
    {
        // remove the session variables from the local property
        $this->_variables = array ();
        session_destroy();
    }

    /**
     * Refill session data
     *
     * After session ID regeneration, the data is cleared out, it needs to be re-set
     */
    public function refillSession()
    {
        $_SESSION = $this->_variables;
    }

    /**
     * Initialize session
     *
     * Set PHP session settings, and start the session
     */
    protected function _init()
    {
        // Set session entropy file
        $entropyFile = "/dev/urandom";
        if (isset($this->_config["session.entropy_file"])) {
            $entropyFile = $this->_config["session.entropy_file"];
        } elseif (file_exists("/dev/arandom")) {
            $entropyFile = "/dev/arandom";
        }
        ini_set("session.entropy_file", $entropyFile);

        // Set session entropy length
        $entropyLength = isset($this->_config["session.entropy_length"])
            ? $this->_config["session.entropy_length"]
            : 2048;
        ini_set("session.entropy_length", $entropyLength);

        // Set session hash function
        $availAlgos = \hash_algos();
        $hashAlgo = "0";
        if (isset($this->_config["session.hash_function"])) {
            $hashAlgo = $this->_config["session.hash_function"];
        } elseif (in_array("sha512", $availAlgos)) {
            $hashAlgo = "sha512";   
        } elseif (in_array("sha1", $availAlgos)) {
            $hashAlgo = "sha1";   
        }
        ini_set("session.hash_function", $hashAlgo);

        // Set session cookie http only
        ini_set(
            "session.cookie_httponly",
            isset($this->_config["session.cookie_httponly"])
                ? $this->_config["session.cookie_httponly"]
                : 1
        );

        // Initiate the session
        session_start();
    }

    /**
     * Copies all session variables to $this->_variables
     */
    protected function _getVariables()
    {
        $this->_variables = $_SESSION;
    }
}

/**
 * End of file ./SlaxWeb/Session/PhpStorage/PhpStorage.php
 */
