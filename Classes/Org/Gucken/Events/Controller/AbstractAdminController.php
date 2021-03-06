<?php
namespace Org\Gucken\Events\Controller;

/*                                                                        *
 * This script belongs to the Flow package "Org.Gucken.Events".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Standard controller for the Events package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
abstract class AbstractAdminController extends BaseController
{
    /**
     * @param string      $actionName
     * @param string|null $controllerName
     * @param string|null $packageKey
     * @param array|null  $arguments
     * @param int         $delay
     * @param int         $statusCode
     * @param string|null $format
     */
    public function redirectIfHtml(
        $actionName,
        $controllerName = null,
        $packageKey = null,
        array $arguments = null,
        $delay = 0,
        $statusCode = 303,
        $format = null
    ) {
        if ($this->isHtmlRequest()) {
            $this->redirect($actionName, $controllerName, $packageKey, $arguments, $delay, $statusCode, $format);
        }
    }
}
