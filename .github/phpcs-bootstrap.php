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
