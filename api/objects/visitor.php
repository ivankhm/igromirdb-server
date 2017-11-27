<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/20/2017
 * Time: 9:25 PM
 */

//TODO сделать абстрактный класс, меньше писать
class Visitor
{

    private $conn;
    private $table_name = 'visitors';

    public $id;
    public $login;
    public $password_hash;

    public $first_name;
    public $last_name;
    
    public $ticket_number;
    
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
                "login" => $login,
                "pswrd_hash" => $pswrd_hash,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "ticket_number" => $ticket_number,
                "image" => $image
            );
            //var_dump($visitor_item);


            array_push($visitors_arr["records"], $visitor_item);
        }
    }

    public function read()
    {


        $query = "
        SELECT v.id, ai.login, ai.pswrd_hash, vi.first_name, vi.last_name, vi.ticket_number, i.path as image 
            FROM visitors v 
                LEFT JOIN visitors_info vi 
                        ON v.visitor_info_id = vi.id 
                LEFT JOIN images i 
                        ON vi.image_id = i.id 
                RIGHT JOIN 
                    autho_info ai 
                        ON v.autho_info_id = ai.id
        ";
        //OPEN
        //SHORTEST
        //PATH
        //FIRST 
        
        
        
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "
            INSERT INTO images 
	           SET path=:image;
            INSERT INTO autho_info
	           SET login=:login, pswrd_hash=:pswrd_hash;
               
            INSERT INTO visitors_info
	           SET first_name=:first_name, last_name=:last_name, ticket_number=:ticket_number,
                    image_id=
    	               (SELECT id FROM images i WHERE i.path = :image);
            INSERT INTO visitors 
                SET 
                    autho_info_id = 
    	               (SELECT id FROM autho_info WHERE (login=:login)),
                    visitor_info_id = 
                        (SELECT id FROM visitors_info WHERE (first_name=:first_name AND last_name=:last_name AND ticket_number=:ticket_number));
        ";
        
        $stmt = $this->conn->prepare($query);
        
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password_hash = htmlspecialchars(strip_tags($this->password_hash));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->ticket_number = htmlspecialchars(strip_tags($this->ticket_number));
        
        //var_dump($this);
        
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":pswrd_hash", $this->password_hash);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":ticket_number", $this->ticket_number);
        //var_dump($stmt);    
        
        
        //return false;
        
        if($stmt->execute())
        {
            return true;                   
        }
        
        return false;
    }
    
    public function readOne()
    {
        $query = "
        SELECT v.id, ai.login, ai.pswrd_hash, vi.first_name, vi.last_name, vi.ticket_number, i.path as image 
            FROM visitors v 
                LEFT JOIN visitors_info vi 
                        ON v.visitor_info_id = vi.id 
                LEFT JOIN images i 
                        ON vi.image_id = i.id 
                RIGHT JOIN 
                    autho_info ai 
                        ON v.autho_info_id = ai.id
            WHERE 
                v.id =:sid
            LIMIT
                0,1
        ";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':sid', $this->id);
        
        $stmt->execute();
        
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //var_dump($row);
        
        $this->image = $row['image'];
        $this->login = $row['login'];
        $this->password_hash = $row['pswrd_hash'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->ticket_number = $row['ticket_number'];
        
        //var_dump($this);
        
    }
    
    public function update()
    {
        $query = "
            UPDATE visitors_info
	           SET first_name=:first_name, last_name=:last_name, ticket_number=:ticket_number
            WHERE id = 
                (SELECT visitor_info_id FROM visitors v WHERE v.id =:id);
                
            UPDATE images i
	           SET path=:image
            WHERE i.id = 
                (
                SELECT image_id FROM visitors_info vi WHERE vi.id = 
                    (
                    SELECT visitor_info_id FROM visitors v WHERE v.id =:id
                    ) 
                );
                
            UPDATE autho_info
	           SET login=:login, pswrd_hash=:pswrd_hash
            WHERE id = 
                (SELECT autho_info_id FROM visitors v WHERE v.id =:id);
        ";
            
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password_hash = htmlspecialchars(strip_tags($this->password_hash));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->ticket_number = htmlspecialchars(strip_tags($this->ticket_number));

        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":pswrd_hash", $this->password_hash);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":ticket_number", $this->ticket_number);

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

    public function login()
    {
        $query =
            "SELECT v.id, ai.pswrd_hash
	          FROM $this->table_name v 
                JOIN autho_info ai
                ON ai.id = v.autho_info_id
            WHERE ai.login =:login";

        $stmt = $this->conn->prepare($query);

        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password_hash = htmlspecialchars(strip_tags($this->password_hash));

        $stmt->bindParam(":login", $this->login);
        //$stmt->bindParam(":pswrd_hash", $this->password_hash);

        $stmt->execute();

        return $stmt;
    }

    public function search($keywords)
    {
        $query = "SELECT v.id, ai.login, ai.pswrd_hash, vi.first_name, vi.last_name, vi.ticket_number, i.path as image 
            FROM visitors v 
                LEFT JOIN visitors_info vi 
                        ON v.visitor_info_id = vi.id 
                LEFT JOIN images i 
                        ON vi.image_id = i.id 
                RIGHT JOIN 
                    autho_info ai 
                        ON v.autho_info_id = ai.id
                WHERE (vi.first_name LIKE ? OR vi.last_name LIKE ? OR ai.login LIKE ? OR vi.ticket_number LIKE ?);
        ";
        
        $stmt = $this->conn->prepare($query);
        
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
        
        for ($i = 1; $i<5; $i++)
        {
            $stmt->bindParam($i, $keywords);
        }
        $stmt->execute();
        
        return $stmt;
    }
    
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT v.id, ai.login, ai.pswrd_hash, vi.first_name, vi.last_name, vi.ticket_number, i.path as image 
            FROM visitors v 
                LEFT JOIN visitors_info vi 
                        ON v.visitor_info_id = vi.id 
                LEFT JOIN images i 
                        ON vi.image_id = i.id 
                RIGHT JOIN 
                    autho_info ai 
                        ON v.autho_info_id = ai.id
            ORDER BY v.id DESC
            LIMIT ?, ?
        ";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt;
    }
    
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM ".$this->table_name."";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //var_dump($row);
        return $row['total_rows'];
    }
}