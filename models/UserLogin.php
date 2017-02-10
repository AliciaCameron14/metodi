<?php namespace models;

    class UserLogin extends \DatabaseEntity 
    {
        var $id;
        var $email;
        var $password;
        var $salt;
        var $firstName;
        var $familyName;
        var $jobDesc;
        var $organisationName;
        var $organisationAddress;
        var $organisationPostalCode;
        var $organisationEmail;
        var $organisationPhone;
    }

    public function verifyPassword($password)
    {
        return (base64_encode(hash_hmac('sha256', $password, $this->salt, true)) == $this->password);
    }

   public function changePassword ($password)
   {
      $this->salt = base64_encode(hash('sha256', time() . mt_rand() . mt_rand() . mt_rand(), true));
      $this->password = base64_encode(hash_hmac('sha256', $password, $this->salt, true));
   }

   public function getTableDefinition ()
   {
      return (array(
        'userType' => 'int not null',
        'email' => 'varchar(256) not null unique',
        'password' => 'char(44) not null',
        'salt' => 'char(44) not null',
        'firstName' => 'varchar(256) not null',
        'familyName' => 'varchar(256) not null'
      ));
   }
    
?>