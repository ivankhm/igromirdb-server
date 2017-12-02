<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 12/1/2017
 * Time: 2:29 PM
 */

class StandEvent
{
    private $conn;
    private $table_name = 'timetable';

    public $id;
    public $title;
    public $description;
    public $event_time;
    public $stand_id;

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
                "title" => $title,
                "description" => $description,
                "event_time" => $event_time,
                "stand_id" => $stand_id,
            );
            //var_dump($visitor_item);


            array_push($visitors_arr["records"], $visitor_item);
        }
    }

    public function readOne()
    {
        $query = "
        SELECT t.id, dp.title, dp.description, t.event_time, t.stand_id  FROM 
	        $this->table_name t
        JOIN discr_pairs dp 
    	  ON dp.id = t.discr_pair_id
        WHERE t.id=:sid
          LIMIT 0,1
        ";
        //OPEN
        //SHORTEST
        //PATH
        //FIRST

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':sid', $this->id);

        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //var_dump($row);


        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->event_time = $row['event_time'];
        $this->stand_id = $row['stand_id'];
        //var_dump($this);
    }

    public function readStandEvents($standId)
    {
        $query = "
        SELECT t.id, dp.title, dp.description, t.event_time, t.stand_id  FROM 
	        $this->table_name t
        JOIN discr_pairs dp 
    	  ON dp.id = t.discr_pair_id
        WHERE t.stand_id=:stand_id;
        
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':stand_id', $standId);

        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "
            INSERT INTO discr_pairs 
	          SET title=:title, description=:description; 
    
            INSERT INTO $this->table_name 
	          SET discr_pair_id= (SELECT id FROM discr_pairs WHERE title=:title AND description=:description), event_time=:event_time, stand_id =:stand_id;
        ";

        $stmt = $this->conn->prepare($query);

        $this->event_time = htmlspecialchars(strip_tags($this->event_time));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->stand_id = htmlspecialchars(strip_tags($this->stand_id));

        //var_dump($this);

        $stmt->bindParam(":event_time", $this->event_time);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":stand_id", $this->stand_id);
        //var_dump($stmt);


        //return false;

        if($stmt->execute())
        {
            return true;
        }

        return false;
    }

    public function read()
    {
        $query = "
        SELECT t.id, dp.title, dp.description, t.event_time, t.stand_id  FROM 
	        $this->table_name t
        JOIN discr_pairs dp 
    	  ON dp.id = t.discr_pair_id;
        ";
        //OPEN
        //SHORTEST
        //PATH
        //FIRST

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        $query = "
            UPDATE discr_pairs dp
	          SET title=:title, description=:description
	        WHERE dp.id = (SELECT discr_pair_id FROM $this->table_name t WHERE t.id =:id);
	        
	        UPDATE $this->table_name t
	          SET t.event_time = :event_time
	          WHERE t.id =:id;
	        ";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        //$this->stand_id = htmlspecialchars(strip_tags($this->stand_id));
        $this->event_time = htmlspecialchars(strip_tags($this->event_time));

        //var_dump($this);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        //$stmt->bindParam(":hall_id", $this->hall_id);
        $stmt->bindParam(":event_time", $this->event_time);

        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
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