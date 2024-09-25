<?php

require_once 'models/orders.php';
require_once 'models/ots.php';
require_once 'models/sparetparts.php';

class LoginModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function login($data){
        $item = new Login();

        try{
            $query = $this->db->connect()->prepare('
            SELECT 
                `login`.`user_iduser`, 
                `login`.`password`, 
                `user`.`name`, 
                `user`.`rol_idrol`, 
                `rol`.`description` AS rol,
                `user`.`company_idcompany`,
                `login`.`update_pass` 
            FROM 
                login 
                INNER JOIN `user` ON `login`.`user_iduser` = `user`.`iduser`
                INNER JOIN `rol` ON `user`.`rol_idrol` = `rol`.`idrol`
            WHERE 
                login.user_iduser = :iduser
            ');
            $query->execute([
                'iduser' => $data['iduser']
            ]);

            while ($row = $query->fetch()) {
                $item->iduser               = $row['user_iduser'];
                $item->password             = $row['password'];
                $item->name                 = $row['name'];
                $item->rol_idrol            = $row['rol_idrol'];
                $item->rol                  = $row['rol'];
                $item->company_idcompany    = $row['company_idcompany'];
                $item->update_pass          = $row['update_pass'];
            }

            return $item;
        }catch(PDOException $e){
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return null;
        }
    }

    public function listAllOrders()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order`, 
                `id_user_order` AS user, 
                `id_user_delivery`, 
                `num_ot`, 
                `bus`.`number_bus` AS bus, 
                `area_maintenance`.`description` AS area, 
                `manto_title_kit`.`description` AS kit_title, 
                `status`.`description` AS status
            FROM 
                `manto_orders` 
                INNER JOIN `status` ON `manto_orders`.`status` = `status`.`idstatus`
                INNER JOIN `manto_title_kit` ON `manto_orders`.`kit_title` = `manto_title_kit`.`id_kit`
                INNER JOIN `bus` ON `manto_orders`.`idbus` = `bus`.`idbus`
                INNER JOIN `area_maintenance` ON `manto_orders`.`area` = `area_maintenance`.`id_area`
            ORDER BY 
                `id_order` DESC
            ');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $item->id_order             = $row['id_order'];

                $idus = $row['user'];

                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$idus' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->id_user_order                    = $position['name']." ".$position['surname'];
                }

                $id_user_delivery = $row['id_user_delivery'];
                if($id_user_delivery != ""){
                    $query3 = $this->db->connectSqlUnn()->query("
                    SELECT
                        PERSONA.UP2010 + ' ' + PERSONA.UP2009 + ' ' + PERSONA.ZP015 + ' ' + PERSONA.ZP016 AS name
                    FROM
                        PERSONA
                    WHERE
                        PERSONA.ZP011 = '$id_user_delivery' 
                    ");
                    $id_user_delivery = $query3->fetch();
                    $item->id_user_delivery     = $id_user_delivery['name'];
                }else{
                    $item->id_user_delivery     = $id_user_delivery;
                }
                
                

                $item->num_ot               = $row['num_ot'];
                $item->idbus                = $row['bus'];
                $item->area                 = $row['area'];
                $item->kit_title            = $row['kit_title'];
                $item->status               = $row['status'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOrders($data)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order`, 
                `num_ot`, 
                `bus`.`number_bus` AS bus, 
                `area_maintenance`.`description` AS area, 
                `manto_title_kit`.`description` AS kit_title, 
                `status`.`description` AS status
            FROM 
                `manto_orders`  
                INNER JOIN `status` ON `manto_orders`.`status` = `status`.`idstatus`
                INNER JOIN `manto_title_kit` ON `manto_orders`.`kit_title` = `manto_title_kit`.`id_kit`
                INNER JOIN `bus` ON `manto_orders`.`idbus` = `bus`.`idbus`
                INNER JOIN `area_maintenance` ON `manto_orders`.`area` = `area_maintenance`.`id_area`
            WHERE
                `id_user_order` = :iduser
            ORDER BY 
                `id_order` DESC
            ');
            $query->execute([
                'iduser'  => $data['id_user']
            ]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $item->id_order             = $row['id_order'];
                $item->num_ot               = $row['num_ot'];
                $item->idbus                = $row['bus'];
                $item->area                 = $row['area'];
                $item->kit_title            = $row['kit_title'];
                $item->status               = $row['status'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOrdersDelivery($store)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order`, 
                `id_user_order` AS user, 
                `num_ot`, 
                `bus`.`number_bus` AS bus, 
                `area_maintenance`.`description` AS area, 
                `manto_title_kit`.`description` AS kit_title, 
                `status`.`description` AS status
            FROM 
                `manto_orders` 
                INNER JOIN `status` ON `manto_orders`.`status` = `status`.`idstatus`
                INNER JOIN `manto_title_kit` ON `manto_orders`.`kit_title` = `manto_title_kit`.`id_kit`
                INNER JOIN `bus` ON `manto_orders`.`idbus` = `bus`.`idbus`
                INNER JOIN `area_maintenance` ON `manto_orders`.`area` = `area_maintenance`.`id_area`
            WHERE
                `store` = :store
            ORDER BY 
                `id_order` DESC
            ');
            $query->execute([
                'store'  => $store
            ]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $item->id_order             = $row['id_order'];
                $id_user_order              = $row['user'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user_order' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->id_user_order                    = $position['name']." ".$position['surname'];
                }

                $item->num_ot               = $row['num_ot'];
                $item->idbus                = $row['bus'];
                $item->area                 = $row['area'];
                $item->kit_title            = $row['kit_title'];
                $item->status               = $row['status'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDataEam(){
        $items = [];

        try{
            $query = $this->db->connectSql()->prepare("
            SELECT
                PAR_CODE,
                PAR_DESC 
            FROM
                R5PARTS
            WHERE
                PAR_CODE LIKE 'CAR%'
                OR PAR_CODE LIKE 'CHA%'
                OR PAR_CODE LIKE 'DIR%'
                OR PAR_CODE LIKE 'ELE%'
                OR PAR_CODE LIKE 'EST%'
                OR PAR_CODE LIKE 'FER%'
                OR PAR_CODE LIKE 'FRE%'
                OR PAR_CODE LIKE 'GCN%'
                OR PAR_CODE LIKE 'GNC%'
                OR PAR_CODE LIKE 'LLA%'
                OR PAR_CODE LIKE 'LUB%'
                OR PAR_CODE LIKE 'MOT%'
                OR PAR_CODE LIKE 'NEU%'
                OR PAR_CODE LIKE 'SEG%'
                OR PAR_CODE LIKE 'SEÑ%'
                OR PAR_CODE LIKE 'SUS%'
                OR PAR_CODE LIKE 'TRA%'
            ORDER BY PAR_CODE ASC;

            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new SparetParts();

                $item->cod_parts              = $row['PAR_CODE'];
                $item->description            = $row['PAR_DESC'];

                array_push($items, $item);
            }

            return $items;
        }catch(PDOException $e){
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return null;
        }
    } 

    public function listMyOt($data)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ot_missing`,
                `num_ot`,
                `bus`.`number_bus`,
                `proveedores`.`name`,
                `service_supplier`.`name_service`,
                `service_supplier`.`description`,
                `cantidad`,
                `status`.`description` AS status
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus`
                INNER JOIN `proveedores` ON `ot_missing`.`suppliers` = `proveedores`.`id_proveedor`
                INNER JOIN `service_supplier` ON `ot_missing`.`type_service` = `service_supplier`.`id_service`
                INNER JOIN `status` ON `ot_missing`.`status` = `status`.`idstatus`
            WHERE
                `id_user_alm` = :iduser 
            ');
            $query->execute([
                'iduser'  => $data['id_user']
            ]);

            while ($row = $query->fetch()) {
                $item = new Ots();

                $item->id_ot_missing        = $row['id_ot_missing'];
                $item->num_ot               = $row['num_ot'];
                $item->id_bus               = $row['number_bus'];
                $item->suppliers            = $row['name'];
                $item->type_service         = $row['name_service'];
                $item->cantidad             = $row['cantidad'];
                $item->status               = $row['status'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOtComp($data)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ot_missing`, 
                `num_ot`, 
                `bus`.`number_bus`,
                `ordenc`,
                `ot_status`.`description` AS status,
                `ot_missing`.`date_register`
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `ot_status` ON `ot_missing`.`status` = `ot_status`.`id_status` 
            WHERE
                1 
            ORDER BY `id_ot_missing` DESC
            ');
            $query->execute([]);

            $query2 = $this->db->connectSql()->prepare("
            SELECT
                CASE
                    WHEN x.ORD_STATUS = 'CR' THEN 'Completamente recibido'
                    WHEN x.ORD_STATUS = 'C' THEN 'Cancelado'
                    WHEN x.ORD_STATUS = 'RP' THEN 'Parcialmente Recibido'
                    WHEN x.ORD_STATUS = 'AG' THEN 'Compra Aprobada'
                    WHEN x.ORD_STATUS = 'U' THEN 'Incompleto'
                    WHEN x.ORD_STATUS = 'RZ' THEN 'Rechazada'
                    WHEN x.ORD_STATUS = 'AR' THEN 'Requerimiento Aprobado'
                    WHEN x.ORD_STATUS = 'A' THEN 'Aprobado'
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobación'
                END AS status,
                x.ORD_CREATED AS created
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            while ($row = $query->fetch()) {
                $item = new Ots();

                $item->id_ot_missing        = $row['id_ot_missing'];
                $item->num_ot               = $row['num_ot'];
                $item->id_bus               = $row['number_bus'];
                $item->ordenc               = $row['ordenc'];
                $item->created              = $row['date_register'];
                
                $id_ot = $row['ordenc'];
                if($id_ot != ""){
                    $query2->execute(['id_ot' => $id_ot]);
                    $process_breaches = $query2->fetch();
                    $item->status               = $process_breaches['status'];
                    $item->createdoc            = $process_breaches['created'];
                }else{
                    $item->status               = $row['status'];
                    $item->createdoc            = "--";
                }
                
                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOtSol($data)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ot_missing`, 
                `num_ot`, 
                `bus`.`number_bus`,
                `ot_status`.`description` AS status,
                `ot_missing`.`ordenc`,
                `ot_missing`.`date_register`
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `ot_status` ON `ot_missing`.`status` = `ot_status`.`id_status` 
            WHERE 
                `id_user_alm` =  :iduser
            ORDER BY `id_ot_missing` DESC 
            ');

            $query2 = $this->db->connectSql()->prepare("
            SELECT
                CASE
                    WHEN x.ORD_STATUS = 'CR' THEN 'Completamente recibido'
                    WHEN x.ORD_STATUS = 'C' THEN 'Cancelado'
                    WHEN x.ORD_STATUS = 'RP' THEN 'Parcialmente Recibido'
                    WHEN x.ORD_STATUS = 'AG' THEN 'Compra Aprobada'
                    WHEN x.ORD_STATUS = 'U' THEN 'Incompleto'
                    WHEN x.ORD_STATUS = 'RZ' THEN 'Rechazada'
                    WHEN x.ORD_STATUS = 'AR' THEN 'Requerimiento Aprobado'
                    WHEN x.ORD_STATUS = 'A' THEN 'Aprobado'
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobación'
                END AS status,
                x.ORD_CREATED AS created
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            $query->execute([
                'iduser'  => $data['iduser']
            ]);

            while ($row = $query->fetch()) {
                $item = new Ots();

                $item->id_ot_missing        = $row['id_ot_missing'];
                $item->num_ot               = $row['num_ot'];
                $item->id_bus               = $row['number_bus'];
                $item->status               = $row['status'];
                $item->ordenc               = $row['ordenc'];
                $item->created              = $row['date_register'];

                $id_ot = $row['ordenc'];
                if($id_ot != ""){
                    $query2->execute(['id_ot' => $id_ot]);
                    $process_breaches = $query2->fetch();
                    $item->status               = $process_breaches['status'];
                    $item->createdoc            = $process_breaches['created'];
                }else{
                    $item->status               = $row['status'];
                    $item->createdoc            = "--";
                }

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

}
