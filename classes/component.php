<?php
/**
 *
 * Created by PhpStorm.
 * User: Jasper
 * Date: 21-5-2019
 * Time: 11:28
 */



class component
{
    private $component_id;
    private $name;
    private $description;
    private $max_storage;
    private $active;
    private $timestamp;
    private $logs = array();

    /**
     * component constructor.
     * @param $component_id
     * @param $name
     * @param $description
     * @param $max_storage
     * @param $active
     * @param $timestamp
     * @param array $logs
     */
    public function __construct($component_id)
    {
        $component = $this->fetchComponent($component_id);
        $this->component_id = $component['component_id'];
        $this->name = $component['name'];
        $this->description = $component['description'];
        $this->max_storage = $component['max_storage'];
        $this->active = $component['active'];
        $this->timestamp = $component['timestamp'];
        $this->logs = $this->fetchComponentLogs($component['component_id']);
    }


    /**
     * @return mixed
     */
    public function getComponentId()
    {
        return $this->component_id;
    }

    /**
     * @param mixed $component_id
     */
    public function setComponentId($component_id)
    {
        $this->component_id = $component_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getMaxStorage()
    {
        return $this->max_storage;
    }

    /**
     * @param mixed $max_storage
     */
    public function setMaxStorage($max_storage)
    {
        $this->max_storage = $max_storage;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public static function fetchAllComponents() {
        global $database;
        try {
            $statement = $database->dbc->prepare("SELECT * FROM components");
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
        return $message;
    }

    public function fetchComponent($id) {
        global $database;
        try {
            $statement = $database->dbc->prepare("SELECT * FROM components WHERE component_id = :id");
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        }
        catch (PDOException $e)
        {
            $message = $e->getMessage();
        }
        return $message;

    }

    public function fetchComponentLogs($id) {
        global $database;
        try {
            $statement = $database->dbc->prepare("SELECT * FROM component_log WHERE component_id = :id");
            $statement->bindParam(':id', $id);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
        return $message;

    }
    public function fetchComponentLog($id, $time) {
        global $database;
        try {
            $this->insertDummy($id, $time);
            $statement = $database->dbc->prepare("SELECT * FROM component_log WHERE component_id = :id AND  timestamp = :timestamp");
            $statement->bindParam(':id', $id);
            $timestamp = $time->format('Y-m-d H:i:s');
            $statement->bindParam(':timestamp', $timestamp);
            $statement->execute();
            $result = $statement->fetch();
            return $result;
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
        return $message;
    }

    public function insertDummy($id, $timestamp) {
        global $database;
        try {
            $statement = $database->dbc->prepare("INSERT INTO component_log (component_id, available, cpu_load, storage_used, timestamp) VALUES (:id, '1', :load, :space, :timestamp)");
            $timestamp = $timestamp->format('Y-m-d H:i:s');
            $load = rand(0, 100);
            $space = rand(1000, 10000);
            $statement->bindParam(':id', $id);
            $statement->bindParam(':load', $load);
            $statement->bindParam(':space', $space);
            $statement->bindParam(':timestamp', $timestamp);
            $statement->execute();
            return 1;
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
        return $message;
    }
}