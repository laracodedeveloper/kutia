<?php
/**
 * File helpers.php.
 * @copyright 2020
 * @version 1.0
 */

namespace Kutia;

/**
 * Get the user
 */
function user()
{
    if (! isset($_SERVER['SUDO_USER'])) {
        return $_SERVER['USER'];
    }

    return $_SERVER['SUDO_USER'];
}
