<?php


require_once 'models/users.php';


class PasswordModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function newPass($data){
        $query = $this->db->connect()->prepare("
        UPDATE
            `login`
        SET 
            `password` = :password,
            `update_pass` = :update_pass
        WHERE 
            `user_iduser` = :user_iduser
            ");
        try{
            $query->execute([
                'user_iduser'       => $data['user'],
                'password'          => $data['password'],
                'update_pass'       => 1
            ]);
                
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

}


?>