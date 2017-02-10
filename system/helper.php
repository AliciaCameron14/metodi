<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

define('DATE_MYSQL', 0);
define('DATE_JS', 1);
define('DATE_ONLY', 2);
define('DATE_AND_TIME', 3);

function toDate ($obj = null, $format = DATE_MYSQL) // MySQL Date Format function
{
   if (!$obj) { $obj = time(); }
   switch ($format)
   {
      case DATE_MYSQL: default: $format = 'Y-m-d H:i:s'; break;
      case DATE_JS: $format = 'Y-m-d H:i:s'; break;
      case DATE_ONLY: $format = 'Y-m-d'; break;
      case DATE_AND_TIME: $format = 'Y-m-d H:i:s'; break;
   }
   if (is_numeric($obj)) { return (date($format, $obj)); }
   if (is_string($obj))
   {
      $x = date_create_from_format('Y-m-d', $obj);
      if (!$x) { $x = strtotime($obj); } else { $x = $x->getTimestamp(); }
      return (date($format, $x));
   }
   return (date($format, $obj));
}

function getHttpBasicAuthentication ($userName, $password)
{
   return (base64_encode($userName . ':' . $password));
}

function parseString ($str)
{
   $array = array(); parse_str($str, $array);
   if (isset($GLOBALS['config']['kill_magic_quotes']) &&
       $GLOBALS['config']['kill_magic_quotes'])
   {
      function stripslashes_a($array)
      {
         return (is_array($array) ? array_map('stripslashes_a', $array) : stripslashes($array));
      }
      $array = stripslashes_a($array);
   }
   return ($array);
}

function mergeObject ($a, $b, $prefix = '')
{
   if (!$a) { return ($b); }
   if ($b)
   {
      foreach ($b as $key => $value)
      {
         $v = $prefix . $key;
         $a->{$v} = $value;
      }
   }
   return ($a);
}

function arrayToObject($array, $recursive = true)
{
   $obj = new stdClass;
   foreach ($array as $k => $v)
   {
      if($recursive && is_array($v)) { $obj->{$k} = arrayToObject($v); }
      else { $obj->{$k} = $v; }
   }
   return ($obj);
}

function objectToArray($data)
{
   if (is_array($data) || is_object($data))
   {
      $result = array();
      foreach ($data as $key => $value) { $result[$key] = objectToArray($value); }
      return ($result);
   }
   return ($data);
}

function toNumber ($obj)
{
   if (is_numeric($obj)) { return ((float)$obj); }
   if ($obj) { $obj = '' . $obj; } else { $obj = 0; }
   if (is_string($obj)) { $obj = str_replace(array(',', ' '), array('.', ''), $obj); }
   return ((float)$obj);
}

function getConfig ($key)
{
   if (!isset($GLOBALS['config'][$key])) { return (null); }
   return ($GLOBALS['config'][$key]);
}

function baseUrlJs ()
{
   return ('<script type="text/javascript">baseUrl=\'' . baseUrl() . '\';</script>');
}

function languageMapJs()
{
   return ('<script type="text/javascript">languageId=' . json_encode(getCurrentLangId()) .
      ';languageMap=' . json_encode(getLanguageMap()) . ';</script>');
}

?>