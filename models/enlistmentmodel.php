<?php


require_once 'models/buses.php';
require_once 'models/enlistmentregister.php';


class EnlistmentModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getRegisters()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`,
                `enlistment`.`id_user`,
                `bus`.`number_bus` AS bus,
                `enlistment`.`date`
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus`  
            WHERE 
                1
            ORDER BY `id_enlistment` DESC
            LIMIT 100
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }
                
                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersCompany($company)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `user`.`name` AS user, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date` 
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `user` ON `enlistment`.`id_user` = `user`.`iduser` 
            WHERE 
                `bus`.`company_idcompany` = $company 
            ORDER BY 
                `id_enlistment` DESC
            LIMIT 10
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }
                
                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDetailRegister($idregister)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`,
                `enlistment_items_data`.`id_data`,
                `enlistment`.`id_user`,
                `enlistment`.`missing`,
                `bus`.`number_bus` AS bus,
                `enlistment`.`date`,
                `enlistment_items`.`description` AS item,
                `enlistment_items_data`.`observation`,
                `enlistment_items_data`.`time`
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus`  
                INNER JOIN `enlistment_items_data` ON `enlistment`.`id_enlistment` = `enlistment_items_data`.`id_enlistment`
                INNER JOIN `enlistment_items` ON `enlistment_items_data`.`id_item` = `enlistment_items`.`id_item`
            WHERE 
                `enlistment`.`id_enlistment` = :idregister
            ");
            $query->execute([
                'idregister'    => $idregister
            ]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_data              = $row['id_data'];
                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }
                
                $item->missing              = $row['missing'];
                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];
                $item->item                 = $row['item'];
                $item->observation          = $row['observation'];
                $item->time                 = $row['time'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDetailOrderDelivery($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `manto_orders`.`id_order`, 
                `id_user_order` AS id_user, 
                `manto_orders`.`num_ot`, 
                `bus`.`number_bus`, 
                `area_maintenance`.`description` AS areades, 
                `manto_title_kit`.`description` AS kit,
                `evidence_1`,
                `novelty`,
                `evidence_2`
            FROM 
                `manto_orders` 
                INNER JOIN `bus` ON `manto_orders`.`idbus` = `bus`.`idbus` 
                INNER JOIN `area_maintenance` ON `manto_orders`.`area` = `area_maintenance`.`id_area` 
                INNER JOIN `manto_title_kit` ON `manto_orders`.`kit_title` = `manto_title_kit`.`id_kit` 
            WHERE 
                `id_order` = :id_order 
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $item->id_order             = $row['id_order'];

                $id_user        = $row['id_user_order'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->id_user_order                    = $position['name']." ".$position['surname'];
                }else{
                    $item->id_user_order = "";
                }

                $item->num_ot               = $row['num_ot'];
                $item->idbus                = $row['number_bus'];
                $item->area                 = $row['areades'];
                $item->kit_title            = $row['kit'];
                $item->evidence_1           = $row['evidence_1'];
                $item->novelty              = $row['novelty'];
                $item->evidence_2           = $row['evidence_2'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersDate($initial_date, $final_date)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date` 
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus`  
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' 
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersDateExp($initial_date, $final_date)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date`,
                `enlistment`.`missing`,
                `enlistment_items`.`description`,
                `enlistment_items_data`.`observation`    
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `enlistment_items_data` ON `enlistment`.`id_enlistment` = `enlistment_items_data`.`id_enlistment`
                INNER JOIN `enlistment_items` ON `enlistment_items_data`.`id_item` = `enlistment_items`.`id_item`
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' 
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new EnlistmentRegister();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];
                $item->missing              = $row['missing'];
                $item->description          = $row['description'];
                $item->observation          = $row['observation'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersDateBus($initial_date, $final_date, $bus)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`,
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date` 
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' 
                AND `bus`.`number_bus` = '$bus'
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersDateBusExp($initial_date, $final_date, $bus)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date`,
                `enlistment`.`missing`,
                `enlistment_items`.`description`,
                `enlistment_items_data`.`observation`    
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `enlistment_items_data` ON `enlistment`.`id_enlistment` = `enlistment_items_data`.`id_enlistment`
                INNER JOIN `enlistment_items` ON `enlistment_items_data`.`id_item` = `enlistment_items`.`id_item`
            WHERE
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' 
                AND `bus`.`number_bus` = '$bus'
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new EnlistmentRegister();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];
                $item->missing              = $row['missing'];
                $item->description          = $row['description'];
                $item->observation          = $row['observation'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersCompanyDate($company, $initial_date, $final_date)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date` 
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' AND
                `bus`.`company_idcompany` = $company 
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }
                
                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersCompanyDateExp($company, $initial_date, $final_date)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `user`.`name` AS user, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date`,
                `enlistment`.`missing`,
                `enlistment_items`.`description`,
                `enlistment_items_data`.`observation`    
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `user` ON `enlistment`.`id_user` = `user`.`iduser` 
                INNER JOIN `enlistment_items_data` ON `enlistment`.`id_enlistment` = `enlistment_items_data`.`id_enlistment`
                INNER JOIN `enlistment_items` ON `enlistment_items_data`.`id_item` = `enlistment_items`.`id_item`
            WHERE
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' AND
                `bus`.`company_idcompany` = $company 
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new EnlistmentRegister();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];
                $item->missing              = $row['missing'];
                $item->description          = $row['description'];
                $item->observation          = $row['observation'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersCompanyDateBus($company, $initial_date, $final_date, $bus)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date` 
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' AND
                `bus`.`company_idcompany` = $company AND `bus`.`number_bus` = '$bus'
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Enlistment();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }
                
                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getRegistersCompanyDateBusExp($company, $initial_date, $final_date, $bus)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `enlistment`.`id_enlistment`, 
                `enlistment`.`id_user`, 
                `bus`.`number_bus` AS bus, 
                `enlistment`.`date`,
                `enlistment`.`missing`,
                `enlistment_items`.`description`,
                `enlistment_items_data`.`observation`    
            FROM 
                `enlistment` 
                INNER JOIN `bus` ON `enlistment`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `enlistment_items_data` ON `enlistment`.`id_enlistment` = `enlistment_items_data`.`id_enlistment`
                INNER JOIN `enlistment_items` ON `enlistment_items_data`.`id_item` = `enlistment_items`.`id_item`
            WHERE 
                `enlistment`.`date` >= '$initial_date' AND `enlistment`.`date` <= '$final_date' AND
                `bus`.`company_idcompany` = $company AND `bus`.`number_bus` = '$bus'
            ORDER BY 
                `id_enlistment` DESC
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new EnlistmentRegister();

                $item->id_enlistment        = $row['id_enlistment'];
                $item->id_user              = $row['id_user'];

                $id_user        = $row['id_user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->user                    = $position['name']." ".$position['surname'];
                }else{
                    $item->user = "";
                }

                $item->bus                  = $row['bus'];
                $item->date                 = $row['date'];
                $item->missing              = $row['missing'];
                $item->description          = $row['description'];
                $item->observation          = $row['observation'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

}
