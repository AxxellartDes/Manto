<?php


require_once 'models/buses.php';
require_once 'models/maintenance.php';
require_once 'models/areamaintenance.php';
require_once 'models/itemsmaintenance.php';
require_once 'models/titleitems.php';
require_once 'models/orders.php';
require_once 'models/items.php';
require_once 'models/supplier.php';
require_once 'models/ots.php';


class OtModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function listBus()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM bus ORDER BY number_bus ASC');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Buses();

                $item->idbus            = $row['idbus'];
                $item->number_bus       = $row['number_bus'];
                $item->bus_type         = $row['bus_type_idbus_type'];
                $item->vehicle_plate    = $row['vehicle_plate'];
                $item->company          = $row['company_idcompany'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function kitMaintenance()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM manto_title_kit');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new TitleItems();

                $item->id_kit            = $row['id_kit'];
                $item->description       = $row['description'];
                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }
    

    public function getDataOtJson($id_ot)
    {
        try {
            $query = $this->db->connectSql()->prepare("
            SELECT
                R5EVENTS.EVT_DESC AS description,
                R5EVENTS.EVT_OBJECT AS bus
            FROM
                R5EVENTS
            WHERE
                EVT_CODE = :id_ot
            ");

            $query->execute(['id_ot' => $id_ot]);
            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function verifyDataOcJson($id_oc, $id_ot)
    {
        try {
            $query = $this->db->connectSql()->prepare("
            SELECT
                COUNT(x.ORL_ORDER)
            FROM
                EAMPRO.dbo.R5ORDERLINES x
                INNER JOIN R5ORDERS ON x.ORL_ORDER = R5ORDERS.ORD_CODE
            WHERE
                R5ORDERS.ORD_DESC LIKE '%$id_ot%'
                AND x.ORL_ORDER = '$id_oc'
            ");

            $query->execute([
            ]);
            $data = 0;
            foreach ($query as $row) {
                $data = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function getDataOcJson($id_ot)
    {
        try {
            $query = $this->db->connectSql()->prepare("
            SELECT
                x.ORD_CODE AS code,
                x.ORD_DESC AS description,
                x.ORD_PRICE AS price,
                x.ORD_CREATED AS created,
                x.ORD_STATUS AS idstatus,
                CASE
                    WHEN x.ORD_STATUS = 'CR' THEN 'Completamente recibido'
                    WHEN x.ORD_STATUS = 'C' THEN 'Cancelado'
                    WHEN x.ORD_STATUS = 'RP' THEN 'Parcialmente Recibido'
                    WHEN x.ORD_STATUS = 'AG' THEN 'Compra Aprobada'
                    WHEN x.ORD_STATUS = 'U' THEN 'Incompleto'
                    WHEN x.ORD_STATUS = 'RZ' THEN 'Rechazada'
                    WHEN x.ORD_STATUS = 'AR' THEN 'Requerimiento Aprobado'
                    WHEN x.ORD_STATUS = 'A' THEN 'Aprobado'
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
                    ELSE 'OTRO'
                END AS status
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  $id_ot
            ");

            $query->execute([
            ]);
            $data = [];
            foreach ($query as $row) {
                $data = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listKitJson($id_title)
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `manto_kit_relation_items`.`id_kit_item`, 
                `manto_kit_relation_items`.`id_title_kit`, 
                `manto_kit_relation_items`.`id_items`,
                `items_maintenance`.`id_items` AS iditem,
                `items_maintenance`.`description`, 
                `manto_kit_relation_items`.`referencia_scania`, 
                `manto_kit_relation_items`.`cantidad`, 
                `manto_kit_relation_items`.`type_bus` 
            FROM 
                `manto_kit_relation_items` 
                INNER JOIN `items_maintenance` ON `manto_kit_relation_items`.`id_items` = `items_maintenance`.`cod_items` 
            WHERE 
                `id_title_kit` = :id_title
            ');
            $query->execute(['id_title' => $id_title]);
            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listKitPedidoJson($id_title)
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `manto_kit_relation_pedido`.`id_kit_item`, 
                `manto_kit_relation_pedido`.`id_title_kit`, 
                `manto_kit_relation_pedido`.`id_items`, 
                `items_maintenance`.`id_items` AS iditem,
                `items_maintenance`.`description`, 
                `manto_kit_relation_pedido`.`referencia_scania`, 
                `manto_kit_relation_pedido`.`cantidad`, 
                `manto_kit_relation_pedido`.`type_bus` 
            FROM 
                `manto_kit_relation_pedido` 
                INNER JOIN `items_maintenance` ON `manto_kit_relation_pedido`.`id_items` = `items_maintenance`.`cod_items` 
            WHERE 
                `id_title_kit` = :id_title
            ');
            $query->execute(['id_title' => $id_title]);
            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listBusByCompany($company)
    {
        $items = [];

        try {

            $query = $this->db->connect()->prepare(
                'SELECT * FROM bus WHERE company_idcompany = :company_idcompany ORDER BY number_bus ASC'
            );
            $query->execute([
                'company_idcompany' => $company
            ]);

            while ($row = $query->fetch()) {
                $item = new Buses();

                $item->idbus            = $row['idbus'];
                $item->number_bus       = $row['number_bus'];
                $item->bus_type         = $row['bus_type_idbus_type'];
                $item->vehicle_plate    = $row['vehicle_plate'];
                $item->company          = $row['company_idcompany'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listSupplier()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_proveedor`,
                `idusers`,
                `name`,
                `email`
            FROM 
                `proveedores` 
            WHERE 
                1
            ');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Supplier();

                $item->id_proveedor         = $row['id_proveedor'];
                $item->idusers              = $row['idusers'];
                $item->name                 = $row['name'];
                $item->email                = $row['email'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function areaMaintenance()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM area_maintenance ORDER BY id_area ASC');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new AreaMaintenance();

                $item->id_area                  = $row['id_area'];
                $item->description              = $row['description'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function itemsMaintenance()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM items_maintenance ORDER BY id_items ASC');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new itemsMaintenance();

                $item->id_items             = $row['id_items'];
                $item->cod_items            = $row['cod_items'];
                $item->description          = $row['description'];
                $item->amount               = $row['amount'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function typeBus($ideventclass)
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT `bus_type_idbus_type` AS typebus FROM `bus` WHERE `idbus` = :idbus;
            ');
            $query->execute([
                'idbus' => $ideventclass 
            ]);
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listtypeserviceJson($id_supplier)
    {
        try {

            if($id_supplier == 900353873){

                $query = $this->db->connectSql()->prepare("
                SELECT TOP 100
                    x.CAT_PART as id_service,
                    x.CAT_SUPPLIER as supplier,
                    x.CAT_REF + ' / ' + x.CAT_PART + ' ' + R5PARTS.PAR_DESC as name_service,
                    'N/A' as description
                FROM
                    EAMPRO.dbo.R5CATALOGUE x
                INNER JOIN R5PARTS ON
                    X.CAT_PART = R5PARTS.PAR_CODE
                WHERE
                    x.CAT_SUPPLIER = '900353873'
                    AND x.CAT_REF != 'NULL'
                ORDER BY
                    x.CAT_REF ASC
                "); 

            }else{

                $query = $this->db->connectSql()->prepare("
                SELECT
                    x.SCA_TASK as id_service,
                    x.SCA_SUPPLIER as supplier,
                    x.SCA_TASK + ' ' + R5TASKS.TSK_DESC as name_service,
                    'N/A' as description
                FROM
                    EAMPRO.dbo.R5SERVICECATALOGUE x
                    INNER JOIN R5TASKS ON x.SCA_TASK = R5TASKS.TSK_CODE
                WHERE
                    SCA_SUPPLIER = '$id_supplier' AND X.SCA_LASTSAVED != ''
                ");
            }

            
            $query->execute([
            ]);

            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data); 
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function typeserviceJson($id_type)
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_service`,
                `description`
            FROM 
                `service_supplier` 
            WHERE 
                `id_service` = :id_type
            ');
            
            $query->execute([
                'id_type' => $id_type
            ]);

            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listKitTitleBiArtJson()
    {
        try {
            $query = $this->db->connect()->prepare('SELECT * FROM manto_title_kit');
            
            $query->execute([]);

            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function saveOt($data)
    {

        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO ot_missing (
                    NUM_OT,
                    ID_USER_ALM,
                    ID_BUS,
                    SUPPLIERS,
                    COMPANY,
                    DATE_REGISTER,
                    STATUS
                ) VALUES (
                    :num_ot,
                    :id_user_alm,
                    :idbus,
                    :suppliers,
                    :company,
                    :date_register,
                    :status
                )');
            $query->execute([
                    'num_ot'            => $data['num_ot'],
                    'id_user_alm'       => $data['id_user_alm'],
                    'idbus'             => $data['idbus'],
                    'suppliers'         => $data['suppliers'],
                    'company'           => $data['company'],
                    'date_register'     => $data['date_register'],
                    'status'            => $data['status']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function getLastOtId()
    {
        try {
            $query = $this->db->connect()->prepare(
                'SELECT MAX(id_ot_missing) FROM ot_missing;'
            );
            $query->execute([]);
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function saveServiceOt($data)
    {
        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO ot_data_service (
                    ID_OT_MISSING,
                    ID_TYPE_SERVICE,
                    CANTIDAD
                ) VALUES (
                    :id_ot_missing,
                    :id_type_service,
                    :cantidad
                )');
            $query->execute([
                    'id_ot_missing'         => $data['id_ot_missing'],
                    'id_type_service'       => $data['id_type_service'],
                    'cantidad'              => $data['cantidad']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function saveOtComp($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            UPDATE 
                `ot_missing` 
            SET
                `ordenc`        = :ordenc, 
                `desoc`         = :desoc, 
                `valor`         = :valor, 
                `status`        = :status,
                `created`       = :created,
                `id_user_comp`  = :id_user
            WHERE 
                `id_ot_missing` = :id_ot_m
            ');
            $query->execute([
                    'ordenc'            => $data['ordenc'],
                    'desoc'             => $data['desoc'],
                    'valor'             => $data['valor'],
                    'status'            => $data['status'],
                    'id_ot_m'           => $data['id_ot_m'],
                    'created'           => $data['created'],
                    'id_user'           => $data['id_user']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function saveItemOrder($data)
    {

        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO manto_orders_items (
                    ID_ORDER,
                    ID_ITEM,
                    CANTIDAD,
                    TYPE_ORDER
                ) VALUES (
                    :id_order,
                    :id_item,
                    :cantidad,
                    :type_order
                )');
            $query->execute([
                    'id_order'          => $data['id_order'],
                    'id_item'           => $data['id_item'],
                    'cantidad'          => $data['cantidad'],
                    'type_order'        => $data['type_order']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function getLastOrderId()
    {
        try {
            $query = $this->db->connect()->prepare(
                'SELECT MAX(id_order) FROM manto_orders;'
            );
            $query->execute([]);
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
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
                `status`.`description` AS status 
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus`  
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

    public function getLastbusId($idbus)
    {
        try {
            $query = $this->db->connect()->prepare("
            SELECT 
                `idbus` 
            FROM 
                `bus` 
            WHERE 
                `number_bus` = :idbus
            ");
            $query->execute([
                'idbus' => $idbus 
            ]);
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listMyOtAll()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ot_missing`, 
                `num_ot`, 
                `bus`.`number_bus`, 
                `proveedores`.`name`,  
                `status`.`description` AS status 
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus` 
                INNER JOIN `proveedores` ON `ot_missing`.`suppliers` = `proveedores`.`id_proveedor` 
                INNER JOIN `status` ON `ot_missing`.`status` = `status`.`idstatus` 
            ');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Ots();

                $item->id_ot_missing        = $row['id_ot_missing'];
                $item->num_ot               = $row['num_ot'];
                $item->id_bus               = $row['number_bus'];
                $item->suppliers            = $row['name'];
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

    public function listAllOrders()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order`, 
                `user`.`name` AS user, 
                `id_user_delivery`, 
                `num_ot`, 
                `bus`.`number_bus` AS bus, 
                `area_maintenance`.`description` AS area, 
                `manto_title_kit`.`description` AS kit_title, 
                `status`.`description` AS status
            FROM 
                `manto_orders` 
                INNER JOIN `user` ON `manto_orders`.`id_user_order` = `user`.`iduser` 
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
                $item->id_user_order        = $row['user'];

                $id_user_delivery = $row['id_user_delivery'];
                if($id_user_delivery != ""){
                    $query2 = $this->db->connect()->query("
                    SELECT 
                        `name`
                    FROM 
                        `user` 
                    WHERE 
                        `iduser` = $id_user_delivery 
                    ");
                    $id_user_delivery = $query2->fetch();
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

    public function getDetailOt($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ot_missing`,
                `num_ot`,
                `id_user_alm`,
                `bus`.`number_bus`,
                `suppliers`,
                `proveedores`.`name`,
                `date_register`,
                `ordenc`,
                `desoc`,
                `created`,
                `valor`,
                `ot_missing`.`status`,
                `ot_status`.`description` AS statusdes
            FROM 
                `ot_missing` 
                INNER JOIN `bus` ON `ot_missing`.`id_bus` = `bus`.`idbus`
                INNER JOIN `proveedores` ON `ot_missing`.`suppliers` = `proveedores`.`idusers`
                INNER JOIN `ot_status` ON `ot_missing`.`status` = `ot_status`.`id_status`
            WHERE 
                `id_ot_missing` = :id_order 
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

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
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
                END AS status
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            while ($row = $query->fetch()) {
                $item = new Ots();

                $item->id_ot_missing            = $row['id_ot_missing'];
                $item->num_ot                   = $row['num_ot'];
                $item->id_user_alm              = $row['id_user_alm'];
                $item->id_bus                   = $row['number_bus'];
                $item->suppliersid              = $row['suppliers'];
                $item->suppliers                = $row['name'];
                $item->date_register            = $row['date_register'];
                $item->ordenc                   = $row['ordenc'];

                $id_ot = $row['ordenc'];
                if($id_ot != ""){
                    $query2->execute(['id_ot' => $id_ot]);
                    $process_breaches = $query2->fetch();
                    $item->statusdes             = $process_breaches['status'];
                }else{
                    $item->statusdes             = $row['statusdes'];
                }

                $item->status                   = $row['status'];
                $item->desoc                    = $row['desoc'];
                $item->created                  = $row['created'];
                $item->valor                    = $row['valor'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDetailServiceOt($data)
    {
        $items = [];

        $idot   = $data['idorder'];
        $idsupp = $data['idsupp'];

        try {
            

            if($idsupp == 900353873){
                
                $query = $this->db->connect()->prepare("
                SELECT 
                    `id_type_service`,
                    `cantidad`
                FROM 
                    `ot_data_service` 
                WHERE 
                    `id_ot_missing` = $idot
                ");
                
                $query->execute([
                ]);

                while ($row = $query->fetch()) {
                    $item = new Ots();
                    
                    $type_service         = $row['id_type_service'];
                    $query2 = $this->db->connectSql()->query("
                    SELECT
                        x.CAT_REF + ' / ' + x.CAT_PART + ' ' + R5PARTS.PAR_DESC as name_service
                    FROM
                        EAMPRO.dbo.R5CATALOGUE x
                        INNER JOIN R5PARTS ON X.CAT_PART = R5PARTS.PAR_CODE 
                    WHERE
                        x.CAT_PART = '$type_service' AND x.CAT_REF != 'NULL'
                    ");
                    $type_service = $query2->fetch();
                    $item->type_service                   = $type_service['name_service'];

                    $item->description          = "N/A";
                    $item->cantidad             = $row['cantidad'];
                    array_push($items, $item);
                }

            }else{
    
                $query = $this->db->connect()->prepare("
                SELECT 
                    `id_type_service`,
                    `cantidad`
                FROM 
                    `ot_data_service` 
                WHERE 
                    `id_ot_missing` = $idot
                ");
                
                $query->execute([
                ]);

                while ($row = $query->fetch()) {
                    $item = new Ots();
                    
                    $type_service         = $row['id_type_service'];
                    $query2 = $this->db->connectSql()->query("
                    SELECT
                        x.SCA_TASK + ' ' + R5TASKS.TSK_DESC as name_service
                    FROM
                        EAMPRO.dbo.R5SERVICECATALOGUE x
                        INNER JOIN R5TASKS ON x.SCA_TASK = R5TASKS.TSK_CODE
                    WHERE
                        x.SCA_TASK = '$type_service' 
                    ");
                    $type_service = $query2->fetch();
                    $item->type_service                   = $type_service['name_service'];

                    $item->description          = "N/A";
                    $item->cantidad             = $row['cantidad'];
                    array_push($items, $item);
                }

            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
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
                `user`.`name` AS id_user, 
                `manto_orders`.`num_ot`, 
                `bus`.`number_bus`, 
                `area_maintenance`.`description` AS areades, 
                `manto_title_kit`.`description` AS kit,
                `evidence_1`,
                `novelty`,
                `evidence_2`
            FROM 
                `manto_orders` 
                INNER JOIN `user` ON `manto_orders`.`id_user_order` = `user`.`iduser` 
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
                $item->id_user_order        = $row['id_user'];
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

    public function getDetailOrderElements1($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order_item`,
                `id_order`,
                `items_maintenance`.`cod_items`,
                `items_maintenance`.`description`,
                `cantidad`
            FROM 
                `manto_orders_items` 
                INNER JOIN `items_maintenance` ON `manto_orders_items`.`id_item` = `items_maintenance`.`id_items`
            WHERE 
                `id_order` = :id_order AND `manto_orders_items`.`type_order` = 1 
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Items();

                $item->id_order_item        = $row['id_order_item'];
                $item->id_order             = $row['id_order'];
                $item->cod_items            = $row['cod_items'];
                $item->description          = $row['description'];
                $item->cantidad             = $row['cantidad'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDetailOrderElements2($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order_item`,
                `id_order`,
                `items_maintenance`.`cod_items`,
                `items_maintenance`.`description`,
                `cantidad`
            FROM 
                `manto_orders_items` 
                INNER JOIN `items_maintenance` ON `manto_orders_items`.`id_item` = `items_maintenance`.`id_items`
            WHERE 
                `id_order` = :id_order AND `manto_orders_items`.`type_order` = 2 
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Items();

                $item->id_order_item        = $row['id_order_item'];
                $item->id_order             = $row['id_order'];
                $item->cod_items            = $row['cod_items'];
                $item->description          = $row['description'];
                $item->cantidad             = $row['cantidad'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getDetailOrderElements3($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_order_item`,
                `id_order`,
                `items_maintenance`.`cod_items`,
                `items_maintenance`.`description`,
                `cantidad`
            FROM 
                `manto_orders_items` 
                INNER JOIN `items_maintenance` ON `manto_orders_items`.`id_item` = `items_maintenance`.`id_items`
            WHERE 
                `id_order` = :id_order AND `manto_orders_items`.`type_order` = 3 
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Items();

                $item->id_order_item        = $row['id_order_item'];
                $item->id_order             = $row['id_order'];
                $item->cod_items            = $row['cod_items'];
                $item->description          = $row['description'];
                $item->cantidad             = $row['cantidad'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getUserDelivery($idorder)
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `user`.`name`
            FROM 
                `manto_orders` 
                INNER JOIN `user` ON `manto_orders`.`id_user_delivery` = `user`.`iduser`
            WHERE 
                `id_order` = :id_order
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $item->id_user_delivery        = $row['name'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function updateDelivery($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            UPDATE 
                `manto_orders` 
            SET  
                `id_user_delivery` = :id_user,
                `date_delivery` = :date, 
                `novelty` = :novelty, 
                `status` = :status,
                `evidence_1` = :evidence
            WHERE 
                `id_order` = :idorder
                ');
            $query->execute([
                    'idorder'       => $data['idorder'],
                    'date'          => $data['date'],
                    'novelty'       => $data['novelty'],
                    'id_user'       => $data['id_user'],
                    'evidence'      => $data['evidence'],
                    'status'        => $data['status']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function updateDeliveryOld($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            UPDATE 
                `manto_orders` 
            SET  
                `status` = :status,
                `evidence_2` = :evidence
            WHERE 
                `id_order` = :idorder
                ');
            $query->execute([
                    'idorder'       => $data['idorder'],
                    'evidence'      => $data['evidence'],
                    'status'        => $data['status']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listItemsSearchJson($itemkey)
    {
        try {
            $query = $this->db->connect()->query('
            SELECT 
                `id_items`,
                `cod_items`,
                `description`
            FROM 
                `items_maintenance` 
            WHERE 
                `description` LIKE "%'.$itemkey.'%"
            ');
            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data);
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
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
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
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
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
                END AS status,
                x.ORD_CREATED AS created
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            $query->execute([
                'iduser'  => $data['id_user']
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

    public function listMyOtCompFil($data)
    {
        $items = [];

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
            `date_register` >= :date_initial AND `date_register` <= :date_final
        ');

        try {
            
            $query->execute([
                'date_initial'      => $data['date_initial'].' 00:00:00',
                'date_final'        => $data['date_final'].' 23:59:59'
            ]);

            $query2 = $this->db->connectSql()->prepare("
            SELECT
                x.ORD_STATUS,
                CASE
                    WHEN x.ORD_STATUS = 'CR' THEN 'Completamente recibido'
                    WHEN x.ORD_STATUS = 'C' THEN 'Cancelado'
                    WHEN x.ORD_STATUS = 'RP' THEN 'Parcialmente Recibido'
                    WHEN x.ORD_STATUS = 'AG' THEN 'Compra Aprobada'
                    WHEN x.ORD_STATUS = 'U' THEN 'Incompleto'
                    WHEN x.ORD_STATUS = 'RZ' THEN 'Rechazada'
                    WHEN x.ORD_STATUS = 'AR' THEN 'Requerimiento Aprobado'
                    WHEN x.ORD_STATUS = 'A' THEN 'Aprobado'
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
                END AS status,
                x.ORD_CREATED AS created
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            while ($row = $query->fetch()) {
                $item = new Ots();

                if($data['id_status'] == 1){
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
                }elseif($data['id_status'] == 4 AND $row['ordenc'] == ""){

                    $item->id_ot_missing        = $row['id_ot_missing'];
                    $item->num_ot               = $row['num_ot'];
                    $item->id_bus               = $row['number_bus'];
                    $item->ordenc               = $row['ordenc'];
                    $item->created              = $row['date_register'];
                    $item->status               = $row['status'];
                    $item->createdoc            = "--";
                    
                    array_push($items, $item);
                }elseif($row['ordenc'] != ""){

                    $id_ot = $row['ordenc'];
                    $query2->execute(['id_ot' => $id_ot]);
                    $process_breaches = $query2->fetch();

                    if($data['id_status'] == $process_breaches['ORD_STATUS']){

                        $item->id_ot_missing        = $row['id_ot_missing'];
                        $item->num_ot               = $row['num_ot'];
                        $item->id_bus               = $row['number_bus'];
                        $item->ordenc               = $row['ordenc'];
                        $item->created              = $row['date_register'];
                        $item->status               = $process_breaches['status'];
                        $item->createdoc            = $process_breaches['created'];
                        
                        array_push($items, $item);
                    }
                }

                
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOtMantFil($data)
    {
        $items = [];

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
            (`date_register` >= :date_initial AND `date_register` <= :date_final) AND `company` = :company
        ');

        try {
            
            $query->execute([
                'date_initial'      => $data['date_initial'],
                'date_final'        => $data['date_final'],
                'company'           => $data['company']
            ]);

            $query2 = $this->db->connectSql()->prepare("
            SELECT
                x.ORD_STATUS,
                CASE
                    WHEN x.ORD_STATUS = 'CR' THEN 'Completamente recibido'
                    WHEN x.ORD_STATUS = 'C' THEN 'Cancelado'
                    WHEN x.ORD_STATUS = 'RP' THEN 'Parcialmente Recibido'
                    WHEN x.ORD_STATUS = 'AG' THEN 'Compra Aprobada'
                    WHEN x.ORD_STATUS = 'U' THEN 'Incompleto'
                    WHEN x.ORD_STATUS = 'RZ' THEN 'Rechazada'
                    WHEN x.ORD_STATUS = 'AR' THEN 'Requerimiento Aprobado'
                    WHEN x.ORD_STATUS = 'A' THEN 'Aprobado'
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
                END AS status,
                x.ORD_CREATED AS created
            FROM
                EAMPRO.dbo.R5ORDERS x
            WHERE 
                x.ORD_CODE =  :id_ot
            ");

            while ($row = $query->fetch()) {
                $item = new Ots();

                if($data['id_status'] == 1){
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
                }elseif($data['id_status'] == 4 AND $row['ordenc'] == ""){

                    $item->id_ot_missing        = $row['id_ot_missing'];
                    $item->num_ot               = $row['num_ot'];
                    $item->id_bus               = $row['number_bus'];
                    $item->ordenc               = $row['ordenc'];
                    $item->created              = $row['date_register'];
                    $item->status               = $row['status'];
                    $item->createdoc            = "--";
                    
                    array_push($items, $item);
                }elseif($row['ordenc'] != ""){

                    $id_ot = $row['ordenc'];
                    $query2->execute(['id_ot' => $id_ot]);
                    $process_breaches = $query2->fetch();

                    if($data['id_status'] == $process_breaches['ORD_STATUS']){

                        $item->id_ot_missing        = $row['id_ot_missing'];
                        $item->num_ot               = $row['num_ot'];
                        $item->id_bus               = $row['number_bus'];
                        $item->ordenc               = $row['ordenc'];
                        $item->created              = $row['date_register'];
                        $item->status               = $process_breaches['status'];
                        $item->createdoc            = $process_breaches['created'];
                        
                        array_push($items, $item);
                    }
                }

                
            }

            return $items;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listMyOtCompFilTemp($data)
    {
        $items = [];

        try {
            $query = $this->db->connect()->query('
                SELECT * FROM `ot_temp`
            ');

            $items = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return [];
        }
    }

    public function saveOtTemp($data)
    {

        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO ot_temp (
                    ID_OT_MISSING,
                    NUM_OT,
                    ID_BUS,
                    ORDENC,
                    CREATED,
                    STATUS,
                    CREATEDOC
                ) VALUES (
                    :id_ot_missing,
                    :num_ot,
                    :id_bus,
                    :ordenc,
                    :created,
                    :status,
                    :createdoc
                )');

            $query->execute([
                    'id_ot_missing'         => $data['id_ot_missing'],
                    'num_ot'                => $data['num_ot'],
                    'id_bus'                => $data['id_bus'],
                    'ordenc'                => $data['ordenc'],
                    'created'               => $data['created'],
                    'status'                => $data['status'],
                    'createdoc'             => $data['createdoc']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function deleteOtTemp(){
        $query = $this->db->connect()->query('
        TRUNCATE `ot_temp`');
    }

    public function listServiceSearchJson($text, $id_supplier)
    {
        try {

            if($id_supplier == 900353873){

                $query = $this->db->connectSql()->prepare("
                SELECT
                    x.CAT_PART as id_service,
                    x.CAT_REF + ' / ' + x.CAT_PART + ' ' + R5PARTS.PAR_DESC as name_service
                FROM
                    EAMPRO.dbo.R5CATALOGUE x
                INNER JOIN R5PARTS ON
                    X.CAT_PART = R5PARTS.PAR_CODE
                WHERE
                    x.CAT_REF LIKE '%$text%' OR x.CAT_PART LIKE '%$text%'
                "); 

            }else{

                $query = $this->db->connectSql()->prepare("
                SELECT
                    x.SCA_TASK as id_service,
                    x.SCA_TASK + ' ' + R5TASKS.TSK_DESC as name_service
                FROM
                    EAMPRO.dbo.R5SERVICECATALOGUE x
                INNER JOIN R5TASKS ON
                    x.SCA_TASK = R5TASKS.TSK_CODE
                WHERE
                    x.SCA_TASK LIKE '%$text%' AND x.SCA_SUPPLIER = '$id_supplier'
                ");
            }

            
            $query->execute([
            ]);

            $data = array();
            foreach ($query as $row) {
                $data[] = $row;
            }
            return json_encode($data); 
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }
}
