<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

if (isset($GLOBALS['config']['log_errors']) && $GLOBALS['config']['log_errors'])
{
   file_put_contents('errorlog.txt', $errstr . ', ' . $errfile . ' (' . $errline . ")\n", FILE_APPEND);
}

$data = '<pre>';
$data .= 'Error # ' . $errno . "\n";
$data .= 'Error: ' . $errstr . "\n";
$data .= 'File: ' . $errfile . "\n";
$data .= 'Line: ' . $errline . "\n";
$data .= 'Stack: ' . print_r(debug_backtrace(FALSE), true) . "\n";
$data .= '</pre>';
outputFail($data);

if (isset($GLOBALS['config']['debug_mode']) && $GLOBALS['config']['debug_mode'])
{
   $data = ob_get_contents();
   outputData('text', $data);
}

endScript();

?>