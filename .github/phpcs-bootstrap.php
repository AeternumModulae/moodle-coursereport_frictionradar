<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * PHPCS bootstrap helpers for local tooling.
 *
 * @package    coursereport_frictionradar
 * @copyright  2026 Jan Svoboda <jan.svoboda@bittra.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Load Composer autoload so external standards can be resolved.
$composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}

// Backward-compat shim for older Moodle sniffs that reference this class name.
if (!class_exists('PHP_CodeSniffer_Exception')) {
    if (class_exists('PHP_CodeSniffer\\Exceptions\\RuntimeException')) {
        class PHP_CodeSniffer_Exception extends PHP_CodeSniffer\Exceptions\RuntimeException
        {
        }
    } else {
        class PHP_CodeSniffer_Exception extends Exception
        {
        }
    }
}

if (!class_exists('PHP_CodeSniffer_File')) {
    spl_autoload_register(function ($class) {
        if ($class !== 'PHP_CodeSniffer_File') {
            return;
        }

        class_alias('PHP_CodeSniffer\\Files\\File', 'PHP_CodeSniffer_File');
    });
}

if (!class_exists('PHP_CodeSniffer_Tokens')) {
    spl_autoload_register(function ($class) {
        if ($class !== 'PHP_CodeSniffer_Tokens') {
            return;
        }

        class_alias('PHP_CodeSniffer\\Util\\Tokens', 'PHP_CodeSniffer_Tokens');
    });
}

if (!class_exists('PHP_CodeSniffer')) {
    class PHP_CodeSniffer
    {
        public static function getConfigData($key)
        {
            if (class_exists('PHP_CodeSniffer\\Config') && method_exists('PHP_CodeSniffer\\Config', 'getConfigData')) {
                return PHP_CodeSniffer\Config::getConfigData($key);
            }

            return null;
        }
    }
}

// Legacy Moodle sniffs expect these class names from PHPCS 2.x.
if (!class_exists('PHP_CodeSniffer_Standards_AbstractPatternSniff')) {
    spl_autoload_register(function ($class) {
        if ($class !== 'PHP_CodeSniffer_Standards_AbstractPatternSniff') {
            return;
        }

        class_alias('PHP_CodeSniffer\\Sniffs\\AbstractPatternSniff', $class);
    });
}

if (!class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff')) {
    spl_autoload_register(function ($class) {
        if ($class !== 'PHP_CodeSniffer_Standards_AbstractScopeSniff') {
            return;
        }

        class_alias('PHP_CodeSniffer\\Sniffs\\AbstractScopeSniff', $class);
    });
}

if (!interface_exists('PHP_CodeSniffer_Sniff')) {
    if (interface_exists('PHP_CodeSniffer\\Sniffs\\Sniff')) {
        interface PHP_CodeSniffer_Sniff extends PHP_CodeSniffer\Sniffs\Sniff
        {
        }
    } else {
        interface PHP_CodeSniffer_Sniff
        {
        }
    }
}

if (!class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff')) {
    spl_autoload_register(function ($class) {
        if ($class !== 'PHP_CodeSniffer_Standards_AbstractVariableSniff') {
            return;
        }

        class_alias('PHP_CodeSniffer\\Sniffs\\AbstractVariableSniff', $class);
    });
}

if (!class_exists('PHPCompatibility_Sniff')) {
    $phpcompatSniff = dirname(__DIR__) . '/vendor/phpcompatibility/php-compatibility/PHPCompatibility/Sniff.php';
    if (file_exists($phpcompatSniff)) {
        require_once $phpcompatSniff;
    }

    if (!class_exists('PHPCompatibility_Sniff') && class_exists('PHPCompatibility\\Sniffs\\Sniff')) {
        class_alias('PHPCompatibility\\Sniffs\\Sniff', 'PHPCompatibility_Sniff');
    }
}

// Map legacy PHPCompatibility class names to the namespaced equivalents.
spl_autoload_register(function ($class) {
    $prefix = 'PHPCompatibility_Sniffs_';
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $suffix = substr($class, strlen($prefix));
    $parts = explode('_', $suffix);
    if (count($parts) < 2) {
        return;
    }

    $standard = array_shift($parts);
    $sniff = implode('_', $parts);
    $target = "PHPCompatibility\\Sniffs\\{$standard}\\{$sniff}";
    if (class_exists($target)) {
        class_alias($target, $class);
    }
});

// Map legacy "<Standard>_Sniffs_*" class names to namespaced sniffs.
spl_autoload_register(function ($class) {
    if (strpos($class, '_Sniffs_') === false) {
        return;
    }

    $parts = explode('_Sniffs_', $class, 2);
    if (count($parts) !== 2) {
        return;
    }

    $standard = $parts[0];
    $rest = $parts[1];
    $segments = explode('_', $rest);
    if (count($segments) < 2) {
        return;
    }

    $category = array_shift($segments);
    $sniff = implode('_', $segments);
    $targets = [
        'PHP_CodeSniffer\\Standards\\' . $standard . '\\Sniffs\\' . $category . '\\' . $sniff,
        $standard . '\\Sniffs\\' . $category . '\\' . $sniff,
    ];

    foreach ($targets as $target) {
        if (class_exists($target)) {
            class_alias($target, $class);
            return;
        }
    }
});
