<?php
define('LOCAL_MODE', 1);

if (defined('LOCAL_MODE'))
{
   $config['debug_mode'] = TRUE;
   $config['base_url'] = '/metodi/';
   $config['db_server'] = 'localhost';
   $config['db_user'] = 'root';
   $config['db_password'] = '';
   $config['db_database'] = 'metodi';
}
//else
//{
//   $config['debug_mode'] = FALSE;
//   $config['base_url'] = '/';
//   $config['db_server'] = 'localhost'; //'stipstapsprong.be.mysql';
//   $config['db_user'] = 'stipstapsprong_';
//   $config['db_password'] = 'N4YEyufX';
//   $config['db_database'] = 'stipstapsprong_';
//}

//$config['log_errors'] = TRUE;
//$config['kill_magic_quotes'] = TRUE;
//$config['helpers'] = array('user', 'file');
// $config['default_controller'] = 'user';
// $config['default_action'] = 'index';
//$config['default_lang_id'] = 'nl';


?>
