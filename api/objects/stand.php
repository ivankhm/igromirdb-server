<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/27/2017
 * Time: 8:05 PM
 */

class Stand {

    public $id;
    public $title;
    public $description;
    public $hall_id;
    public $image;


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
                "image" => $image,
                "hall_id" => $hall_id,
                "owner_id" => $owner_id
            );
            //var_dump($visitor_item);


            array_push($visitors_arr["records"], $visitor_item);
        }
    }
    
    public function readOne()
    {
        $query = "
        SELECT s.id, dp.title, dp.description, s.hall_id, s.owner_id, i.path as image FROM 
	        stands s 
        JOIN discr_pairs dp 
    	  ON dp.id = s.discr_pair_id
        JOIN images i
    	  ON s.image_id = i.id;
          WHERE s.id=:sid
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
        
        $this->image = $row['image'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->hall_id = $row['hall_id'];
        $this->owner_id = $row['owner_id'];
        //var_dump($this);
    }


    public function create()
    {
        $query = "
            INSERT INTO images 
	          SET path=:image;

            INSERT INTO discr_pairs 
	          SET title=:title, description=:description; 
    
            INSERT INTO stands 
	          SET discr_pair_id= (SELECT id FROM discr_pairs WHERE title=:title AND description=:description), hall_id=:hall_id, image_id=
    	               (SELECT  id FROM images i WHERE i.path = :image);

        ";

        $stmt = $this->conn->prepare($query);

        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->hall_id = htmlspecialchars(strip_tags($this->hall_id));

        //var_dump($this);

        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":hall_id", $this->hall_id);
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
        SELECT s.id, dp.title, dp.description, s.hall_id, s.owner_id, i.path as image FROM 
	        stands s 
        JOIN discr_pairs dp 
    	  ON dp.id = s.discr_pair_id
        JOIN images i
    	  ON s.image_id = i.id;
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

            UPDATE images i
	         SET path=:image
	         WHERE i.id = (SELECT image_id FROM stands s WHERE s.id:=id);

            UPDATE discr_pairs dp
	          SET title=:title, description=:description
	        WHERE dp.id = (SELECT discr_pair_id FROM stands WHERE s.id =:id);
        ";

        $stmt = $this->conn->prepare($query);

        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->hall_id = htmlspecialchars(strip_tags($this->hall_id));

        //var_dump($this);

        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":hall_id", $this->hall_id);

        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}