<?php
/**
 * Session Library Service Provider
 *
 * Registers the Library to the DIC as a service
 *
 * @package   SlaxWeb\Session
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.4
 */
namespace SlaxWeb\Session\Service;

use Pimple\Container;
use SlaxWeb\Session\Exception\MissingDependencyException;

class Provider implements \Pimple\ServiceProviderInterface
{
    /**
     * Register Provider
     *
     * Called when the container is about to register this provider with the DIC.
     * It should define all the services, or call other methods that define the
     * services.
     *
     * @param \Pimple\Container $container Dependency Injection Container
     * @return void
     */
    public function register(Container $container)
    {
        $container["session.service"] = function (Container $container) {

            $session = new \Symfony\Component\HttpFoundation\Session\Session($container["sessionStorage.service"]);
            $session->start();
            return $session;
        };

        $container["sessionStorage.service"] = function (Container $container) {
            return new \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage(
                $container["config.service"]["session.options"],
                $container["storageHandler.service"]
            );
        };

        $container["storageHandler.service"] = function (Container $container) {
            $storageHandler = null;
            $handler = $container["config.service"]["session.storageHandler"];
            $hNamespace = "\\Symfony\\Component\\HttpFoundation\\Session\\Storage\\Handler\\";
            switch ($handler) {
                case "native":
                case "null":
                    $handler = ucfirst($handler);
                    $handlerClass = "{$hNamespace}{$handler}SessionHandler";
                    $storageHandler = new $handlerClass;
                    break;
                case "memcache":
                case "memcached":
                case "mongo":
                    if (isset($container["{$handler}.service"]) === false) {
                        throw new MissingDependencyException(
                            "Required '{$handler}.service' is not registered with the DIC"
                        );
                    }
                    $handler = ucfirst($handler);
                    $handlerClass = "{$hNamespace}{$handler}SessionHandler";
                    $storageHandler = new $handlerClass($container["{$handler}.service"]);
                    break;
                case "database":
                    $handlerClass = "{$hNamespace}PdoSessionHandler";

                    if (isset($container["pdo.service"])) {
                        $storageHandler = new $handlerClass($container["pdo.service"]);
                    } elseif (isset($container["config.service"]["database.connection"])) {
                        $config = $container["config.service"]["database.connection"];

                        $dsn = "{$config["driver"]}:dbname={$config["database"]};host={$config["hostname"]}";
                        $storageHandler = new $handlerClass(
                            $dsn,
                            ["db_username" => $config["username"], "db_password" => $config["password"]]
                        );
                    } else {
                        throw new MissingDependencyException(
                            "Install the database component to store session data in the database."
                        );
                    }
                    break;
                default:
                    break;
            }

            return $storageHandler;
        };
    }
}
