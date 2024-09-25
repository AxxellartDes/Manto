<?php

require_once 'models/buses.php';
require_once 'models/maintenance.php';
require_once 'models/areamaintenance.php';
require_once 'models/itemsmaintenance.php';
require_once 'models/titleitems.php';
require_once 'models/orders.php';
require_once 'models/items.php';
require_once 'models/ots.php';


class TaskModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function idOcupation($id_user)
    {

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `id_type_position` 
            FROM 
                `technical_position` 
            WHERE 
                `id_user` = :id_user
            ');
            $query->execute([
                'id_user' => $id_user
            ]);

            while ($row = $query->fetch()) {

                $item           = $row['id_type_position'];
            }

            return $item;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
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

    public function listTaskType()
    {
        $items = [];

        try {

            $query = $this->db->connect()->prepare('
            SELECT 
                `id_ocupation`,
                `description`
            FROM 
                `task_ocupation` 
            WHERE 
                1
            ');
            $query->execute([]);

            while ($row = $query->fetch()) {
                $item = new Task();

                $item->id_type_task         = $row['id_ocupation'];
                $item->description          = $row['description'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listTasks($id_ocupation)
    {
        $items = [];

        try {

            $query = $this->db->connect()->prepare('
            SELECT 
                `task_plan`,
                `description`,
                `num_technicals`,
                `hours_estimated`
            FROM 
                `task` 
            WHERE 
                `ocupation` = :id_ocupation
            ');
            $query->execute([
                'id_ocupation' => $id_ocupation
            ]);

            while ($row = $query->fetch()) {
                $item = new Task();

                $item->task_plan            = $row['task_plan'];
                $item->description          = $row['description'];
                $item->num_technicals       = $row['num_technicals'];
                $item->hours_estimated      = $row['hours_estimated'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function listTaskSearchJson($taskkey)
    {
        try {
            $query = $this->db->connect()->query('
            SELECT 
                `id_task`, 
                `task_plan`, 
                `description`, 
                `hours_estimated` 
            FROM 
                `task` 
            WHERE 
                (
                    `description` LIKE "%'.$taskkey.'%"
                ) 
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

    public function taskSave($data)
    {

        try {
            $query = $this->db->connect()->prepare('
                INSERT INTO task_register (
                    ID_USER,
                    DATE_INITIAL,
                    COMPANY,
                    ID_TASK,
                    ID_BUS,
                    TYPE_TASK
                ) VALUES (
                    :id_user,
                    :date_initial,
                    :company,
                    :id_task,
                    :id_bus,
                    :type_task
                )');
            $query->execute([
                    'id_user'               => $data['id_user'],
                    'date_initial'          => $data['date_initial'],
                    'company'               => $data['company'],
                    'id_task'               => $data['id_task'],
                    'id_bus'                => $data['bus'],
                    'type_task'             => $data['type_task']
            ]);
            
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    } 

    public function myRegistersOn($id_user)
    {
        $items = [];

        try {

            $query = $this->db->connect()->prepare('
            SELECT 
                `task_register`.`id_register`, 
                `task_register`.`id_task`, 
                `task`.`description`, 
                `task_register`.`date_initial`,
                `bus`.`number_bus`,
                `task_ocupation`.`description` AS ocupation
            FROM 
                `task_register` 
                INNER JOIN `task` ON `task_register`.`id_task` = `task`.`id_task`
                INNER JOIN `bus` ON `task_register`.`id_bus` = `bus`.`idbus`
                INNER JOIN `task_ocupation` ON `task_register`.`type_task` = `task_ocupation`.`id_ocupation`
            WHERE  
                `task_register`.`id_user` = :id_user
                AND `task_register`.`status` = 1
            ');
            $query->execute([
                'id_user' => $id_user
            ]);

            while ($row = $query->fetch()) {
                $item = new Task();

                $item->id_register          = $row['id_register'];
                $item->id_task              = $row['id_task'];
                $item->description          = $row['description'];
                $item->date_initial         = $row['date_initial'];
                $item->id_type_task         = $row['ocupation'];
                $item->id_bus               = $row['number_bus'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function myRegistersOff($id_user)
    {
        $items = [];

        try {

            $query = $this->db->connect()->prepare('
            SELECT 
                `task_register`.`id_register`, 
                `task_register`.`id_task`,
                `task`.`description`,
                `task_register`.`date_initial`,
                `task_register`.`date_final`,
                TIMEDIFF(`task_register`.`date_final`,`task_register`.`date_initial`) AS time,
                `bus`.`number_bus`,
                `task_ocupation`.`description` AS ocupation
            FROM 
                `task_register` 
                INNER JOIN `task` ON `task_register`.`id_task` = `task`.`id_task`
                INNER JOIN `bus` ON `task_register`.`id_bus` = `bus`.`idbus`
                INNER JOIN `task_ocupation` ON `task_register`.`type_task` = `task_ocupation`.`id_ocupation`
            WHERE 
                `task_register`.`id_user` = :id_user
                AND `task_register`.`status` = 2
            ');
            $query->execute([
                'id_user' => $id_user
            ]);

            while ($row = $query->fetch()) {
                $item = new Task();

                $item->id_register          = $row['id_register'];
                $item->id_task              = $row['id_task'];
                $item->description          = $row['description'];
                $item->date_initial         = $row['date_initial'];
                $item->date_final           = $row['date_final'];
                $item->time                 = $row['time'];
                $item->id_type_task         = $row['ocupation'];
                $item->id_bus               = $row['number_bus'];

                array_push($items, $item);
            }

            return $items;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function taskEnd($data)
    {

        try {
            $query = $this->db->connect()->prepare('
            UPDATE 
                `task_register` 
            SET 
                `date_final` = :date_final,
                `status` = 2
            WHERE 
                `id_register` = :id_register 
            ');

            $query->execute([
                'id_register'   => $data['id_register'],
                'date_final'    => $data['date_final']
            ]);

            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function getDateInitial($id_register)
    {

        try {
            $query = $this->db->connect()->prepare('
            SELECT 
                `date_initial`
            FROM 
                `task_register` 
            WHERE 
                `id_register` = :id_register
            ');
            $query->execute([
                'id_register' => $id_register
            ]);

            while ($row = $query->fetch()) {

                $item           = $row['date_initial'];
            }

            return $item;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return [];
        }
    }

    public function getTaskType()
    {
        try {
            $query = $this->db->connect()->query(
                "SELECT `ocupation` FROM `task` WHERE `task_plan` = '$task_plan'"
            );
            $result = $query->fetchColumn();
            return $result;
        } catch (PDOException $e) {
            // echo "Este documento ya esta registrado";
            return false;
        }
    }
}
