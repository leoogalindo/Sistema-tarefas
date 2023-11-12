<?php 
session_start();
require_once('DBConnection.php');
/**
 * Login Registration Class
 */
Class LoginRegistration extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){

        extract($_POST);

        $allowedToken = $_SESSION['formToken']['login'];
        if(!isset($formToken) || (isset($formToken) && $formToken != $allowedToken)){
            
            $resp['status'] = 'failed';
            $resp['msg'] = "Security Check: Form Token is invalid.";
        }else{

            $sql = "SELECT * FROM user_list where username = :username ";

            $stmt = $this->prepare($sql);

            $stmt->bindValue(':username', $username, SQLITE3_TEXT);

            $result = $stmt->execute();

            $data = $result->fetchArray();
            if(!empty($data)){

                $password_verify = password_verify($password, $data['password']);
                if($password_verify){
                    if($data['status'] == 1){
                        // Login Success
                        $resp['status'] = "success";
                        $resp['msg'] = "Login com sucesso.";
                        foreach($data as $k => $v){
                            if(!is_numeric($k) && !in_array($k, ['password']))
                            $_SESSION[$k] = $v;
                        }
                    }elseif($data['status'] == 0){
                        // Pendente
                        $resp['status'] = "failed";
                        $resp['msg'] = "Sua conta está esperando aprovação.";
                    }elseif($data['status'] == 2){
                        // Negado
                        $resp['status'] = "failed";
                        $resp['msg'] = "Acesso negado. Por favor entre em contato com o gerente.";
                    }
                    elseif($data['status'] == 3){
                        //Block
                        $resp['status'] = "failed";
                        $resp['msg'] = "Acesso bloqueado. Por favor entre em contato com o gerente.";
                    }else{
                        $resp['status'] = "failed";
                        $resp['msg'] = "Status Inválido. Por favor entre em contato com o gerente.";
                    }
                   
                }else{
                    // Senha invalida
                    $resp['status'] = "failed";
                    $resp['msg'] = "Usuário ou senha inválidos.";
                }
            }else{
                // Usuario invalido
                $resp['status'] = "failed";
                $resp['msg'] = "Usuário ou senha inválidos.";
            }
        }
        return json_encode($resp);
    }
    function logout(){

        session_destroy();
        header("location:./");
    }
    function register_user(){

        foreach($_POST as $k => $v){
            if(!in_array($k, ['user_id', 'formToken']) && !is_numeric($v) && !is_array($_POST[$k])){
                $_POST[$k] = $this->escapeString($v);
            }
        }

        extract($_POST);
        $allowedToken = $_SESSION['formToken']['registration'];
        if(!isset($formToken) || (isset($formToken) && $formToken != $allowedToken)){

            $resp['status'] = 'failed';
            $resp['msg'] = "Security Check: Form Token is invalid.";
        }else{

            $dbColumn = "(`fullname`, `username`, `password`, `status`, `type`)";
  
            $password = password_hash($password, PASSWORD_DEFAULT);

            $values = "('{$fullname}', '{$username}', '{$password}', 0, 2)";
    
            $sql = "INSERT INTO `user_list` {$dbColumn} VALUES {$values}";

            $insert = $this->query($sql);
            if($insert){

                $resp['status'] = 'success';
                $resp['msg'] = "Sua conta foi criada com sucesso e está em aprovação.";

            }else{

                $resp['status'] = 'failed';
                $resp['msg'] = "Error: ".$this->lastErrorMsg();
            }
        }
        echo json_encode($resp);
    }
    function update_user(){
        
        extract($_POST);
        $allowedToken = $_SESSION['formToken']['manage_user'];
        if(!isset($formToken) || (isset($formToken) && $formToken != $allowedToken)){
            $resp['status'] = 'failed';
            $resp['msg'] = "Security Check: Form Token is invalid.";
        }else{

            $data = "`status` = '{$status}'";
            $data .= ",`type` = '{$type}'";
            

            $sql = "UPDATE `user_list` set {$data} where `user_id` = '{$user_id}'";

            $update = $this->query($sql);
            if($update){

                $resp['status'] = 'success';
                $resp['msg'] = "Usuário foi atualizado com sucesso.";

            }else{

                $resp['status'] = 'failed';
                $resp['msg'] = "Error: ".$this->lastErrorMsg();
            }
        }
        echo json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$LG = new LoginRegistration();
switch($a){
    case 'login':
        echo $LG->login();
    break;
    case 'logout':
        echo $LG->logout();
    break;
    case 'register_user':
        echo $LG->register_user();
    break;
    case 'update_user':
        echo $LG->update_user();
    break;
    default:

    break;
}