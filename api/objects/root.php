<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 4:50 PM
 */

class Root
{
    private $conn;
    private $table_name = 'roots';

    public $id;
    public $event_id;
    public $visitor_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public static function toArray($stmt, &$visitors_arr)
    {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {

            extract($row);

            $visitor_item = array(
                "id" => $id,
                "event_id" => $event_id,
                "visitor_id" => $visitor_id,
            );
            //var_dump($visitor_item);


            array_push($visitors_arr["records"], $visitor_item);
        }
    }

    public function readOne()
    {
        $query = "
        SELECT * FROM $this->table_name WHERE id=:id LIMIT 0,1;
        ";
        //OPEN
        //SHORTEST
        //PATH
        //FIRST

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($row);


        $this->event_id = $row['event_id'];
        $this->visitor_id = $row['visitor_id'];

        //var_dump($this);
    }

    public function create()
    {
        $query = "
            INSERT INTO $this->table_name 
            SET 
                event_id=:event_id, 
                visitor_id=:visitor_id;
        ";

        $stmt = $this->conn->prepare($query);

        $this->event_id = htmlspecialchars(strip_tags($this->event_id));
        $this->visitor_id = htmlspecialchars(strip_tags($this->visitor_id));


        //var_dump($this);

        $stmt->bindParam(":visitor_id", $this->visitor_id);
        $stmt->bindParam(":event_id", $this->event_id);


        //var_dump($stmt);


        //return false;

        if($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function read($stand_id, $visitor_id)
    {
        /*
        $query = "
        SELECT r.id, r.event_id, r.visitor_id FROM $this->table_name r
        ";

        if ($stand_id != null)
        {
            $query.="JOIN timetable t ON t.id = r.event_id ";
            $query.="WHERE t.stand_id=:stand_id ";
        }
        if ($visitor_id !=null)
        {
            $query.= "AND r.visitor_id=:visitor_id ";
        }
    */
        $query =
            "
            SELECT r.id, r.event_id, r.visitor_id FROM roots r
              JOIN timetable t ON t.id = r.event_id 
            WHERE (t.stand_id=$stand_id AND r.visitor_id=$visitor_id)
            ";

     //   $stmt = $this->conn->prepare($query);
       // var_dump($query);
      //  var_dump($stand_id);
      //  var_dump($visitor_id);
        //$stmt->bindParam(':stand_id', $stand_id);
        //$stmt->bindParam(':visitor_id', $visitor_id);

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function delete()
    {
        $query = "
            DELETE FROM $this->table_name WHERE id =:id;
        ";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

}