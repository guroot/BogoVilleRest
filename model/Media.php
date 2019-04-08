<?php


namespace model;


class Media extends DataAccess
{

    private $table_name;
    private $id;
    private $all_column;
    private $media;
    private $mime;
    private $filename;

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAllColumn(): array
    {
        return $this->all_column;
    }

    /**
     * @return string
     */
    public function getMedia(): string
    {
        return $this->media;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }



    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = MediaTable::$TABLE_NAME;
        $this->id = MediaTable::$ID;
        $this->media = MediaTable::$MEDIA;
        $this->mime = MediaTable::$MIME;
        $this->filename = MediaTable::$FILENAME;
        $this->all_column = MediaTable::getAllColums();

    }

}