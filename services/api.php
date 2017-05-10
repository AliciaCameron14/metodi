<?php

require '../FirePHPCore/fb.php';
require '../PHPMailer/PHPMailerAutoload.php';
require_once("Rest.php");


if (!isset($_SESSION)) {
  ini_set('session.gc_maxlifetime', 30);
  session_set_cookie_params(30);
    session_start();
}

class API extends REST
{
    public $data = "";
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "metodi";
    private $db = NULL;
    private $mysqli = NULL;

    public function __construct()
    {
        parent::__construct(); // Init parent contructor
        $this->dbConnect(); // Initiate Database connection
    }

    /*
     *  Connect to Database
     */
    private function dbConnect()
    {
        $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
    }

    /*
     * Dynmically call the method based on the query string
     */
    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404); // If the method not exist -> "Page not found".
    }

    private function updateChain()
    {
        $chain = json_decode(file_get_contents("php://input"), true);
        $_SESSION['chain'] = $chain;
    }

    private function getChain()
    {
        if (!isset($_SESSION['chain'])) {
            $this->response('', 204);
        } else {
            $this->response($this->json($_SESSION['chain']), 200);
        }
    }

    private function setCurrentUser($user)
    {
        $_SESSION['user'] = $user;
    }

    private function getCurrentUser()
    {
        if (!isset($_SESSION['user'])) {
            $this->response('', 204);
        } else {
            $this->response($this->json($_SESSION['user']), 200);
        }

    }

    private function clearCurrentUser()
    {
        unset($_SESSION['user']);
        unset($_SESSION['chain']);

        if (!isset($_SESSION['user']) && !isset($_SESSION['chain'])) {
            $this->response('', 200);
        }
        $this->response('', 500);
    }

    private function login()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $user     = json_decode(file_get_contents("php://input"), true);
        $email    = $user['email'];
        $password = $user['password'];

        if (!empty($email) and !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                //fetch salt
                $query = $this->mysqli->prepare('SELECT salt FROM userlogin WHERE email = ?' );
                $query->bind_param('s', $email);
                $query->execute();
                $r = $query->get_result();

                if ($r->num_rows > 0) {
                    $result = $r->fetch_assoc();
                    $temp = base64_encode(hash_hmac('sha256', $password, $result['salt'], true));

                    //check
                    $query = $this->mysqli->prepare('SELECT id, userType, email, firstName, familyName FROM userlogin WHERE email = ? AND password = ? LIMIT 1');
                    $query->bind_param('ss', $email, $temp);
                    $query->execute();
                    $r = $query->get_result();

                    if ($r->num_rows > 0) {
                        $result = $r->fetch_assoc();
                        $this->setCurrentUser($result);
                        $this->response($this->json($result), 200); //send user details
                    }
                    $this->response('', 204); // "No Content" status
                }
                $this->response('', 204);
            }
        }

        $error = array(
            'status' => "Failed",
            "msg" => "Invalid Email address or Password"
        );
        $this->response($this->json($error), 400);
    }

    // private function forgotPassword()
    // {
    //   $emailAddress = json_decode(file_get_contents("php://input"), true);
    //   FB::info($emailAddress);
    //   $mail = new PHPMailer();
    // }

    private function getRequirements()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $id = json_decode(file_get_contents("php://input"), true);

        if (is_array($id)) {
            $comma_separated = implode("','", $id);
            $ids = "'" . $comma_separated . "'";

            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement WHERE requirementId IN (?) order by requirementId * 1');
            $query->bind_param('s', $ids);

        } elseif ($id) {
            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement WHERE requirementId = $id order by requirementId * 1');
            $query->bind_param('s', $id);

        } else {
            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement order by requirementId * 1');

        }

        $query->execute();
        $r = $query->get_result();

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = array_map('utf8_encode', $row);
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204);
    }

    private function getFunctionalities()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $requirement = json_decode(file_get_contents("php://input"), true);
        $id    = $requirement['requirementId'];

        if ($id) {
          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality WHERE requirementId = ? order by functionalityId * 1');
            $query->bind_param('s', $id);

        } else {
          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality order by functionalityId * 1');
        }

        $query->execute();
        $r = $query->get_result();

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
              foreach ($row as $key => $value) {
                  $row[$key] = utf8_encode($value);

                  if ($key == 'links' && $value != "") {
                      $row[$key] = array_map(function($element)
                      {
                          return $element;
                      }, explode(",", $value));
                  }
              }
              $result[] = array_map(function($element)
              {
                  return $element;
              }, $row);
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204);
    }

    private function getExamples()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $id = $functionality['functionalityId'];
        $requirementId = $functionality['requirementId'];

        if ($id) {

          $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example WHERE requirementId = ? AND functionalityId = ? order by exampleId * 1');
            $query->bind_param('ss', $requirementId, $id);
        }

        else {
            $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example order by exampleId * 1');
        }

        $query->execute();
        $r = $query->get_result();

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'screenshot') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204);
    }

    private function getWords()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
          $query = $this->mysqli->prepare('SELECT word, requirements, functionalities, examples FROM wordle order by id asc');

        $query->execute();
        $r = $query->get_result();

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'requirements' || $key == 'functionalities' || $key == 'examples') {
                      if ($value != '') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                      }
                      else {
                      $row[$key] = array();
                      }
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
            }
            $this->response($this->json($result), 200);
        }
        $this->response('', 204);
    }

    private function editRequirement()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $requirement = json_decode(file_get_contents("php://input"), true);
        $id = $requirement['requirementId'];
        $desc = $requirement['description'];

        if ($requirement) {
            $query = $this->mysqli->prepare('UPDATE requirement SET description = ? WHERE requirementId = ?');
            $query->bind_param('ss', $desc, $id);
        }

        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

              $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement order by requirementId * 1');

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_encode', $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 500);
    }

    private function addRequirement()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $requirement = json_decode(file_get_contents("php://input"), true);
        $id = $requirement['requirementId'];
        $desc = $requirement['description'];

        if ($requirement) {
            $query = $this->mysqli->prepare('INSERT INTO requirement (requirementId, description) VALUES(?,?)');
            $query->bind_param('ss',$id, $desc);
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

              $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement order by requirementId * 1');

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_encode', $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 500);
    }

    private function deleteRequirement()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $requirement = json_decode(file_get_contents("php://input"), true);
        $id = $requirement['requirementId'];

        if ($requirement) {
            $query = $this->mysqli->prepare('DELETE FROM requirement WHERE requirementId = ?');
            $query->bind_param('s',$id);
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

              $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement order by requirementId * 1');

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_encode', $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 204);
    }

    private function editFunctionality()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $requirementId = $functionality['requirementId'];
        $functionalityId = $functionality['functionalityId'];
        $framework = $functionality['framework'];
        $guideline = $functionality['guideline'];
        $desc = $functionality['description'];

        if (array_key_exists('oldRequirementId', $functionality)) {
          $oldRequirementId = $functionality['oldRequirementId'];
        } else $oldRequirementId = $functionality['requirementId'];

        if ($functionality) {
            $query = $this->mysqli->prepare('UPDATE functionality SET requirementId = ?, description = ?, framework = ?, guideline = ? WHERE functionalityId = ? AND requirementId = ? ');
            $query->bind_param('ssssss', $requirementId, $desc, $framework, $guideline,  $functionalityId, $oldRequirementId);
        }

        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality WHERE requirementId = ? order by functionalityId * 1');
            $query->bind_param('s', $oldRequirementId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'links') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 204);
    }

    private function editFunctionalityLinks()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $requirementId = $functionality['requirementId'];
        $functionalityId = $functionality['functionalityId'];
        $linkObjects = $functionality['links'];
        $links = array();


        foreach ($linkObjects as $key => $value) {
          array_push($links, $value);
        }
        $links = implode(",", $links);

        if ($functionality) {
            $query = $this->mysqli->prepare('UPDATE functionality SET links = ? WHERE functionalityId = ? AND requirementId = ? ');
            $query->bind_param('sss', $links, $functionalityId, $requirementId);
        }


         $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality WHERE functionalityId = ? AND requirementId = ? order by functionalityId * 1');
            $query->bind_param('ss', $functionalityId, $requirementId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  foreach ($row as $key => $value) {
                      $row[$key] = utf8_encode($value);

                      if ($key == 'links') {
                          $row[$key] = array_map(function($element)
                          {
                              return $element;
                          }, explode(",", $value));
                      }
                  }
                  $result[] = array_map(function($element)
                  {
                      return $element;
                  }, $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 204);
    }

    private function addFunctionality()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $requirementId = $functionality['requirementId'];
        $functionalityId = $functionality['functionalityId'];
        $desc = $functionality['description'];

        if ($functionality) {
            $query = $this->mysqli->prepare('INSERT INTO functionality (requirementId, functionalityId, description) VALUES(?,?,?)');
            $query->bind_param('sss', $requirementId, $functionalityId, $desc);

        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality WHERE requirementId = ? order by functionalityId * 1');
            $query->bind_param('s', $requirementId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'links') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 500);
    }

    private function deleteFunctionality()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $functionalityId = $functionality['functionalityId'];
        $requirementId = $functionality['requirementId'];


        if ($functionality) {
            $query = $this->mysqli->prepare('DELETE FROM functionality WHERE functionalityId = ? AND requirementId = ?');
            $query->bind_param('ss', $functionalityId, $requirementId);
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline, links FROM functionality WHERE requirementId = ? order by functionalityId * 1');
            $query->bind_param('s', $requirementId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'links') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
              }
              $this->response($this->json($result), 200);
          }
        else  $this->response('', 204);
        }
    }

    private function editExample()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $example = json_decode(file_get_contents("php://input"), true);
        $exampleId = $example['exampleId'];
        $requirementId = $example['requirementId'];
        $functionalityId = $example['functionalityId'];
        $title = $example['title'];
        $desc = $example['description'];
        $targetGroup = $example['targetGroup'];

        if (array_key_exists('oldFunctionalityId', $example)) {
          $oldFunctionalityId = $example['oldFunctionalityId'];
        } else $oldFunctionalityId = $example['functionalityId'];

        if ($example) {

            $query = $this->mysqli->prepare('UPDATE example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId SET example.functionalityId = ?, example.title = ?, example.description = ?, example.targetGroup = ? WHERE example.exampleId = ? AND example.functionalityId = ? AND functionality.requirementId = ?');

            $query->bind_param('sssssss', $functionalityId, $title, $desc, $targetGroup, $exampleId, $oldFunctionalityId, $requirementId);
        }

        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example WHERE requirementId = ? AND functionalityId = ? order by exampleId * 1');
            $query->bind_param('ss', $requirementId, $oldFunctionalityId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_decode', $row);
              }
              $this->response($this->json($result), 200);
          }
        }
        $this->response('', 204);
    }

    private function addExample()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $example = json_decode(file_get_contents("php://input"), true);
        $exampleId = $example['exampleId'];
        $requirementId = $example['requirementId'];
        $functionalityId = $example['functionalityId'];
        $title = $example['title'];

        if (array_key_exists('targetGroup', $example)) {
          $targetGroup = $example['targetGroup'];
        } $targetGroup = "";

        if ($example) {
            $query = $this->mysqli->prepare('INSERT INTO example (requirementId, functionalityId, exampleId, title, targetGroup) VALUES(?,?,?,?,?)');
            $query->bind_param('sssss', $requirementId, $functionalityId, $exampleId, $title, $targetGroup );
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example WHERE requirementId = ? AND functionalityId = ? order by exampleId * 1');
            $query->bind_param('ss', $requirementId, $functionalityId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_encode', $row);
              }
              $this->response($this->json($result), 200);
          }$this->response('', 204);
        }
    }

    private function deleteExample()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $example = json_decode(file_get_contents("php://input"), true);
        $exampleId = $example['exampleId'];
        $requirementId = $example['requirementId'];
        $functionalityId = $example['functionalityId'];

        if ($example) {
            $query = $this->mysqli->prepare('DELETE FROM example WHERE functionalityId = ? AND requirementId = ? AND exampleId = ?');
            $query->bind_param('sss', $functionalityId, $requirementId, $exampleId);
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {

          $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example WHERE requirementId = ? AND functionalityId = ? order by exampleId * 1');
            $query->bind_param('ss', $requirementId, $functionalityId);

          $query->execute();
          $r = $query->get_result();

          if ($r->num_rows > 0) {
              $result = array();

              while ($row = $r->fetch_assoc()) {
                  $result[] = array_map('utf8_encode', $row);
              }
              $this->response($this->json($result), 200);
          }
        else  $this->response('', 204);
        }
    }

    private function addExampleImg()
    {
        $exampleId = $_POST['exampleId'];
        $requirementId = $_POST['requirementId'];
        $functionalityId = $_POST['functionalityId'];

        if ( !empty( $_FILES ) ) {

          $path = '..' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $_POST['path']. DIRECTORY_SEPARATOR;

          if (!file_exists($path)) {
            mkdir($path, 0777, true);
          }
            $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
            $uploadPath = $path . $_FILES[ 'file' ][ 'name' ];
            move_uploaded_file( $tempPath, $uploadPath );
        } else {
            // echo 'No files';
        }

        $dirContents = array_diff(scandir($path), array('..', '.'));
        $images = implode(",", $dirContents);

        if ($images) {
            $query = $this->mysqli->prepare('UPDATE example SET screenshot = ? WHERE exampleId = ? AND functionalityId = ? AND requirementId = ?');

            $query->bind_param('ssss', $images, $exampleId, $functionalityId, $requirementId);
            $query->execute();

            if ($this->mysqli->affected_rows >= 0) {
                  $this->response($this->json('OK'), 200);
            }
            $this->response('', 204);
        }
    }

    private function deleteExampleImg()
    {
      $example = json_decode(file_get_contents("php://input"), true);
      $exampleId = $example['exampleId'];
      $requirementId = $example['requirementId'];
      $functionalityId = $example['functionalityId'];
      $folder = $example['path'];
      $imagesToRemove = $example['imagesToRemove'];

          $path = '..' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $folder. DIRECTORY_SEPARATOR;

          foreach ($imagesToRemove as $key => $value) {
            $file_to_delete = $path.$value;
            unlink($file_to_delete);
          }

        $dirContents = array_diff(scandir($path), array('..', '.'));
        $images = implode(",", $dirContents);

        if ($images) {
            $query = $this->mysqli->prepare('UPDATE example SET screenshot = ? WHERE exampleId = ? AND functionalityId = ? AND requirementId = ?');

            $query->bind_param('ssss', $images, $exampleId, $functionalityId, $requirementId);
            $query->execute();

            if ($this->mysqli->affected_rows >= 0) {
                  $this->response($this->json('OK'), 200);
            }
            $this->response('', 204);
        }
    }

    private function getExample()
    {
       if ($this->get_request_method() != "POST") {
          $this->response('', 406);
      }

      $example = json_decode(file_get_contents("php://input"), true);
      $exampleId = $example['exampleId'];
      $requirementId = $example['requirementId'];
      $functionalityId = $example['functionalityId'];

      if ($example) {
        $query = $this->mysqli->prepare('SELECT functionalityId, exampleId, title, description, targetGroup, screenshot, requirementId FROM example WHERE exampleId = ? AND functionalityId = ? AND requirementId = ? order by exampleId * 1');

        $query->bind_param('sss', $exampleId, $functionalityId, $requirementId);

        $query->execute();
        $r = $query->get_result();

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'screenshot') {
                        $row[$key] = array_map(function($element)
                        {
                            return $element;
                        }, explode(",", $value));
                    }
                }
                $result[] = array_map(function($element)
                {
                    return $element;
                }, $row);
            }
            $this->response($this->json($result), 200);
        }
      }
      $this->response('', 204);
    }

    private function addWord()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $word = json_decode(file_get_contents("php://input"), true);

        if ($word) {
            $query = $this->mysqli->prepare('INSERT INTO wordle (word, requirements, functionalities, examples) VALUES(?, "", "", "")');
            $query->bind_param('s', $word['word']);
        }
        $query->execute();
        if ($this->mysqli->affected_rows >= 0) {
              $this->response('', 200);
        }
        $this->response('', 500);
    }

    private function editWord()
    {
      if ($this->get_request_method() != "POST") {
        $this->response('', 406);
      }

      $word = json_decode(file_get_contents("php://input"), true);

      $requirements = implode(",", $word['requirements']);
      $functionalities = implode(",", $word['functionalities']);
      $examples = implode(",", $word['examples']);
      $name = $word['word'];


      if ($word) {
        $query = $this->mysqli->prepare('UPDATE wordle SET requirements = ?, functionalities = ?, examples =? WHERE word = ?');
        $query->bind_param('ssss', $requirements, $functionalities, $examples, $name);
      }
      $query->execute();
      if ($this->mysqli->affected_rows >= 0) {
            $this->response('', 200);
      }
      $this->response('', 500);
    }


        private function removeWord()
        {
            if ($this->get_request_method() != "POST") {
                $this->response('', 406);
            }

            $word = json_decode(file_get_contents("php://input"), true);

            if ($word) {
                $query = $this->mysqli->prepare('DELETE FROM wordle WHERE word = ?');
                $query->bind_param('s', $word['word']);
            }
            $query->execute();
            if ($this->mysqli->affected_rows >= 0) {
                  $this->response('', 200);
            }
            $this->response('', 500);
        }


    private function insertUser()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $user = json_decode(file_get_contents("php://input"), true);
        $user = $this->changePassword($user);

        $column_names = array(
            'userType',
            'email',
            'password',
            'salt',
            'firstName',
            'familyName',
            'jobDesc',
            'organisationName',
            'organisationAddress',
            'organisationPostalCode',
            'organisationEmail',
            'organisationPhone'
        );
        $keys = array_keys($user);
        $columns = '';
        $values = '';
        foreach ($column_names as $desired_key) { // Check the user received. If blank insert blank into the array.
            if (!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $user[$desired_key];
            }
            $columns = $columns . $desired_key . ',';
            $values  = $values . "'" . $$desired_key . "',";
        }
        $query = "INSERT INTO userlogin(" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ")";
        if (!empty($user)) {
            $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
            $success = array(
                'status' => "Success",
                "msg" => "User Created Successfully.",
                "data" => $user
            );
            $this->response($this->json($success), 200);
        } else
            $this->response('', 500);
    }

       // Encode array into JSON
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }
    }

    private function changePassword($user)
    {
        $user['salt']     = base64_encode(hash('sha256', time() . mt_rand() . mt_rand() . mt_rand(), true));
        $user['password'] = base64_encode(hash_hmac('sha256', $user['password'], $user['salt'], true));
        return $user;
    }
}

// Initiiate Library
$api = new API;
$api->processApi();
?>
