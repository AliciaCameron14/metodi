<?php
// if (!defined('BASEPATH')) { exit('No direct script access allowed'); }
session_start();
if (isset($_REQUEST['_SESSION'])) { exit(); }

if (!isset($GLOBALS['config']))
{
   $config = array();
   include_once 'config.php';
   $GLOBALS['config'] = $config;
}
/////////////////////////////////////////////////////////////////////////////////








/////////////////////////////////////////////////////////////////////////////////




// if (get_magic_quotes_runtime()) { set_magic_quotes_runtime(0); }
// if (get_magic_quotes_gpc())
// {
//    function stripslashesArray($array)
//    {
//       return is_array($array) ? array_map('stripslashesArray', $array) : stripslashes($array);
//    }
//    $_GET = stripslashesArray($_GET);
//    $_POST = stripslashesArray($_POST);
//    $_COOKIE = stripslashesArray($_COOKIE);
// }

///////////////////////////////////////////////////////////////////////////////

// function handlePHPError ($errno, $errstr, $errfile, $errline)
// {
//    if (!(error_reporting() & $errno)) { return(false); }
//    include './errors/error_php.php';
//    return(true);
// }
//
// function handleDBError ($errno, $errstr, $fatal)
// {
//    include './errors/error_db.php';
//    if ($fatal) { exit(); }
//    return(true);
// }
//
// set_error_handler('handlePHPError');



// $GLOBALS['fromAjax'] =
//    (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) &&
//    (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

// if (isset($GLOBALS['config']['helpers']) && is_array($GLOBALS['config']['helpers']))
// {
//    foreach ($GLOBALS['config']['helpers'] as $i)
//    {
//       include_once './helpers/' . $i . '.php';
//    }
// }

// $GLOBALS['request'] = array_merge($_GET, $_POST);
// if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'json') !== FALSE)
// {
//    $i = json_decode(file_get_contents('php://input'), true);
//    if ($i) { $GLOBALS['request'] = array_merge($GLOBALS['request'], $i); }
// }
//
// include_once './system/helper.php';
// include_once './system/db.php';

// $db = null;
// if (isset($GLOBALS['config']['db_database'])) { $db = new Database(); }
// $GLOBALS['db'] = $db;

///////////////////////////////////////////////////////////////////////////////

// function modelAutoLoad($className)
// {
//    $i = strpos($className, 'models\\');
//    if ($i === 1 && $className[0] == '\\') { $className = substr($className, 1); $i = 0; }
//    if ($i === 0) { require './' . str_replace('\\', '/', $className) . '.php'; }
// }
//
// spl_autoload_register('modelAutoLoad');

///////////////////////////////////////////////////////////////////////////////

// function baseUrl ($name = '')
// {
//    if (strlen($name) > 7 && strcasecmp(substr($name, 0, 7), 'http://') == 0) { return ($name); }
//    return ($GLOBALS['config']['base_url'] . str_replace('\\', '/', $name));
// }
//
// function baseIndexUrl ($name = '')
// {
//    if (strlen($name) > 7 && strcasecmp(substr($name, 0, 7), 'http://') == 0) { return ($name); }
//    return ($GLOBALS['config']['base_url'] . 'index.php/' . $name);
// }
//
// function getDb () { return ($GLOBALS['db']); }
//
// function endNotFound ()
// {
//    if (!isset($GLOBALS['failStatus']) || !$GLOBALS['failStatus']) { getDb()->commit(); }
//    ob_end_clean();
//    http_response_code(404);
//    exit();
// }
//
// function ___fixdata ($data)
// {
//    if (is_object($data)) { foreach ($data as $key => $value) { $data->{$key} = ___fixdata($value); } }
//    else if (is_array($data)) { foreach ($data as $key => $value) { $data[$key] = ___fixdata($value); } }
//    else if (is_numeric($data) &&
//            (!is_string($data) || (($data[0] != '0' || strlen($data) == 1) && $data[0] != '.' && $data[0] != ' '))) { return (doubleval($data)); }
//    else if (is_string($data) && !mb_detect_encoding($data, 'UTF-8', true))
//    {
//       ///$data = mb_convert_encoding($data, 'ISO-8859-1', 'UTF-8');
//       $data = utf8_encode($data);
//    }
//    return ($data);
// }
//
// function endScript ()
// {
//    if (!isset($GLOBALS['failStatus']) || !$GLOBALS['failStatus']) { getDb()->commit(); }
//    if (isset($GLOBALS['jsonResponse']))
//    {
//       if (isset($GLOBALS['jsonExtra']) && is_array($GLOBALS['jsonExtra']))
//       {
//          if (is_object($GLOBALS['jsonResponse']))
//          {
//             foreach ($GLOBALS['jsonExtra'] as $key => $value)
//             {
//                $GLOBALS['jsonResponse']->{$key} = $value;
//             }
//          }
//          else if (is_array($GLOBALS['jsonResponse']))
//          {
//             foreach ($GLOBALS['jsonExtra'] as $key => $value)
//             {
//                $GLOBALS['jsonResponse'][$key] = $value;
//             }
//          }
//       }
//       ob_end_clean();
//       if (!$GLOBALS['fromAjax'])
//       {
//          header('Content-Type: text/html');
//          if (!isset($GLOBALS['autoIncludeFiles']) || $GLOBALS['autoIncludeFiles'])
//          {
//             // echo getIncludedStyles();
//             // echo getIncludsedScripts();
//          }
//          if (!isset($GLOBALS['jsonResponse']->message))
//          {
//             echo json_encode(___fixdata($GLOBALS['jsonResponse']));
//          }
//          else
//          {
//             echo $GLOBALS['jsonResponse']->message;
//          }
//       }
//       else
//       {
//          if ((isset($GLOBALS['returnHtml']) && $GLOBALS['returnHtml']) || input('html'))
//          {
//             header('Content-Type: text/html');
//             if (!isset($GLOBALS['autoIncludeFiles']) || $GLOBALS['autoIncludeFiles'])
//             {
//               //  echo getIncludedStyles();
//               //  echo getIncludedScripts();
//             }
//             echo '<div class="return" title="' . htmlentities(json_encode(___fixdata($GLOBALS['jsonResponse']))) . '"></div>';
//          }
//          else
//          {
//             header('Content-Type: application/json');
//             echo json_encode(___fixdata($GLOBALS['jsonResponse']));
//          }
//       }
//    }
//    else
//    {
//       $data = ob_get_clean();
//       if (!$GLOBALS['fromAjax'] || input('html') ||
//           (isset($GLOBALS['returnHtml']) && $GLOBALS['returnHtml']))
//       {
//          header('Content-Type: text/html');
//          if (!isset($GLOBALS['autoIncludeFiles']) || $GLOBALS['autoIncludeFiles'])
//          {
//             // echo getIncludedStyles();
//             // echo getIncludedScripts();
//          }
//          echo $data;
//       }
//       else
//       {
//          $data = ob_get_clean();
//          header('Content-Type: application/json');
//          echo json_encode(___fixdata(array('returnValue' => $data)));
//       }
//    }
//    exit();
// }
//
// $includedFiles = array();
// $includedFilesSorted = false;
//
// function includeFile ($file, $priority = 1)
// {
//    global $includedFiles;
//    global $includedFilesSorted;
//    $includedFiles[$file] = $priority;
//    $includedFilesSorted = false;
// }
//
// // function setAutoIncludeFiles ($state)
// // {
// //    $GLOBALS['autoIncludeFiles'] = $state;
// // }
//
// // function getIncludedScripts ()
// // {
// //    global $includedFiles;
// //    global $includedFilesSorted;
// //    if (!$includedFilesSorted)
// //    {
// //       arsort($includedFiles);
// //       $includedFilesSorted = true;
// //    }
// //    $str = '';
// //    foreach ($includedFiles as $file => $x)
// //    {
// //       if (substr($file, -3) != '.js' && substr($file, -5) != '.html') { continue; }
// //       $str .= '<script type="text/javascript" src="' . baseUrl($file) . '"></script>';
// //    }
// //    return ($str);
// // }
//
// // function getIncludedStyles ()
// // {
// //    global $includedFiles;
// //    global $includedFilesSorted;
// //    if (!$includedFilesSorted)
// //    {
// //       arsort($includedFiles);
// //       $includedFilesSorted = true;
// //    }
// //    $str = '';
// //    foreach ($includedFiles as $file => $x)
// //    {
// //       if (substr($file, -4) != '.css') { continue; }
// //       $str .= '<link rel="stylesheet" type="text/css" href="' . baseUrl($file) . '" />';
// //    }
// //    return ($str);
// // }
//
// function loadView ($path, $params = null, $capture = false, $forceFullView = false)
// {
//    $data = loadViewOnly($path, $params, true);
//    if (!$GLOBALS['fromAjax'] || $forceFullView)
//    {
//       for ($pathTree = explode('/', $path), $i = count($pathTree) - 1; $i >= 0; $i--)
//       {
//          $pathTree = array_slice($pathTree, 0, $i);
//          $path = join('/', $pathTree) . '/_viewstart';
//          if (file_exists('./views/' . $path . '.php'))
//          {
//             if (!$params) { $params = array(); }
//             $params['body'] = $data;
//             $data = loadViewOnly($path, $params, true);
//          }
//       }
//    }
//    if (!$capture) { outputHtml($data); }
//    return ($data);
// }
//
// function loadViewOnly ($path, $params = null, $capture = false)
// {
//    if (is_array($params)) { foreach ($params as $key => $value) { $$key = $value; } }
//    ob_start();
//    require ('./views/' . $path . '.php');
//    $data = preg_replace_callback('/\[\[([A-Za-z0-9_\-]+)\]\]/',
//       function ($a) { return (getStringFor($a[1])); }, ob_get_clean());
//    if (!$capture) { outputHtml($data); }
//    return ($data);
// }
//
// ///////////////////////////////////////////////////////////////////////////////
//
// function startJsonOutput ()
// {
//    if (!isset($GLOBALS['jsonResponse']))
//    {
//       $obj = new stdClass();
//       $GLOBALS['jsonResponse'] = $obj;
//       return ($obj);
//    }
//    return ($GLOBALS['jsonResponse']);
// }
//
// function outputJson ($data)
// {
//    $GLOBALS['jsonResponse'] = $data;
// }
//
// function outputData ($key, $data)
// {
//    $obj = startJsonOutput();
//    $obj->{$key} = $data;
// }
//
// function outputFail ($message)
// {
//    $obj = startJsonOutput();
//    $obj->message = $message;
//    $GLOBALS['failStatus'] = 1;
//    http_response_code(500);
// }
//
// function outputExtra ($key, $value)
// {
//    if (!isset($GLOBALS['jsonExtra']) || !$GLOBALS['jsonExtra']) { $GLOBALS['jsonExtra'] = array(); }
//    $GLOBALS['jsonExtra'][$key] = $value;
// }
//
// function outputTotalCount ($data)
// {
//    $obj = startJsonOutput();
//    $obj->count = $data;
// }
//
// function forceHtml ()
// {
//    $GLOBALS['returnHtml'] = 1;
// }
//
// function outputHtml ($data)
// {
//    echo $data;
// }
//
// function redirect ($url)
// {
//    header('Location: ' . baseIndexUrl($url));
//    die();
// }
//
// ///////////////////////////////////////////////////////////////////////////////
//
// class UploadedFile
// {
//    var $fieldName;
//    var $tmpFileName;
//    var $name;
//    var $size;
//    var $error;
//
//    public function __construct ($fieldName, $data, $key = null)
//    {
//       $this->fieldName = $fieldName;
//       if ($key === null)
//       {
//          $this->tmpFileName = $data['tmp_name'];
//          $this->name = $data['name'];
//          $this->size = $data['size'];
//          $this->error = $data['error'];
//       }
//       else
//       {
//          $this->tmpFileName = $data['tmp_name'][$key];
//          $this->name = $data['name'][$key];
//          $this->size = $data['size'][$key];
//          $this->error = $data['error'][$key];
//       }
//    }
//
//    public function good ()
//    {
//       return ($this->error == UPLOAD_ERR_OK);
//    }
//
//    public function get ()
//    {
//       return (file_get_contents($this->tmpFileName));
//    }
//
//    public function saveAs ($name)
//    {
//       return (move_uploaded_file($this->tmpFileName, $name));
//    }
// }
//
// function getUploadedFiles ()
// {
//    $files = array();
//    foreach ($_FILES as $name => $file)
//    {
//       if (is_array($file['error']))
//       {
//          foreach ($file['error'] as $key => $error)
//          {
//             if ($error == UPLOAD_ERR_NO_FILE) { continue; }
//             $files[] = new UploadedFile($name, $file, $key);
//          }
//       }
//       else if ($file['error'] != UPLOAD_ERR_NO_FILE)
//       {
//          $files[] = new UploadedFile($name, $file);
//       }
//    }
//    return ($files);
// }
//
// function input ($name = null)
// {
//    if ($name === null) { return ($GLOBALS['request']); }
//    if (!isset($GLOBALS['request'][$name])) { return (false); }
//    return ($GLOBALS['request'][$name]);
// }
//
// function getPagedRequestDetails ()
// {
//    if (($count = input('itemsPerPage')) === false) { return (null); }
//    $obj = new stdClass();
//    $obj->pagingItemCount = (int)$count;
//    $obj->pagingItemOffset = ((int)input('page') - 1) * $obj->pagingItemCount;
//    if ($obj->pagingItemOffset < 0) { $obj->pagingItemOffset = 0; }
//    return ($obj);
// }
//
// function getCurrentLangId ()
// {
//    if (!isset($_SESSION['langId'])) { return ($GLOBALS['config']['default_lang_id']); }
//    return ($_SESSION['langId']);
// }
//
// function setCurrentLangId ($langId)
// {
//    if (!$langId) { $langId = $GLOBALS['config']['default_lang_id']; }
//    $_SESSION['langId'] = $langId;
//    unset($GLOBALS['langMap']);
// }
//
// function getLanguageMap ()
// {
//    if (!isset($GLOBALS['langMap']))
//    {
//       $map = array();
//       $path = './lang/' . getCurrentLangId() . '.xml';
//       if (file_exists($path))
//       {
//          $xml = new LanguageXMLParser();
//          $map = $xml->parse($path);
//       }
//       $GLOBALS['langMap'] = $map;
//    }
//    return ($GLOBALS['langMap']);
// }
//
// function getStringFor ($key)
// {
//    if (!isset($GLOBALS['langMap'])) { getLanguageMap(); }
//    if (!isset($GLOBALS['langMap'][$key])) { return ('(' . $key . '?)'); }
//    return ($GLOBALS['langMap'][$key]);
// }
//
// class LanguageXMLParser
// {
//    var $key;
//    var $result;
//
//    public function parse ($file)
//    {
//       $this->result = array();
//       $xml = xml_parser_create();
//       xml_set_object($xml, $this);
//       xml_set_element_handler($xml, 'xmlStart', false);
//       xml_set_character_data_handler($xml, 'xmlData');
//       xml_parse($xml, file_get_contents($file), true);
//       xml_parser_free($xml);
//       return ($this->result);
//    }
//
//    function xmlStart ($parser, $name, $attr)
//    {
//       $this->key = null;
//       if ($attr && isset($attr['KEY'])) { $this->key = strtolower($attr['KEY']); }
//    }
//
//    function xmlData ($parser, $data)
//    {
//       $data = trim($data);
//       if (strlen($data) == 0 || !$this->key) { return; }
//       if (!key_exists($this->key, $this->result))
//       {
//          $this->result[$this->key] = $data;
//       }
//       else if ($data != '')
//       {
//          $this->result[$this->key] .= $data;
//       }
//    }
// }
//
// class StringContainer
// {
//    var $data = array();
//
//    public function set ($text)
//    {
//       $this->data[getCurrentLangId()] = $text;
//    }
//
//    public function get ()
//    {
//       $langId = getCurrentLangId();
//       if (!isset($this->data[$langId]))
//       {
//          foreach ($this->data as $key => $value) { return ($value); }
//          return ('');
//       }
//       return ($this->data[$langId]);
//    }
//
//    public function __toString() { return ($this->get()); }
// }
//
// ///////////////////////////////////////////////////////////////////////////////
//
// class HtmlElement
// {
//    var $tag;
//    var $attributes = array();
//    var $styles;
//    var $classes;
//    var $content;
//
//    public function __construct($tag) { $this->tag = $tag; }
//
//    public function setTag ($tag) { $this->tag = $tag; return ($this); }
//    public function setAttribute ($key, $value) { $this->attributes[$key] = $value; return ($this); }
//    public function clearAttribute ($key) { unset($this->attributes[$key]); return ($this); }
//    public function setContent ($content) { $this->content = $content; return ($this); }
//
//    public function appendContent ($content)
//    {
//       if (is_array($this->content)) { $this->content[] = $content; }
//       else if (is_a($this->content, 'HtmlElement')) { $this->content = array($this->content, $content); }
//       else { $this->content = $this->content . $content; }
//       return ($this);
//    }
//
//    public function appendAttribute ($key, $value)
//    {
//       if (!isset($this->attributes[$key])) { $this->attributes[$key] = $value; }
//       else { $this->attributes[$key] .= ' ' . $value; }
//       return ($this);
//    }
//
//    public function setStyle ($key, $value)
//    {
//       if (!$this->styles) { $this->styles = array(); }
//       $this->styles[$key] = $value;
//       return ($this);
//    }
//
//    public function addClass ($class)
//    {
//       if (!$this->classes) { $this->classes = array(); }
//       $this->classes[$class] = 1;
//       return ($this);
//    }
//
//    public function removeClass ($class)
//    {
//       if ($this->classes) { unset($this->classes[$class]); }
//       return ($this);
//    }
//
//    public function hasClass ($class)
//    {
//       if (!$this->classes) { return (false); }
//       return (isset($this->classes[$class]));
//    }
//
//    public function get ()
//    {
//       $str = '<' . $this->tag;
//       foreach ($this->attributes as $key => $value)
//       {
//          $str .= ' ' . $key . '="' . $value . '"';
//       }
//       if ($this->classes)
//       {
//          $class = null;
//          foreach ($this->classes as $key => $value)
//          {
//             if (!$class) { $class = $key; }
//             else { $class .= ' ' . $key; }
//          }
//          if ($class) { $str .= ' class="' . $class . '"'; }
//       }
//       if ($this->styles)
//       {
//          $style = '';
//          foreach ($this->styles as $key => $value)
//          {
//             $style .= $key . ':' . $value . ';';
//          }
//          $str .= ' style="' . $style . '"';
//       }
//       $content = $this->content;
//       if (is_array($content))
//       {
//          $content = '';
//          foreach ($this->content as $value) { $content .= $value; }
//       }
//       else { $str .= '>' . $content . '</' . $this->tag . '>'; }
//       return ($str);
//    }
//
//    public function __toString() { return ($this->get()); }
// }
//
// function htmlActionLink ($content, $action, $controller = null, $params = null)
// {
//    $paramStr = '/';
//    if (!$controller) { $controller = getCurrentController(); }
//    if ($params) { $paramStr .= '?' . http_build_query($params); }
//    $element = new HtmlElement('a');
//    return ($element
//       ->setContent($content)
//       ->setAttribute('href', baseIndexUrl($controller . '/' . $action . $paramStr)));
// }
//
// function htmlActionNavItem ($content, $action, $controller = null, $params = null)
// {
//    $element = new HtmlElement('li');
//    $li = $element->setContent(htmlActionLink($content, $action, $controller, $params));
//    if (strcasecmp(getCurrentAction(), $action) == 0 &&
//        (!$controller || strcasecmp(getCurrentController(), $controller) == 0))
//    {
//       $li->addClass('selected');
//    }
//    return ($li);
// }
//
// ///////////////////////////////////////////////////////////////////////////////
//
// class Controller
// {
//    var $db;
//
//    public function __construct ($db) { $this->db = $db; }
//
//    public function index () { }
//
//    public function input ($key = null) { return (input($key)); }
//    public function output ($data) { outputData($data); }
//    public function outputHtml ($data) { outputHtml($data); }
//    public function outputJson($data) { outputJson($data); }
//    public function fail ($data) { outputFail($data); }
//    public function view ($name, $params = null, $return = false)
//    {
//       if ($return) { return (loadView($name, $params, true)); }
//       return (outputHtml(loadView($name, $params, true)));
//    }
//    public function notFound () { endNotFound(); }
//    public function end () { endScript(); }
// }
//
// ?>
