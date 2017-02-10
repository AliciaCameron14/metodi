<?php require_once './config/config.php';

    class Database
    {

      private $db;
          private $err;
          function __construct() {
              $dsn = 'mysql:host='.$GLOBALS['config']['db_server'].';dbname='.$GLOBALS['config']['db_database'].';charset=utf8';
              try {
                  $this->db = new PDO($dsn, $GLOBALS['config']['db_user'], $GLOBALS['config']['db_password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
              } catch (PDOException $e) {
                  $response["status"] = "error";
                  $response["message"] = 'Connection failed: ' . $e->getMessage();
                  $response["data"] = null;
                  //echoResponse(200, $response);
                  exit;
              }
          }

          function select($table, $columns, $where){
                  try{
                      $a = array();
                      $w = "";
                      foreach ($where as $key => $value) {
                          $w .= " and " .$key. " like :".$key;
                          $a[":".$key] = $value;
                      }
                      $stmt = $this->db->prepare("select ".$columns." from ".$table." where 1=1 ". $w);
                      $stmt->execute($a);
                      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      if(count($rows)<=0){
                          $response["status"] = "warning";
                          $response["message"] = "No data found.";
                      }else{
                          $response["status"] = "success";
                          $response["message"] = "Data selected from database";
                      }
                          $response["data"] = $rows;
                  }catch(PDOException $e){
                      $response["status"] = "error";
                      $response["message"] = 'Select Failed: ' .$e->getMessage();
                      $response["data"] = null;
                  }
                  return $response;
              }

              function insert($table, $columnsArray, $requiredColumnsArray) {
                      $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);

                      try{
                          $a = array();
                          $c = "";
                          $v = "";
                          foreach ($columnsArray as $key => $value) {
                              $c .= $key. ", ";
                              $v .= ":".$key. ", ";
                              $a[":".$key] = $value;
                          }
                          $c = rtrim($c,', ');
                          $v = rtrim($v,', ');
                          $stmt =  $this->db->prepare("INSERT INTO $table($c) VALUES($v)");
                          $stmt->execute($a);
                          $affected_rows = $stmt->rowCount();
                          $lastInsertId = $this->db->lastInsertId();
                          $response["status"] = "success";
                          $response["message"] = $affected_rows." row inserted into database";
                          $response["data"] = $lastInsertId;
                      }catch(PDOException $e){
                          $response["status"] = "error";
                          $response["message"] = 'Insert Failed: ' .$e->getMessage();
                          $response["data"] = 0;
                      }
                      return $response;
                  }
                  function update($table, $columnsArray, $where, $requiredColumnsArray){
                      $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);
                      try{
                          $a = array();
                          $w = "";
                          $c = "";
                          foreach ($where as $key => $value) {
                              $w .= " and " .$key. " = :".$key;
                              $a[":".$key] = $value;
                          }
                          foreach ($columnsArray as $key => $value) {
                              $c .= $key. " = :".$key.", ";
                              $a[":".$key] = $value;
                          }
                              $c = rtrim($c,", ");

                          $stmt =  $this->db->prepare("UPDATE $table SET $c WHERE 1=1 ".$w);
                          $stmt->execute($a);
                          $affected_rows = $stmt->rowCount();
                          if($affected_rows<=0){
                              $response["status"] = "warning";
                              $response["message"] = "No row updated";
                          }else{
                              $response["status"] = "success";
                              $response["message"] = $affected_rows." row(s) updated in database";
                          }
                      }catch(PDOException $e){
                          $response["status"] = "error";
                          $response["message"] = "Update Failed: " .$e->getMessage();
                      }
                      return $response;
                  }
                  function delete($table, $where){
                      if(count($where)<=0){
                          $response["status"] = "warning";
                          $response["message"] = "Delete Failed: At least one condition is required";
                      }else{
                          try{
                              $a = array();
                              $w = "";
                              foreach ($where as $key => $value) {
                                  $w .= " and " .$key. " = :".$key;
                                  $a[":".$key] = $value;
                              }
                              $stmt =  $this->db->prepare("DELETE FROM $table WHERE 1=1 ".$w);
                              $stmt->execute($a);
                              $affected_rows = $stmt->rowCount();
                              if($affected_rows<=0){
                                  $response["status"] = "warning";
                                  $response["message"] = "No row deleted";
                              }else{
                                  $response["status"] = "success";
                                  $response["message"] = $affected_rows." row(s) deleted from database";
                              }
                          }catch(PDOException $e){
                              $response["status"] = "error";
                              $response["message"] = 'Delete Failed: ' .$e->getMessage();
                          }
                      }
                      return $response;
                  }

                  function verifyRequiredParams($inArray, $requiredColumns) {
                          $error = false;
                          $errorColumns = "";
                          foreach ($requiredColumns as $field) {
                          // strlen($inArray->$field);
                              if (!isset($inArray->$field) || strlen(trim($inArray->$field)) <= 0) {
                                  $error = true;
                                  $errorColumns .= $field . ', ';
                              }
                          }

                          if ($error) {
                              $response = array();
                              $response["status"] = "error";
                              $response["message"] = 'Required field(s) ' . rtrim($errorColumns, ', ') . ' is missing or empty';
                              echoResponse(200, $response);
                              exit;
                          }
                      }

    //     private $db;
    //     private $config;
     //
    //     public function __construct($config = null)
    //     {
    //         if (!is_array($config)) { $this->config = $GLOBALS['config']; }
    //         else { $this->config = array_merge($GLOBALS['config'], $config); }
    //     }
     //
    //     private function connect()
    //     {
    //        if ($this->db) { return (0); }
    //   $this->db = @new mysqli(
    //      $this->config['db_server'],
    //      $this->config['db_user'],
    //      $this->config['db_password'],
    //      $this->config['db_database']);
     //
    //  if ($this->db->connect_errno)
    //  {
    //     return (new DatabaseError($this->db->connect_errno,
    //        $this->db->connect_error, $this->report > 0));
    //  }
    //   $this->db->set_charset('utf8');
    //   $this->db->autocommit(false);
     //
    //   return (0);
    //     }
      }


?>
