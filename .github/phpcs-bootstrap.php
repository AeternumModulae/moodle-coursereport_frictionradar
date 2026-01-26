<?php
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
