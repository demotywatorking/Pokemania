<?php
namespace src\libs;

class Database extends \PDO
{

    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS)
    {
        parent::__construct($DB_TYPE.':host='.$DB_HOST.';charset=utf8;dbname='.$DB_NAME, $DB_USER, $DB_PASS);
        //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTIONS);
    }
    
    public function select($sql, $array = [], $fetchMode = \PDO::FETCH_ASSOC)
    {
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value){
            $sth->bindValue("$key", $value);
        }
        $sth->execute();

        return array_merge($sth->fetchAll($fetchMode), ['rowCount' => $sth->rowCount()]); 
    }
    
    public function insert($query, $params)
    {
        $stmt = $this->prepare($query);
        // Execute statement
        $stmt->execute($params);
    }
    
    public function update($query, $params)
    {
        $stmt = $this->prepare($query);
        // Execute statement
        $stmt->execute($params);
    }
    
    public function delete($query, $params, $limit = 1)
    {
        $stmt = $this->prepare($query." LIMIT $limit");
        // Execute statement
        $stmt->execute($params);
    }
             
}

