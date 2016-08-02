<?php
/**
 * Missing Dependency Exception
 *
 * Thrown when the session library is being instantiated, but the required dependency
 * service is not available in the DIC.
 *
 * @package   SlaxWeb\Session
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.4
 */
namespace SlaxWeb\Session\Exception;

class MissingDependencyException extends \Exception
{
}
