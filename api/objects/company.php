<?php
/**
 * Created by PhpStorm.
 * User: ivank
 * Date: 11/25/2017
 * Time: 4:24 PM
 */

class Company
{
    private $conn;
    private $table_name = 'companies';

    public $id;
    public $company_name;
    public $company_description;

    public $login;
    public $password_hash;

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
                "company_name" => $company_name,
                "company_description" => $company_description,
                "image" => $image
            );
            //var_dump($visitor_item);


            array_push($visitors_arr["records"], $visitor_item);
        }
    }

    public function read()
    {


        $query = "
        SELECT c.id, ai.login, ai.pswrd_hash, ci.company_name, ci.company_description, i.path as image 
            FROM $this->table_name c 
                 JOIN companies_info ci 
                        ON c.company_info_id = ci.id 
                 JOIN images i 
                        ON ci.image_id = i.id 
                 JOIN 
                    autho_info ai 
                        ON c.autho_info_id = ai.id
        ";
        //OPEN
        //SHORTEST
        //PATH
        //FIRST



        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }
    
    
    public function readOne()
    {
        $query = "
        SELECT c.id, ai.login, ai.pswrd_hash, ci.company_name, ci.company_description, i.path as image 
            FROM $this->table_name c 
                 JOIN companies_info ci 
                        ON c.company_info_id = ci.id 
                 JOIN images i 
                        ON ci.image_id = i.id 
                 JOIN 
                    autho_info ai 
                        ON c.autho_info_id = ai.id
            WHERE c.id=:sid
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
        $this->company_name = $row['company_name'];
        $this->company_description = $row['company_description'];
        //var_dump($this);
    }
    

    public function create()
    {
        $query = "
            INSERT INTO images 
	           SET path=:image;
            INSERT INTO autho_info
	           SET login=:login, pswrd_hash=:pswrd_hash;
               
            INSERT INTO companies_info
	           SET company_name=:company_name, company_description=:company_description,
                    image_id=
    	               (SELECT id FROM images i WHERE i.path = :image);
            INSERT INTO companies 
                SET 
                    autho_info_id = 
    	               (SELECT id FROM autho_info WHERE (login=:login)),
                    company_info_id = 
                        (SELECT id FROM companies_info WHERE (company_name=:company_name AND company_description=:company_description));
        ";

        $stmt = $this->conn->prepare($query);

        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password_hash = htmlspecialchars(strip_tags($this->password_hash));
        $this->company_name = htmlspecialchars(strip_tags($this->company_name));
        $this->company_description = htmlspecialchars(strip_tags($this->company_description));

        //var_dump($this);

        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":pswrd_hash", $this->password_hash);
        $stmt->bindParam(":company_name", $this->company_name);
        $stmt->bindParam(":company_description", $this->company_description);
        //var_dump($stmt);


        //return false;

        if($stmt->execute())
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


}