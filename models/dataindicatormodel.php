<?php

require_once 'models/indicator1.php';

class DataIndicatorModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataInd1()
    {
        $items = [];

        try {
            $query = $this->db->connectSql()->prepare("
            SELECT DISTINCT
                R5OBJECTS.OBJ_CODE,
                R5OBJECTS.OBJ_MRC,
                R5READINGS.REA_READING,
                R5READINGS.REA_DIFF,
                R5READINGS.REA_DATE,
                MONTH(R5READINGS.REA_DATE) AS month,
                YEAR(R5READINGS.REA_DATE) AS year,
                DATEPART(ISO_WEEK, R5READINGS.REA_DATE) AS week_year,
                DATEPART(WEEK, R5READINGS.REA_DATE) - DATEPART(WEEK, DATEADD(MONTH, DATEDIFF(MONTH, 0, R5READINGS.REA_DATE), 0)) + 1 AS week_month,
                CASE
                    WHEN CONVERT(DATE, R5READINGS.REA_DATE) = CONVERT(DATE,R5EVENTS.EVT_CREATED) THEN 1
                    ELSE 0
                END AS tvar
            FROM
                EAMPRO.dbo.R5OBJECTS
                INNER JOIN R5READINGS ON R5OBJECTS.OBJ_CODE  = R5READINGS.REA_OBJECT
                INNER JOIN R5EVENTS ON R5OBJECTS.OBJ_CODE = R5EVENTS.EVT_OBJECT
            WHERE 
                OBJ_CLASS = 'VEH' AND (R5READINGS.REA_DATE >= DATEADD(week, -12, GETDATE())) AND R5EVENTS.EVT_REQM = 'P-VAR'
                ORDER BY OBJ_CODE ASC, R5READINGS.REA_DATE ASC 
            ");

            $query2 = $this->db->connectdt()->prepare('
                INSERT INTO ind1 (
                    ID_BUS,
                    COMPANY,
                    KM_ACTUAL,
                    KM_DIFF,
                    KM_DIFF_ACT,
                    DATE,
                    MONTH,
                    YEAR,
                    WEEK_YEAR,
                    WEEK_MONTH,
                    TVAR,
                    TVAR_SUM
                ) VALUES (
                    :id_bus,
                    :company,
                    :km_actual,
                    :km_diff,
                    :km_diff_act,
                    :date,
                    :month,
                    :year,
                    :week_year,
                    :week_month,
                    :tvar,
                    :tvar_sum
                )');

            $query4 = $this->db->connectdt()->prepare('
                DELETE FROM 
                    `ind1` 
                ORDER BY `id_ind1` DESC
                LIMIT 1
                ');

            $query3 = $this->db->connectdt()->prepare('
                TRUNCATE TABLE ind1;
                ');

            $start_time = microtime(true);
            $query3->execute([]);
            $query->execute([]);

            $km_diff_sum = 0;
            $tvar_sum = 0;
            $control_month = 0;
            $control_bus = 0;

            $num = 0;

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $OBJ_CODE0 = 0;
            $OBJ_MRC0 = 0;
            $REA_READING0 = 0;
            $REA_DATE0 = '0000-00-00';
            $month0 = 0;
            $year0 = 0;
            $week_year0 = 0;
            $week_month0 = 0;
            $tvar0 = 0;
            $controldate = 0;
            $controlkm = 0;

            $numcontrol = 0;
            $buscontrol = 0;

            // echo "<pre>";
            // print_r($result);
            // echo "</pre>";

            // echo "aqui - ".$result[0]['REA_DATE'];
            
            foreach($result as &$row){

                $row['OBJ_CODE1'] = $OBJ_CODE0;
                $OBJ_CODE0 = $row['OBJ_CODE'];

                $row['OBJ_MRC1'] = $OBJ_MRC0;
                $OBJ_MRC0 = $row['OBJ_MRC'];

                $row['REA_READING1'] = $REA_READING0;
                $REA_READING0 = $row['REA_READING'];

                $row['REA_DATE1'] = $REA_DATE0;
                $REA_DATE0 = $row['REA_DATE'];

                $row['month1'] = $month0;
                $month0 = $row['month'];

                $row['year1'] = $year0;
                $year0 = $row['year'];

                $row['week_year1'] = $week_year0;
                $week_year0 = $row['week_year'];

                $row['week_month1'] = $week_month0;
                $week_month0 = $row['week_month'];

                $row['tvar1'] = $tvar0;
                $tvar0 = $row['tvar'];

                if($control_month != $row['month1'] OR $control_bus != $row['OBJ_CODE1']){
                    $control_month = $row['month1'];
                    $control_bus = $row['OBJ_CODE1'];
                    $km_diff_sum = 0;
                    $tvar_sum = 0;
                    $km_diff_sum = intval($row['REA_DIFF']);
                    $tvar_sum = intval($row['tvar1']);
                }else{
                    $km_diff_sum = $km_diff_sum+intval($row['REA_DIFF']);
                    $tvar_sum = $tvar_sum+intval($row['tvar1']);
                }

                if($controldate == $row['REA_DATE1']){
                    $km_diff_sum = $km_diff_sum-$controlkm;
                    $query4->execute([]);
                }

                if($numcontrol>0){

                    $datetime1 = new DateTime($row['REA_DATE1']);
                    $datetime2 = new DateTime($controldate);
                    $diff = $datetime1->diff($datetime2);
                    $res = $diff->days;
                    // echo $buscontrol." -- ".$row['OBJ_CODE1']."<br>";
                    if($res > 1 AND $buscontrol == $row['OBJ_CODE1']){
                        $datenew = $controldate;
                        for($i = 1; $i<$res; $i++){
                            $datenew = date("Y-m-d",strtotime($datenew."+ 1 days"));
                            $query2->execute([
                                'id_bus'        => $row['OBJ_CODE1'],
                                'company'       => $row['OBJ_MRC1'],
                                'km_actual'     => $row['REA_READING1'],
                                'km_diff'       => 0,
                                'km_diff_act'   => $kmaccontrol,
                                'date'          => $datenew,
                                'month'         => $row['month1'],
                                'year'          => $row['year1'],
                                'week_year'     => $row['week_year1'],
                                'week_month'    => $row['week_month1'],
                                'tvar'          => 0,
                                'tvar_sum'      => $tvraccontrol
                            ]);
                        }
                    }

                }

                $query2->execute([
                    'id_bus'        => $row['OBJ_CODE1'],
                    'company'       => $row['OBJ_MRC1'],
                    'km_actual'     => $row['REA_READING1'],
                    'km_diff'       => $row['REA_DIFF'],
                    'km_diff_act'   => $km_diff_sum,
                    'date'          => $row['REA_DATE1'],
                    'month'         => $row['month1'],
                    'year'          => $row['year1'],
                    'week_year'     => $row['week_year1'],
                    'week_month'    => $row['week_month1'],
                    'tvar'          => $row['tvar1'],
                    'tvar_sum'      => $tvar_sum
                ]);

                $controldate = $row['REA_DATE1'];
                $controlkm = intval($row['REA_DIFF']);
                $buscontrol = $row['OBJ_CODE1'];
                $kmaccontrol = $km_diff_sum;
                $tvraccontrol = $tvar_sum;
                $numcontrol++;

            }

            // while ($row = $query->fetch()) {

            //     // $num++;
            //     // echo "<pre>";
            //     // print_r($row);
            //     // echo "</pre>";

            //     // if($num > 5){
            //     //     break;
            //     // }

            //     // echo $row['OBJ_CODE']."<br>";
            //     // echo $row['REA_DIFF']."<br>";
            //     // echo $row['month']."<br>";
            //     // echo $row['REA_CREATED']."<br>";
            //     // echo $row['year']."<br>";
            //     // echo $row['week_month']."<br>";
            //     // echo $row['tvar']."<br>";

            //     // if($control_month != $row['month'] OR $control_bus != $row['OBJ_CODE']){
            //     //     $control_month = $row['month'];
            //     //     $control_bus = $row['OBJ_CODE'];
            //     //     $km_diff_sum = 0;
            //     //     $tvar_sum = 0;
            //     //     $km_diff_sum = intval($row['REA_DIFF']);
            //     //     $tvar_sum = intval($row['tvar']);
            //     // }else{
            //     //     $km_diff_sum = $km_diff_sum+intval($row['REA_DIFF']);
            //     //     $tvar_sum = $tvar_sum+intval($row['tvar']);
            //     // }

            //     // print_r($km_diff_sum);

            //     // $query2->execute([
            //     //     'id_bus'        => $row['OBJ_CODE'],
            //     //     'company'       => $row['OBJ_MRC'],
            //     //     'km_actual'     => $row['REA_READING'],
            //     //     'km_diff'       => $row['REA_DIFF'],
            //     //     'km_diff_real'       => $row['REA_DIFF'],
            //     //     'km_diff_act'   => $km_diff_sum,
            //     //     'date'          => $row['REA_CREATED'],
            //     //     'month'         => $row['month'],
            //     //     'year'          => $row['year'],
            //     //     'week_year'     => $row['week_year'],
            //     //     'week_month'    => $row['week_month'],
            //     //     'tvar'          => $row['tvar'],
            //     //     'tvar_sum'      => $tvar_sum
            //     // ]);
            // }
            $end_time = microtime(true);
            $duration = $end_time - $start_time;
            return $duration;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function getDataInd2()
    {
        $items = [];

        try {
            $query = $this->db->connectSql()->prepare("
            SELECT DISTINCT
                R5OBJECTS.OBJ_CODE,
                R5OBJECTS.OBJ_MRC,
                R5READINGS.REA_READING,
                R5READINGS.REA_DIFF,
                R5READINGS.REA_DATE,
                MONTH(R5READINGS.REA_DATE) AS month,
                YEAR(R5READINGS.REA_DATE) AS year,
                DATEPART(ISO_WEEK, R5READINGS.REA_DATE) AS week_year,
                DATEPART(WEEK, R5READINGS.REA_DATE) - DATEPART(WEEK, DATEADD(MONTH, DATEDIFF(MONTH, 0, R5READINGS.REA_DATE), 0)) + 1 AS week_month
            FROM
                EAMPRO.dbo.R5OBJECTS
                INNER JOIN R5READINGS ON R5OBJECTS.OBJ_CODE  = R5READINGS.REA_OBJECT
            WHERE 
                OBJ_CLASS = 'VEH' AND (R5READINGS.REA_DATE >= DATEADD(day, -90, GETDATE()))
            ");

            $query2 = $this->db->connectdt()->prepare('
                INSERT INTO ind2 (
                    ID_BUS,
                    COMPANY,
                    KM_ACTUAL,
                    KM_DIFF,
                    DATE,
                    MONTH,
                    YEAR,
                    WEEK_YEAR,
                    WEEK_MONTH
                ) VALUES (
                    :id_bus,
                    :company,
                    :km_actual,
                    :km_diff,
                    :date,
                    :month,
                    :year,
                    :week_year,
                    :week_month
                )');

            $query3 = $this->db->connectdt()->prepare('
                TRUNCATE TABLE ind2;
                ');

            $start_time = microtime(true);
            $query3->execute([]);
            $query->execute([]);

            while ($row = $query->fetch()) {

                $query2->execute([
                    'id_bus'        => $row['OBJ_CODE'],
                    'company'       => $row['OBJ_MRC'],
                    'km_actual'     => $row['REA_READING'],
                    'km_diff'       => $row['REA_DIFF'],
                    'date'          => $row['REA_DATE'],
                    'month'         => $row['month'],
                    'year'          => $row['year'],
                    'week_year'     => $row['week_year'],
                    'week_month'    => $row['week_month']
                ]);
            }
            $end_time = microtime(true);
            $duration = $end_time - $start_time;
            return $duration;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function getDataInd3()
    {
        $items = [];

        try {
            $query = $this->db->connectSql()->prepare("
                SELECT
                    x.EVT_OBJECT,
                    x.EVT_MRC,
                    CASE
                        WHEN x.EVT_STATUS = 'C' THEN 'TERMINADO' 
                        ELSE 'PENDIENTE'
                    END AS STATUS
                FROM
                    EAMPRO.dbo.R5EVENTS x
                WHERE
                    EVT_CODE = :EVT_CODE  
            ");

            $query2 = $this->db->connectdt()->prepare("
                SELECT 
                    `id_maintenance`, 
                    `id_ot` 
                FROM 
                    `scheduled_maintenance` 
                WHERE 
                    `status` != 'TERMINADO'
                ");

            $query4 = $this->db->connectdt()->prepare('
                UPDATE 
                    `scheduled_maintenance` 
                SET  
                    `id_vehiculo` = :id_bus, 
                    `company` = :company,
                    `status` = :id_status
                WHERE 
                    `id_ot` = :id_ot
                ');

            $query3 = $this->db->connectdt()->prepare('
                TRUNCATE TABLE ind3;
                ');

            $start_time = microtime(true);
            $query3->execute([]);
            $query2->execute([]);

            while ($row = $query2->fetch()) {

                $id_ot = $row['id_ot'];
                

                $query->execute([
                    'EVT_CODE'                => $row['id_ot']
                ]);
                $dataot = $query->fetch();
                $id_bus                     = $dataot['EVT_OBJECT'];
                $company                    = $dataot['EVT_MRC'];
                $id_status                  = $dataot['STATUS'];

                $query4->execute([
                    'id_ot'                     => $id_ot,
                    'id_bus'                    => $id_bus,
                    'company'                   => $company,
                    'id_status'                 => $id_status
                ]);


            }
            $end_time = microtime(true);
            $duration = $end_time - $start_time;
            return $duration;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    // public function getDataInd3()
    // {
    //     $items = [];

    //     try {
    //         $query = $this->db->connectSql()->prepare("
    //         SELECT
    //             MONTH(R5EVENTS.EVT_TARGET) AS month,
    //             R5EVENTS.EVT_STATUS, --EMITIDA -> R OR TERMINADA -> C
    //             R5EVENTS.EVT_MRC,
    //             DATEPART(ISO_WEEK, R5EVENTS.EVT_TARGET) AS week,
    //             COUNT(R5EVENTS.EVT_STATUS) AS count_rutina,	
    //             CASE
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%GAS%' THEN 'GAS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%LLA%' THEN 'LLANTAS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CHA%' THEN 'CHASIS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR-3M%' THEN 'DESMANCHE'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR-2M%' THEN 'POLICHADO'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR%' THEN 'CARROCERIA'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%STS%' THEN 'STS'
    //                 ELSE 'OTRO'
    //             END AS RUTINA
    //         FROM
    //             R5EVENTS
    //         WHERE
    //             R5EVENTS.EVT_TYPE  = 'PPM'
    //             AND R5EVENTS.EVT_TARGET >= DATEADD(day, -90, GETDATE())
    //             AND (R5EVENTS.EVT_STATUS = 'C'
    //                 OR R5EVENTS.EVT_STATUS = 'PRO')
    //             AND (R5EVENTS.EVT_PPM LIKE '%GAS%'
    //                 OR R5EVENTS.EVT_PPM LIKE '%LLA%' 
    //                 OR R5EVENTS.EVT_PPM LIKE '%CHA%'
    //                 OR R5EVENTS.EVT_PPM LIKE '%CAR-3M%'
    //                 OR R5EVENTS.EVT_PPM LIKE '%CAR-2M%'
    //                 OR R5EVENTS.EVT_PPM LIKE '%CAR%'
    //                 OR R5EVENTS.EVT_PPM LIKE '%STS%')
    //         GROUP BY
    //             MONTH(R5EVENTS.EVT_TARGET),
    //             R5EVENTS.EVT_STATUS,
    //             R5EVENTS.EVT_MRC,
    //             DATEPART(ISO_WEEK, R5EVENTS.EVT_TARGET),
    //             CASE
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%GAS%' THEN 'GAS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%LLA%' THEN 'LLANTAS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CHA%' THEN 'CHASIS'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR-3M%' THEN 'DESMANCHE'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR-2M%' THEN 'POLICHADO'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%CAR%' THEN 'CARROCERIA'
    //                 WHEN R5EVENTS.EVT_PPM LIKE '%STS%' THEN 'STS'
    //                 ELSE 'OTRO' END
    //         ORDER BY MONTH(R5EVENTS.EVT_TARGET) ASC, RUTINA ASC  
    //         ");

    //         $query2 = $this->db->connectdt()->prepare('
    //             INSERT INTO ind3 (
    //                 RUTINA,
    //                 COMPANY,
    //                 MONTH,
    //                 WEEK,
    //                 STATUS,
    //                 COUNT_RUTINA
    //             ) VALUES (
    //                 :rutina,
    //                 :company,
    //                 :month,
    //                 :week,
    //                 :status,
    //                 :count_rutina
    //             )');

    //         $query3 = $this->db->connectdt()->prepare('
    //             TRUNCATE TABLE ind3;
    //             ');

    //         $start_time = microtime(true);
    //         $query3->execute([]);
    //         $query->execute([]);

    //         while ($row = $query->fetch()) {

    //             $query2->execute([
    //                 'rutina'                => $row['RUTINA'],
    //                 'company'               => $row['EVT_MRC'],
    //                 'month'                 => $row['month'],
    //                 'week'                  => $row['week'],
    //                 'status'                => $row['EVT_STATUS'],
    //                 'count_rutina'          => $row['count_rutina']
    //             ]);
    //         }
    //         $end_time = microtime(true);
    //         $duration = $end_time - $start_time;
    //         return $duration;
    //     } catch (PDOException $e) {
    //         echo $e->getMessage();
    //         // echo "Este documento ya esta registrado";
    //         return false;
    //     }
    // }

    public function getDataInd4()
    {
        $items = [];

        try {
            $query = $this->db->connectSql()->prepare("
            SELECT DISTINCT 
                R5EVENTS.EVT_CODE,
                CASE 
                    WHEN R5EVENTS.EVT_CODE = 1225910 THEN 'JUEGO ÁRBOL PE2 -PRIORIDAD-'
                    ELSE R5EVENTS.EVT_DESC
                END AS EVT_DESC,
                R5EVENTS.EVT_DATE,
                R5EVENTS.EVT_MRC,
                R5EVENTS.EVT_OBJECT,
                R5BOOKEDHOURS.BOO_PERSON,
                R5PERSONNEL.PER_DESC,
                R5BOOKEDHOURS.BOO_HOURS,
                R5ACTIVITIES.ACT_EST
            FROM
                R5EVENTS
                INNER JOIN R5BOOKEDHOURS ON R5EVENTS.EVT_CODE = R5BOOKEDHOURS.BOO_EVENT 
                INNER JOIN R5PERSONNEL ON R5BOOKEDHOURS.BOO_PERSON = R5PERSONNEL.PER_CODE 
                INNER JOIN R5ACTIVITIES ON R5EVENTS.EVT_CODE = R5ACTIVITIES.ACT_EVENT
            WHERE 
                R5EVENTS.EVT_STATUS = 'C' AND (R5EVENTS.EVT_DATE >= DATEADD(day, -90, GETDATE())) AND R5PERSONNEL.PER_DESC NOT LIKE '%Tecnico%'
                ORDER BY R5EVENTS.EVT_CODE ASC 
            ");

            $query7 = $this->db->connectSql()->prepare("
            SELECT
                DISTINCT
                R5BOOKEDHOURS.BOO_PERSON
            FROM
                R5EVENTS
            INNER JOIN R5BOOKEDHOURS ON
                R5EVENTS.EVT_CODE = R5BOOKEDHOURS.BOO_EVENT
            INNER JOIN R5PERSONNEL ON
                R5BOOKEDHOURS.BOO_PERSON = R5PERSONNEL.PER_CODE
            INNER JOIN R5ACTIVITIES ON
                R5EVENTS.EVT_CODE = R5ACTIVITIES.ACT_EVENT
            WHERE
                R5EVENTS.EVT_STATUS = 'C'
                AND (R5EVENTS.EVT_DATE >= DATEADD(day, -90, GETDATE()))
                AND R5PERSONNEL.PER_DESC NOT LIKE '%Tecnico%'
            ");

            $query2 = $this->db->connectdt()->prepare('
                INSERT INTO ind4 (
                    ID_USER,
                    ID_OT,
                    DESCRIPTION,
                    COMPANY,
                    ID_BUS,
                    ID_TECNICO,
                    TECNICO,
                    DATE,
                    HOURS_WORKED,
                    HOURS_ESTIMATED
                ) VALUES (
                    :id_user,
                    :id_ot,
                    :description,
                    :company,
                    :id_bus,
                    :id_tecnico,
                    :tecnico,
                    :date,
                    :hours_worked,
                    :hours_estimated
                )');

            $query3 = $this->db->connectdt()->prepare('
                TRUNCATE TABLE ind4;
                ');

            $query4 = $this->db->connectdt()->prepare('
                TRUNCATE TABLE technicians;
                ');

            $query5 = $this->db->connectdt()->prepare('
                INSERT INTO technicians (
                    ID_EAM
                ) VALUES (
                    :id_user
                )');

            $query6 = $this->db->connectdt()->prepare('
                SELECT 
                    `id_technic` 
                FROM 
                    `technicians` 
                WHERE 
                    `id_eam` = :id_tech
                ');


            $start_time = microtime(true);
            $query3->execute([]);
            $query4->execute([]);
            $query7->execute([]);

            while ($rowt = $query7->fetch()) {

                $query5->execute([
                    'id_user'           => $rowt['BOO_PERSON']
                ]);
                
            }

            $query->execute([]);

            while ($row = $query->fetch()) {

                $id_tech = $row['BOO_PERSON'];
                $query6->execute(['id_tech' => $id_tech]);
                $id_techg = $query6->fetch();
                $id_tech           = $id_techg['id_technic'];

                $query2->execute([
                    'id_user'           => $id_tech,
                    'id_ot'             => $row['EVT_CODE'],
                    'description'       => $row['EVT_DESC'],
                    'company'           => $row['EVT_MRC'],
                    'id_bus'            => $row['EVT_OBJECT'],
                    'id_tecnico'        => $row['BOO_PERSON'],
                    'tecnico'           => $row['PER_DESC'],
                    'date'              => $row['EVT_DATE'],
                    'hours_worked'      => $row['BOO_HOURS'],
                    'hours_estimated'   => $row['ACT_EST']
                ]);
            }
            $end_time = microtime(true);
            $duration = $end_time - $start_time;
            return $duration;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // echo "Este documento ya esta registrado";
            return false;
        }
    }

    public function load($items)
    {
        //print_r($items);
        try {
            $conn = $this->db->connectdt();
            foreach ($items as $row) {

                $payment = new Maintenance();
                $payment = $row;
                $query = $conn->prepare('
                    INSERT INTO scheduled_maintenance 
                    (
                        id_ot, 
                        date_programmed, 
                        rutina, 
                        maintenance, 
                        classification
                    ) 
                    VALUES 
                    (
                        :id_ot, 
                        :date_programmed, 
                        :rutina, 
                        :maintenance, 
                        :classification
                    );
                ');
                $prueba = $query->execute([
                    'id_ot'                 => $payment->id_ot,
                    'date_programmed'       => $payment->date_programmed,
                    'rutina'                => $payment->rutina,
                    'maintenance'           => $payment->maintenance,
                    'classification'        => $payment->classification
                ]);
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            // print_r($e);
            echo ($e);
            return false;
        }
    }

//     SELECT 
// 	`id_ind1`,
//     `id_bus`,
//     `company`,
//     `km_actual`,
//     `km_diff`,
//     `month`,
//     `week_month`,
//     `week_year`,
//     SUM(`tvar`)
// FROM 
// 	`ind1` 
// WHERE 
// 	1
// GROUP BY `month`, `week_month`

    
}
