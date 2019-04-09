<?php


namespace model;


class Media extends DataAccess
{

    private $media;
    private $mime;
    private $filename;


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