<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model{

    const SESSION = "User";

    public static function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if (count($results) === 0)
        {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];

        if (password_verify($password, $data["despassword"]) ===  true)
        {

            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;

        } else {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }
    
    }

    public static function verifyLogin($inadmin = true)
    {
        if (
            !isset($_SESSION[User::SESSION])
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0
            ||
            (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ) {
            // header("Location: /admin/login");
            // exit;

            echo"Login nao verificado";
        }
    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }

    public static function listAll()
    {
        $sql = new Sql();

        return $sql->select("SELECT a.*, b.* FROM tb_users AS a INNER JOIN tb_persons AS b ON a.idperson = b.idperson ORDER BY b.desperson");

    }

    public function save()
    {
        echo"aa";

        $sql = new Sql();

        $query = "INSERT INTO tb_users ";
        $query .= "( ";
        $query .= "   idperson, ";
        $query .= "   deslogin, "; 
        $query .= "   despassword, ";
        $query .= "   inadmin ";
        $query .= " )VALUES( ";
        $query .= "   ( ";
        $query .= "     INSERT INTO tb_persons ";
        $query .= "     ( ";
        $query .= "        desperson, ";
        $query .= "        desemail, ";
        $query .= "        nrphone ";
        $query .= "      )VALUES( ";
        $query .= "        :desPerson, ";
        $query .= "        :desPhone, ";
        $query .= "        :desEmail ";
        $query .= "      ) ";
        $query .= "      RETURNING idperson ";
        $query .= "   ), ";
        $query .= "   :desLogin, ";
        $query .= "   :desPassword, ";
        $query .= "   :inAdmin ";
        $query .= " ) ";

        echo"bb";
        
        $sql->query($query, array(
                ":desPerson"=>$this->getdesperson(),
                ":desEmail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":desLogin"=>$this->getdeslogin(),
                ":desPassword"=>$this->getdespassword(),
                ":inAdmin"=>$this->getinadmin() ? 1 : 0
            ));

        echo"cc";
    }

}