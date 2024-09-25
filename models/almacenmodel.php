<?php


require_once 'models/buses.php';
require_once 'models/maintenance.php';
require_once 'models/areamaintenance.php';
require_once 'models/itemsmaintenance.php';
require_once 'models/titleitems.php';
require_once 'models/orders.php';
require_once 'models/items.php';
require_once 'models/ots.php';


class AlmacenModel extends Model
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

    public function listKitJson($id_title)
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `manto_kit_relation_items`.`id_kit_item`, 
                `manto_kit_relation_items`.`id_title_kit`, 
                `manto_kit_relation_items`.`id_items`, 
                `manto_kit_relation_items`.`referencia_scania`, 
                `manto_kit_relation_items`.`cantidad`, 
                `manto_kit_relation_items`.`type_bus` 
            FROM 
                `manto_kit_relation_items` 
            WHERE 
                `id_title_kit` = :id_title
            ');
            $query->execute(['id_title' => $id_title]);
            $data = array();
            foreach ($query as $row) {

                $cod_items = $row['id_items'];
                $query2 = $this->db->connectSql()->prepare("
                    SELECT 
                        PAR_DESC
                    FROM 
                        R5PARTS
                    WHERE 
                        PAR_CODE = :cod_items; 
                    ");

                $query2->execute(['cod_items' => $cod_items]);
                $description = $query2->fetch();
                $row['description']             = $description['PAR_DESC'];

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
                `manto_kit_relation_pedido`.`referencia_scania`, 
                `manto_kit_relation_pedido`.`cantidad`, 
                `manto_kit_relation_pedido`.`type_bus` 
            FROM 
                `manto_kit_relation_pedido` 
            WHERE 
                `id_title_kit` = :id_title
            ');
            $query->execute(['id_title' => $id_title]);
            $data = array();
            foreach ($query as $row) {

                $cod_items = $row['id_items'];
                $query2 = $this->db->connectSql()->prepare("
                    SELECT 
                        PAR_DESC
                    FROM 
                        R5PARTS
                    WHERE 
                        PAR_CODE = :cod_items; 
                    ");

                $query2->execute(['cod_items' => $cod_items]);
                $description = $query2->fetch();
                $row['description']             = $description['PAR_DESC'];
                
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

    public function listMaintenance()
    {
        $items = [];

        try {
            $query = $this->db->connect()->prepare('SELECT * FROM type_maintenance ORDER BY id_maintenance ASC');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Maintenance();

                $item->id_maintenance           = $row['id_maintenance'];
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
            $query = $this->db->connectSql()->prepare("
            SELECT
                R5PARTS.PAR_CODE,
                R5PARTS.PAR_DESC,
                R5STOCK.STO_FORINV,
                R5STOCK.STO_STORE 
            FROM
                R5PARTS
                INNER JOIN R5STOCK ON R5PARTS.PAR_CODE = R5STOCK.STO_PART 
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
            ORDER BY PAR_CODE ASC 
                ;
            ");
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new itemsMaintenance();

                $item->cod_items            = $row['PAR_CODE'];
                $item->description          = $row['PAR_DESC'];
                $item->amount               = $row['STO_FORINV'];
                $item->store                = $row['STO_STORE'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function itemsMaintenanceOLD()
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

    public function typeBus($type_bus)
    {
        try {
            $query = $this->db->connect()->prepare("
            SELECT `bus_type_idbus_type` AS typebus FROM `bus` WHERE `number_bus` = ':idbus';
            ");
            $query->execute([
                'idbus' => $type_bus 
            ]);
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listKitTitleArtJson()
    {
        try {
            $query = $this->db->connect()->prepare('
            SELECT * FROM `manto_title_kit` WHERE `bus_type` = 1
            ');
            
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

    public function getDataOtJson($id_ot)
    {
        try {
            $query = $this->db->connectSql()->prepare("
            SELECT
                R5EVENTS.EVT_DESC AS description,
                R5EVENTS.EVT_OBJECT AS bus,
                R5EVENTS.EVT_REQM AS event_req,
                R5ACTIVITIES.ACT_TRADE AS type_req
            FROM
                R5EVENTS
                INNER JOIN R5ACTIVITIES ON R5EVENTS.EVT_CODE = R5ACTIVITIES.ACT_EVENT 
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

    public function saveOrder($data)
    {

        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO manto_orders (
                    NUM_OT,
                    ID_USER_ORDER,
                    IDBUS,
                    AREA,
                    KIT_TITLE,
                    COMPANY,
                    STORE,
                    STATUS
                ) VALUES (
                    :num_ot,
                    :id_user_order,
                    :idbus,
                    :area,
                    :kit_title,
                    :company,
                    :store,
                    :status
                )');
            $query->execute([
                    'num_ot'            => $data['num_ot'],
                    'id_user_order'     => $data['id_user_order'],
                    'idbus'             => $data['idbus'],
                    'area'              => $data['area'],
                    'kit_title'         => $data['kit_title'],
                    'company'           => $data['company'],
                    'store'             => $data['store'],
                    'status'            => $data['status']
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
                INNER JOIN `user` ON `manto_orders`.`id_user_order` = `user`.`iduser` 
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

    public function getDetailOrder($idorder)
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
                `manto_title_kit`.`description` AS kit
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

                $id_user_order        = $row['id_user'];
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
                $item->idbus                = $row['number_bus'];
                $item->area                 = $row['areades'];
                $item->kit_title            = $row['kit'];

                array_push($items, $item);
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
                `id_user_order` AS id_user, 
                `manto_orders`.`num_ot`, 
                `bus`.`number_bus`, 
                `area_maintenance`.`description` AS areades, 
                `manto_title_kit`.`description` AS kit,
                `evidence_1`,
                `novelty`,
                `evidence_2`,
                `confirm_a`,
                `confirm_t`
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

                $id_user_order        = $row['id_user'];
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
                $item->idbus                = $row['number_bus'];
                $item->area                 = $row['areades'];
                $item->kit_title            = $row['kit'];
                $item->evidence_1           = $row['evidence_1'];
                $item->novelty              = $row['novelty'];
                $item->evidence_2           = $row['evidence_2'];
                $item->confirm_a            = $row['confirm_a'];
                $item->confirm_t            = $row['confirm_t'];

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
                `id_item`,
                `cantidad`
            FROM 
                `manto_orders_items` 
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
                $item->cod_items            = $row['id_item'];

                $cod_items = $row['id_item'];
                $query2 = $this->db->connectSql()->prepare("
                    SELECT 
                        PAR_DESC
                    FROM 
                        R5PARTS
                    WHERE 
                        PAR_CODE = :cod_items; 
                    ");

                $query2->execute(['cod_items' => $cod_items]);
                $description = $query2->fetch();
                $item->description             = $description['PAR_DESC'];

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
                `id_item`,
                `cantidad`
            FROM 
                `manto_orders_items` 
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
                $item->cod_items            = $row['id_item'];

                $cod_items = $row['id_item'];
                $query2 = $this->db->connectSql()->prepare("
                    SELECT 
                        PAR_DESC
                    FROM 
                        R5PARTS
                    WHERE 
                        PAR_CODE = :cod_items; 
                    ");

                $query2->execute(['cod_items' => $cod_items]);
                $description = $query2->fetch();
                $item->description             = $description['PAR_DESC'];

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
                `id_item`,
                `cantidad`
            FROM 
                `manto_orders_items` 
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
                $item->cod_items            = $row['id_item'];
                
                $cod_items = $row['id_item'];
                $query2 = $this->db->connectSql()->prepare("
                    SELECT 
                        PAR_DESC
                    FROM 
                        R5PARTS
                    WHERE 
                        PAR_CODE = :cod_items; 
                    ");

                $query2->execute(['cod_items' => $cod_items]);
                $description = $query2->fetch();
                if(isset($description['PAR_DESC']) AND $description['PAR_DESC'] != ""){
                    $item->description            = $description['PAR_DESC'];
                }else{
                    $item->description            = "";
                }
                
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
                `id_user_delivery`
            FROM 
                `manto_orders` 
            WHERE 
                `id_order` = :id_order
            ');
            $query->execute([
                'id_order'          => $idorder
            ]);

            while ($row = $query->fetch()) {
                $item = new Orders();

                $id_user_delivery        = $row['id_user_delivery'];
                $query2 = $this->db->connectSqlUnn()->query("
                SELECT
                    PERSONA.UP2010 + ' ' + PERSONA.UP2009 AS name,
                    PERSONA.ZP015 + ' ' + PERSONA.ZP016  AS surname
                FROM
                    PERSONA
                WHERE
                    PERSONA.ZP011 = '$id_user_delivery' 
                ");
                $position = $query2->fetch();
                if(isset($position['name']) AND $position['name'] != "" AND $position['name'] != NULL){
                    $item->id_user_delivery                    = $position['name']." ".$position['surname'];
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
                `evidence_1` = :evidence,
                `confirm_a` = :confirm
            WHERE 
                `id_order` = :idorder
                ');
            $query->execute([
                    'idorder'       => $data['idorder'],
                    'date'          => $data['date'],
                    'novelty'       => $data['novelty'],
                    'id_user'       => $data['id_user'],
                    'evidence'      => $data['evidence'],
                    'status'        => $data['status'],
                    'confirm'       => $data['confirm']
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
                `evidence_2` = :evidence,
                `confirm_t` = :confirm
            WHERE 
                `id_order` = :idorder
                ');
            $query->execute([
                    'idorder'       => $data['idorder'],
                    'evidence'      => $data['evidence'],
                    'status'        => $data['status'],
                    'confirm'       => $data['confirm']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function listItemsSearchJson($itemkey, $store)
    {
        try {
            $query = $this->db->connectSql()->prepare("
            SELECT
                R5PARTS.PAR_CODE AS id_items,
                R5PARTS.PAR_CODE AS cod_items,
                R5PARTS.PAR_DESC AS description,
                R5STOCK.STO_FORINV AS stock,
                R5STOCK.STO_STORE AS store
            FROM
                R5PARTS
            INNER JOIN R5STOCK ON R5PARTS.PAR_CODE = R5STOCK.STO_PART AND R5STOCK.STO_STORE LIKE '".$store."%'
            WHERE
                R5STOCK.STO_FORINV > 0
                AND 
                (PAR_CODE LIKE 'CAR%'
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
                    OR PAR_CODE LIKE 'TRA%')
                AND 
                    (R5PARTS.PAR_DESC LIKE '%".$itemkey."%')
            ORDER BY
                PAR_CODE ASC 
                            ;
            ");
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

    public function actList($itemkey)
    {
        try {
            // $query = $this->db->connect()->query('
            // SELECT 
            //     `id_items`,
            //     `cod_items`,
            //     `description`
            // FROM 
            //     `items_maintenance` 
            // WHERE 
            //     `description` LIKE "%'.$itemkey.'%"
            // ');
            $query = $this->db->connectSql()->prepare("
            SELECT
                R5PARTS.PAR_CODE AS id_items,
                R5PARTS.PAR_CODE AS cod_items,
                R5PARTS.PAR_DESC AS description
            FROM
                R5PARTS
            WHERE
                (PAR_CODE LIKE 'CAR%'
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
                    OR PAR_CODE LIKE 'TRA%')
                AND 
                    (R5PARTS.PAR_DESC LIKE '%ESP%')
            ORDER BY
                PAR_CODE ASC 
                ;
            ");
            $query->execute([
                'itemkey'       => $data['itemkey']
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
                    WHEN x.ORD_STATUS = 'R' THEN 'Esperando Aprobacion'
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
