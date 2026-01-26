<?php
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

// Legacy Moodle sniffs expect these class names from PHPCS 2.x.
if (!class_exists('PHP_CodeSniffer_Standards_AbstractPatternSniff')) {
    if (class_exists('PHP_CodeSniffer\\Sniffs\\AbstractPatternSniff')) {
        class PHP_CodeSniffer_Standards_AbstractPatternSniff extends PHP_CodeSniffer\Sniffs\AbstractPatternSniff
        {
        }
    } else {
        abstract class PHP_CodeSniffer_Standards_AbstractPatternSniff
        {
        }
    }
}

if (!class_exists('PHP_CodeSniffer_Standards_AbstractScopeSniff')) {
    if (class_exists('PHP_CodeSniffer\\Sniffs\\AbstractScopeSniff')) {
        class PHP_CodeSniffer_Standards_AbstractScopeSniff extends PHP_CodeSniffer\Sniffs\AbstractScopeSniff
        {
        }
    } else {
        abstract class PHP_CodeSniffer_Standards_AbstractScopeSniff
        {
        }
    }
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
    if (class_exists('PHP_CodeSniffer\\Sniffs\\AbstractVariableSniff')) {
        class PHP_CodeSniffer_Standards_AbstractVariableSniff extends PHP_CodeSniffer\Sniffs\AbstractVariableSniff
        {
        }
    } else {
        abstract class PHP_CodeSniffer_Standards_AbstractVariableSniff
        {
        }
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
    $target = $standard . '\\Sniffs\\' . $category . '\\' . $sniff;
    if (class_exists($target)) {
        class_alias($target, $class);
    }
});
