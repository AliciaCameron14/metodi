<?php 
    class Database
    {
        private $db;
        private $config;
        
        public function __construct($config = null)
        {
            if (!is_array($config)) { $this->config = $GLOBALS['config']; }
            else { $this->config = array_merge($GLOBALS['config'], $config); }
        }
        
        private function connect()
        {
           if ($this->db) { return (0); }
      $this->db = @new mysqli(
         $this->config['db_server'],
         $this->config['db_user'],
         $this->config['db_password'],
         $this->config['db_database']);
      if ($this->db->connect_errno)
      {
         return (new DatabaseError($this->db->connect_errno,
            $this->db->connect_error, $this->report > 0));
      }
      $this->db->set_charset('utf8');
      $this->db->autocommit(false);
      return (0);
        }
        
    }
?>