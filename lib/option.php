<?php
include_once("../conn/conn.php");

Class OptionMaker {
    public static function Populate($level, $type) {
        global $pdo; // Make sure $pdo is accessible
        $jsonData = json_encode(array(
            "data" => $level
        ));

        try {
            // Prepare and execute the stored procedure
            $stmt = $pdo->prepare("CALL selectprogramenroll(:param)");
            $stmt->bindParam(':param', $jsonData);
            $stmt->execute();

            $selectedItem = null;
            if (isset($_SESSION["student_information"])) {
                // $selectedItem = $_SESSION["student_information"][$type] ?? null; 
                $selectedItem = json_decode($_SESSION["student_information"], true); 
            }
            // echo $selectedItem[0]["course"];
            // Fetch the result from the stored procedure
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = json_decode($result['Result'], true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    // Access and use the abbrv and value fields
                    $value = $item['value'];
                    $description = $item['description']; // Also accessing description
                    $isSelected = (strtoupper($selectedItem[0][$type]) === strtoupper($description)) ? 'selected' : '';
                    // echo "<option value='$value' $isSelected>".strtoupper($description)."</option>";
                    echo "<option value='$value' $isSelected>".strtoupper($description)."</option>";
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public static function SpecialPopulate($spname, $data, $value, $description, $key) {
        global $pdo;
        $jsonData = json_encode(array(
            "data" => $data
        ));
        try {
            $stmt = $pdo->prepare("CALL $spname(:param)");
            $stmt->bindParam(':param', $jsonData);
            $stmt->execute();

            $selectedItem = null;
            if (isset($_SESSION["student_information"])) {
                $selectedItem = json_decode($_SESSION["student_information"], true); 

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $data = json_decode($result['Result'], true);
                if (is_array($data)) {
                    foreach ($data as $item) {
                        $isSelected = (strtoupper($selectedItem[0][$key]) === strtoupper($item[$description])) ? 'selected' : '';
                        echo "<option value='$item[$value]' $isSelected>".strtoupper($item[$description])."</option>";
                    }
                }
            } else {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $data = json_decode($result['Result'], true);
                if (is_array($data)) {
                    foreach ($data as $item) {
                        echo "<option value='$item[$value]'>".strtoupper($item[$description])."</option>";
                    }
                }
            }

            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function CustomPopulate($spname, $data, $value, $description, $key) {
        global $pdo;
        $jsonData = json_encode(array(
            "data" => $data
        ));
        try {
            $stmt = $pdo->prepare("CALL $spname(:param)");
            $stmt->bindParam(':param', $jsonData);
            $stmt->execute();

            $selectedItem = null;
            if (isset($_SESSION["student_information"])) {
                $selectedItem = json_decode($_SESSION["student_information"], true); 
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = json_decode($result['Result'], true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    $isSelected = (strtoupper($selectedItem[0][$key]) === strtoupper($description)) ? 'selected' : '';
                    echo "<option value='$item[$value]' $isSelected>".strtoupper($item[$description])."</option>";
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public static function DefaultPopulate($sp, $dat, $value, $dss) {
    global $pdo;
    $jsonStringData = json_encode(array(
        "data" => $dat
    ));
    try {
        $stmt = $pdo->prepare("CALL $sp(:param)");
        $stmt->bindParam(':param', $jsonStringData);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = json_decode($result['Result'], true);
        if (is_array($data)) {
            foreach ($data as $item) {
                echo "<option value='$item[$value]'>".strtoupper($item[$dss])."</option>";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
}
?>