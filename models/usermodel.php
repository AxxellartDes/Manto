<?php


require_once 'models/users.php';
require_once 'models/login.php';
require_once 'models/roles.php';
require_once 'models/companies.php';
// require_once 'models/childrens.php';


class UserModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            INSERT INTO `user`(
                `iduser`,
                `name`,
                `phone`,
                `email`,
                `age`,
                `rol_idrol`,
                `company_idcompany`
            )
            VALUES(
                :iduser,
                :name,
                :phone,
                :email,
                :age,
                :rol_idrol,
                :company_idcompany
            )
            ');
            $query->execute([
                'iduser'            => $data['iduser'],
                'name'              => $data['name'],
                'phone'             => $data['phone'],
                'email'             => $data['email'],
                'age'               => $data['age'],
                'rol_idrol'         => $data['rol'],
                'company_idcompany' => $data['company']
            ]);

            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function saveChildren($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            INSERT INTO `children`(
                `name`,
                `age`,
                `user_iduser`,
                `status_idstatus`
            )
            VALUES(
                :name,
                :age,
                :user_iduser,
                :status_idstatus
            )
            ');
            $query->execute([
                'name'              => $data['name'],
                'age'               => $data['age'],
                'user_iduser'       => $data['user_iduser'],
                'status_idstatus'   => $data['status_idstatus']
            ]);

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function createLogin($data)
    {

        try {
            $query = $this->db->connect()->prepare('INSERT INTO login (USER_IDUSER, PASSWORD) VALUES (:user_iduser, :password)');
            $query->execute([

                'user_iduser'   => $data['user_iduser'],
                'password'      => $data['password']
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listRoles()
    {
        $items = [];

        try {
            $query = $this->db->connect()->query("SELECT idrol, description, comment FROM rol");

            while ($row = $query->fetch()) {
                $item = new Roles();

                $item->idrol        = $row['idrol'];
                $item->description  = $row['description'];
                $item->comment      = $row['comment'];

                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function list()
    {
        $items = [];

        try {
            $query = $this->db->connect()->query("
            SELECT
                `user`.iduser,
                `user`.name,
                `user`.phone,
                `user`.email,
                rol.description AS rol,
                company.description AS company
            FROM
                `user`
            INNER JOIN rol ON `user`.rol_idrol = rol.idrol
            INNER JOIN company ON `user`.company_idcompany = company.idcompany
            ORDER BY
                `user`.iduser ASC;
            ");

            while ($row = $query->fetch()) {
                $item = new Users();

                $item->iduser   = $row['iduser'];
                $item->name     = $row['name'];
                $item->phone    = $row['phone'];
                $item->email    = $row['email'];
                $item->company  = $row['company'];
                $item->rol      = $row['rol'];

                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function searchUsersById($id)
    {
        $items = [];

        $query = $this->db->connect()->prepare("
        SELECT
            `user`.iduser,
            `user`.name,
            `user`.phone,
            `user`.email,
            rol.description AS rol,
            company.description AS company
        FROM
            `user`
        INNER JOIN rol ON `user`.rol_idrol = rol.idrol
        INNER JOIN company ON `user`.company_idcompany = company.idcompany
        WHERE `user`.`iduser` = :iduser
        ORDER BY
            `user`.iduser ASC;
        ");
        try {
            $query->execute(['iduser' => $id]);

            while ($row = $query->fetch()) {
                $item = new Users();

                $item->iduser   = $row['iduser'];
                $item->name     = $row['name'];
                $item->phone    = $row['phone'];
                $item->email    = $row['email'];
                $item->company  = $row['company'];
                $item->rol      = $row['rol'];

                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listChildrens()
    {
        $items = [];

        try {
            $query = $this->db->connect()->query("
            SELECT
                `idchildren`,
                `children`.`name`,
                `age`,
                `user_iduser`,
                `gift_idgift`,
                `gift`.`name` AS `giftname`
            FROM
                `children`
            LEFT JOIN `gift` ON `children`.`gift_idgift` = `gift`.`idgift`
            ORDER BY
                `idchildren` ASC;
            ");

            while ($row = $query->fetch()) {
                $item = new Childrens();

                $item->idchildren   = $row['idchildren'];
                $item->name         = $row['name'];
                $item->user_iduser  = $row['user_iduser'];
                $item->age          = $row['age'];
                $item->gift_name    = $row['giftname'];
                $item->gift_idgift  = $row['gift_idgift'];

                array_push($items, $item);
            }
            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listCompanies()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM company');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Companies();

                $item->idcompany    = $row['idcompany'];
                $item->description  = $row['description'];
                $item->comment      = $row['comment'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function searchCompaniesById($id)
    {

        $items = [];

        $query = $this->db->connect()->prepare("
        SELECT
            *
        FROM
            company
        WHERE
            `description` = :idcompany
        ");

        try {

            $query->execute(['idcompany' => $id]);

            while ($row = $query->fetch()) {
                $item = new Companies();

                $item->idcompany    = $row['idcompany'];
                $item->description  = $row['description'];
                $item->comment      = $row['comment'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function searchChildrensById($id)
    {

        $items = [];

        $query = $this->db->connect()->prepare("
        SELECT
            `idchildren`,
            `children`.`name`,
            `age`,
            `user_iduser`,
            `gift_idgift`,
            `gift`.`name` AS `gift`
        FROM
            `children`
        LEFT JOIN `gift` ON `children`.`gift_idgift` = `gift`.`idgift`
        WHERE `user_iduser` = :iduser
        ORDER BY
            `idchildren` ASC;
        ");

        try {

            $query->execute(['iduser' => $id]);

            while ($row = $query->fetch()) {
                $item = new Childrens();

                $item->idchildren   = $row['idchildren'];
                $item->name         = $row['name'];
                $item->user_iduser  = $row['user_iduser'];
                $item->age          = $row['age'];
                $item->gift_name    = $row['gift'];
                $item->gift_idgift  = $row['gift_idgift'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getById($id)
    {
        $item = new User();

        $query = $this->db->connect()->prepare("SELECT * FROM user WHERE iduser = :iduser");

        try {
            $query->execute(['iduser' => $id]);

            while ($row = $query->fetch()) {
                $item->iduser               = $row['iduser'];
                $item->code                 = $row['code'];
                $item->name                 = $row['name'];
                $item->surname              = $row['surname'];
                $item->phone                = $row['phone'];
                $item->email                = $row['email'];
                $item->rol_idrol            = $row['rol_idrol'];
                $item->company_idcompany    = $row['company_idcompany'];
            }

            return $item;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function update($data)
    {
        $query = $this->db->connect()->prepare("
        UPDATE
            `user`
        SET
            `code`                  = :code, 
            `name`                  = :name, 
            `surname`               = :surname, 
            `phone`                 = :phone, 
            `email`                 = :email, 
            `rol_idrol`             = :rol_idrol, 
            `company_idcompany`     = :company_idcompany
        WHERE 
            `iduser`                = :iduser
            ");
        try {
            $query->execute([
                'iduser'            => $data['iduser'],
                'code'              => $data['code'],
                'name'              => $data['name'],
                'surname'           => $data['surname'],
                'phone'             => $data['phone'],
                'email'             => $data['email'],
                'rol_idrol'         => $data['rol'],
                'company_idcompany' => $data['company']

            ]);

            return true;
        } catch (PDOException $e) {
            // echo ("entro aqui");
            // echo $e->getMessage();
            // print_r($e);
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function delete($id)
    {

        $query = $this->db->connect()->prepare("DELETE FROM user WHERE iduser = :iduser");

        try {
            $query->execute(['iduser' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function restore($data)
    {
        try {
            $query = $this->db->connect()->prepare('UPDATE login SET password = :password WHERE user_iduser = :user_iduser');
            $query->execute([

                'user_iduser'   => $data['iduser'],
                'password'      => $data['password']
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function restoreGift($data)
    {
        
        
        try {
            $query = $this->db->connect()->prepare('
            UPDATE
                `children`
            SET
                `gift_idgift` = NULL,
                `status_idstatus` = 1
            WHERE
                `children`.`idchildren` = :idchildren
            ');
            $query->execute([
                'idchildren'   => $data['idchildren']
            ]);

            $query2 = $this->db->connect()->prepare("
                UPDATE 
                    `gift`
                SET 
                    quantity = quantity+1
                WHERE 
                    `idgift` = :idgift;
                ");
                
                $query2->execute([
                'idgift'       => $data['gift_idgift']
                ]);

            return true;

        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function search($id)
    {
        $items = [];

        $query = $this->db->connect()->prepare("
        SELECT * FROM user INNER JOIN roles ON user.rol = roles.idrol
         WHERE iduser = :id
         ");

        try {
            $query->execute(['id' => $id]);

            while ($row = $query->fetch()) {
                $item = new Users();

                $item->iduser       = $row['iduser'];
                $item->name         = $row['name'];
                $item->rol          = $row['description'];
                $item->email        = $row['email'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function createMasiveLogin($items)
    {
        try {
            $conn = $this->db->connect();
            foreach ($items as $row) {

                $user = new Users();
                $user = $row;
                $query = $conn->prepare('
                INSERT INTO login 
                (`user_iduser`, `password`) 
                VALUES 
                (:user_iduser, :password)
                ON DUPLICATE KEY UPDATE user_iduser = :user_iduser_1
                ');
                $query->execute([

                    'user_iduser'   => $user->idusers,
                    'user_iduser_1' => $user->idusers,
                    'password'      => $user->password

                ]);
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo $e;
            // print_r($e);

            return false;
        }
    }
}
