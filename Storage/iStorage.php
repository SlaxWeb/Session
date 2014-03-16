<?php
namespace SlaxWeb\Session\Storage;

/**
 * Session interface, that all storage classes must implement.
 * Copyright (c) 2013 Tomaz Lovrec (tomaz.lovrec@gmail.com)
 *
 * This file is part of "SlaxWeb Framework".
 *
 * "SlaxWeb Framework" is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Foobar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Tomaz Lovrec <tomaz.lovrec@gmail.com>
 */
interface iStorage
{
    /**
     * Get the session variable.
     *
     * @param $name string Name of the session variable
     * @return mixed Returns the value of a session variable
     */
    public function getVariable($name);

    /**
     * Get all session variables.
     *
     * @return array Returns all session variables as an array
     */
    public function getAllVariables();

    /**
     * Set session variable
     *
     * @param $name string Name of the session variable
     * @param $value mixed Value of the session variable
     */
    public function setVariable($name, $value);

    /**
     * Set multiple session variables
     *
     * @param $variables array Array of the session variables that need to be set
     */
    public function setVariables(array $variables);

    /**
     * Remove session variable
     *
     * @param $name string Name of the session variable
     */
    public function removeVariable($name);

    /**
     * Remove all session variables
     */
    public function removeAllVariables();

    /**
     * Destroys the session and removes all variables
     */
    public function destroySession();
}
