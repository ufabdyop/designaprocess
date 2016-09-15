<?php
/**
 * Plugin Name: Nanofab SSO
 * Plugin URI:  http://www.nanofab.utah.edu/nanofab-sso
 * Description: Allows centralized auth in nanofab
 * Version:     1.0.0
 * Author:      Ryan Taylor
 *
 * This let's us use existing login infrastructure for word press.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not,
 * write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   Nanofab SSO
 * @version   1.0.0
 * @author    Ryan Taylor
 * @copyright Copyright (c) 2016
 * @link      http://www.nanofab.utah.edu/nanofab-sso
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

$functions_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/functions.php');
require_once($functions_path);


