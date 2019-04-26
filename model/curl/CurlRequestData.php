<?php
/**
 * Created by PhpStorm.
 * User: cedri
 * Date: 2019-04-26
 * Time: 09:02
 */

//       ______           ______                             __  ____        __
//      / ____/_  _______/ / __ \___  ____ ___  _____  _____/ /_/ __ \____ _/ /_____ _
//     / /   / / / / ___/ / /_/ / _ \/ __ `/ / / / _ \/ ___/ __/ / / / __ `/ __/ __ `/
//    / /___/ /_/ / /  / / _, _/  __/ /_/ / /_/ /  __(__  ) /_/ /_/ / /_/ / /_/ /_/ /
//    \____/\__,_/_/  /_/_/ |_|\___/\__, /\__,_/\___/____/\__/_____/\__,_/\__/\__,_/
//                                    /_/

class CurlRequestData
{
    private $model;
    private $id;
    private $datas;

    //TODO Quand ce code va être intégré au programme, il faut mettre en place une façon que le $model soit un model accessible
    public function __construct($model, $id = null, array $datas = null)
    {
        $this->model = $model;
        $this->id = $id;
        $this->datas = $datas;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDatas()
    {
        return $this->datas;
    }



}