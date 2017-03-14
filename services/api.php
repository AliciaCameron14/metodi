<?php

require '../FirePHPCore/fb.php';
require_once("Rest.php");


if (!isset($_SESSION)) {
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
        // $this->response('', 200);
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

        // $email = $this->_request['email'];
        // $password = $this->_request['password'];

        if (!empty($email) and !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                //fetch salt
                $query = $this->mysqli->prepare('SELECT salt FROM userlogin WHERE email = ?' );
                $query->bind_param('s', $email);
                $query->execute();
                $r = $query->get_result();
                // $query = "SELECT salt FROM userlogin WHERE email = '$email' LIMIT 1";
                // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

                if ($r->num_rows > 0) {
                    $result = $r->fetch_assoc();
                    $temp = base64_encode(hash_hmac('sha256', $password, $result['salt'], true));

                    //check
                    $query = $this->mysqli->prepare('SELECT id, userType, email, firstName, familyName FROM userlogin WHERE email = ? AND password = ? LIMIT 1');
                    $query->bind_param('ss', $email, $temp);
                    $query->execute();
                    $r = $query->get_result();

                    // $query = "SELECT id, userType, email, firstName, familyName FROM userlogin WHERE email = '$email' AND password = '" . $temp . "' LIMIT 1";
                    // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

                    if ($r->num_rows > 0) {
                        $result = $r->fetch_assoc();
                        // If success everythig is good send header as "OK" and user details
                        $this->setCurrentUser($result);
                        $this->response($this->json($result), 200);
                    }
                    $this->response('', 204); // If no records "No Content" status
                }
                $this->response('', 204); // If no records "No Content" status
            }
        }

        $error = array(
            'status' => "Failed",
            "msg" => "Invalid Email address or Password"
        );
        $this->response($this->json($error), 400);
    }

    private function getRequirements()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $id = json_decode(file_get_contents("php://input"), true);


        if (is_array($id)) {
            $comma_separated = implode("','", $id);
            $ids = "'" . $comma_separated . "'";

            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement WHERE requirementId IN (?) order by requirementId asc');
            $query->bind_param('s', $ids);

            // $query           = "SELECT requirementId, description FROM requirement WHERE requirementId IN ($ids) order by requirementId asc";
        } elseif ($id) {
            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement WHERE requirementId = $id order by requirementId asc');
            $query->bind_param('s', $id);
            // $query = "SELECT requirementId, description FROM requirement WHERE requirementId = '$id' order by requirementId asc";
        } else {
            $query = $this->mysqli->prepare('SELECT requirementId, description FROM requirement order by requirementId asc');
            // $query = "SELECT requirementId, description FROM requirement order by requirementId asc";
        }

        $query->execute();
        $r = $query->get_result();
        // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = array_map('utf8_encode', $row);
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function getFunctionalities()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $requirement = json_decode(file_get_contents("php://input"), true);
        $id    = $requirement['requirementId'];

        if ($id) {
          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline FROM functionality WHERE requirementId = ? order by functionalityId asc');
            $query->bind_param('s', $id);
            // $query = "SELECT requirementId, functionalityId, description, framework, guideline FROM functionality WHERE requirementId = '$id' order by functionalityId asc";
        } else {
          $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline FROM functionality order by functionalityId asc');
            // $query = "SELECT requirementId, functionalityId, description, framework, guideline FROM functionality order by functionalityId asc";
        }

        $query->execute();
        $r = $query->get_result();

        // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                $result[] = array_map('utf8_encode', $row);
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // No Content
    }

    private function getExamples()
    {
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }

        $functionality = json_decode(file_get_contents("php://input"), true);
        $id = $functionality['functionalityId'];

        if ($id) {
          $query = $this->mysqli->prepare('SELECT example.functionalityId, example.exampleId, example.title, example.description, example.targetGroup, example.screenshot, functionality.requirementId FROM example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId WHERE functionality.functionalityId = ? order by exampleId asc');
            $query->bind_param('s', $id);


            // $query = "SELECT example.functionalityId, example.exampleId, example.title, example.description, example.targetGroup, example.screenshot, functionality.requirementId FROM example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId WHERE functionality.functionalityId = '$id' order by exampleId asc";
        }

        else {
          $query = $this->mysqli->prepare('SELECT example.functionalityId, example.exampleId, example.title, example.description, example.targetGroup, example.screenshot, functionality.requirementId FROM example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId order by exampleId asc');
            // $query = "SELECT example.functionalityId, example.exampleId, example.title, example.description, example.targetGroup, example.screenshot, functionality.requirementId FROM example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId order by exampleId asc";
        }

        $query->execute();
        $r = $query->get_result();

        // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

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
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // No Content
    }

    // private function getWordleFunctionalities()
    // {
    //     if ($this->get_request_method() != "POST") {
    //         $this->response('', 406);
    //     }
    //
    //     $functionalities = json_decode(file_get_contents("php://input"), true);
    //
    //     if (is_array($functionalities)) {
    //       $query = $this->mysqli->prepare('SELECT requirementId, functionalityId, description, framework, guideline FROM functionality WHERE');
    //         // $query = "SELECT requirementId, functionalityId, description, framework, guideline FROM functionality WHERE";
    //
    //         foreach ($functionalities as $key => $value) {
    //
    //             if ($key == 0) {
    //                 $clause = " (requirementId = '" . substr($value, 0, 2) . "' AND functionalityId = '" . substr($value, 2, 2) . "' )";
    //             } else {
    //                 $clause = " OR (requirementId = '" . substr($value, 0, 2) . "' AND functionalityId = '" . substr($value, 2, 2) . "' )";
    //             }
    //             $query = $query . $clause;
    //         }
    //     }
    //     $query->execute();
    //     $r = $query->get_result();
    //
    //     // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
    //
    //     if ($r->num_rows > 0) {
    //         $result = array();
    //
    //         while ($row = $r->fetch_assoc()) {
    //             $result[] = array_map('utf8_encode', $row);
    //         }
    //         $this->response($this->json($result), 200); // send user details
    //     }
    //     $this->response('', 204); // No Content
    // }

    // private function getWordleExamples()
    // {
    //     if ($this->get_request_method() != "POST") {
    //         $this->response('', 406);
    //     }
    //
    //     $examples = json_decode(file_get_contents("php://input"), true);
    //
    //     if (is_array($examples)) {
    //         $query =  "SELECT example.functionalityId, example.exampleId, example.title, example.description, example.targetGroup, example.screenshot, functionality.requirementId FROM example INNER JOIN functionality ON example.functionalityId = functionality.functionalityId WHERE";
    //
    //         foreach ($examples as $key => $value) {
    //
    //             if ($key == 0) {
    //                 $clause = " (example.functionalityId = '" . substr($value, 0, 2) . "' AND example.exampleId = '" . substr($value, 2, 2) . "' )";
    //             } else {
    //                 $clause = " OR (example.functionalityId = '" . substr($value, 0, 2) . "' AND example.exampleId = '" . substr($value, 2, 2) . "' )";
    //             }
    //             $query = $query . $clause;
    //         }
    //     }
    //
    //     $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);
    //
    //     if ($r->num_rows > 0) {
    //         $result = array();
    //
    //         while ($row = $r->fetch_assoc()) {
    //             foreach ($row as $key => $value) {
    //                 $row[$key] = utf8_encode($value);
    //
    //                 if ($key == 'screenshot') {
    //                     $row[$key] = array_map(function($element)
    //                     {
    //                         return $element;
    //                     }, explode(",", $value));
    //                 }
    //             }
    //             $result[] = array_map(function($element)
    //             {
    //                 return $element;
    //             }, $row);
    //         }
    //         $this->response($this->json($result), 200); // send user details
    //     }
    //     $this->response('', 204); // No Content
    // }

    private function getWords()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
          $query = $this->mysqli->prepare('SELECT word, requirements, functionalities, examples FROM wordle order by id asc');

        // $query = "SELECT word, requirements, functionalities, examples FROM wordle order by id asc";
        $query->execute();
        $r = $query->get_result();
        // $r = $this->mysqli->query($query) or die($this->mysqli->error . __LINE__);

        if ($r->num_rows > 0) {
            $result = array();

            while ($row = $r->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);

                    if ($key == 'requirements' || $key == 'functionalities' || $key == 'examples') {
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
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204); // No Content
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
            $this->response('', 204); //"No Content" status
    }

    /*
     *    Encode array into JSON
     */
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
