<?php


namespace model;


class UsagerProbleme extends DataAccess
{

    private $userid;
    private $problemid;

    /**
     * @return string
     */
    public function getUserid(): string
    {
        return $this->userid;
    }

    /**
     * @return string
     */
    public function getProblemid(): string
    {
        return $this->problemid;
    }


    public function __construct($pdo){
        $this->_pdo = $pdo;
        $this->table_name = UsagerProblemesTable::$TABLE_NAME;
        $this->userid = UsagerProblemesTable::$ID_USAGER;
        $this->problemid = UsagerProblemesTable::$ID_PROBLEME;
        $this->all_column = UsagerProblemesTable::getAllColums();

    }

    /**
     * Fonction qui va chercher le nombre d'usagers qui ont signalé un problème en particulier.
     *
     * @param $problemId le ID du problème.
     * @return array
     */
    public function getUsersCountForThisProblem($problemId){
        $request = $this->_pdo->prepare("SELECT COUNT(" . $this->getUserid() . ") AS count FROM " .
                    $this->getTableName() . " WHERE " . $this->getProblemid() . " = :" . $this->getProblemid(). ";");
        $request->execute([$this->getProblemid() => $problemId]);
        return $request->fetchObject();
    }

    /**
     * Fonction qui retourne tous les problèmes signalés par un usager.
     *
     * @param $userId id de l'usager
     * @param $voirieProbModel
     * @return array
     */
    public function getProblemsByUserId($userId, $voirieProbModel){
        $allColumn = implode(", " , $voirieProbModel->getAllColumn());
        $request = $this->_pdo->prepare("SELECT " . $allColumn . " FROM " . $voirieProbModel->getTableName()
                    . " JOIN " . $this->getTableName() . " ON " . $voirieProbModel->getId() . " = "
                    . $this->getProblemid() . " WHERE " . $this->getUserid() . " = :" . $this->getUserid());
        $request->execute([$this->getUserid() => $userId]);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getUsersByProblemId($problemId , $usagerModel ){

        $allColumn = $usagerModel->getTableName(). ".". implode(", " . $usagerModel->getTableName(). "." , $usagerModel->getAllColumn());
        $sql = "SELECT ". $allColumn ." FROM " . $usagerModel->getTableName()
            . " JOIN " . $this->getTableName() . " ON " .  $this->getTableName(). "." . $this->getUserid() . " = "
            .$usagerModel->getTableName() . "." . $usagerModel->getId() . " WHERE " . $this->getTableName(). "."
            . $this->getProblemid() . " = :" . $this->getProblemid();
        $request = $this->_pdo->prepare($sql);
        $request->execute([$this->getProblemid() => $problemId]);
        return $request->fetchAll(\PDO::FETCH_OBJ);
    }


    public function getProblemIdWithUserId($userId, $problemId){
        $request = $this->_pdo->prepare("SELECT * FROM " . $this->getTableName() . " WHERE "
            . $this->getUserid() . " = :" . $this->getUserid() . " AND "
            . $this->getProblemid() . " = :" . $this->getProblemid());
        $request->execute([$this->getUserid() => $userId,$this->getProblemid() => $problemId ]);
        return $request->fetchObject();
    }

    /**
     * Fonction qui associe un problème à un usager.
     *
     * @param $userId ID de l'usager.
     * @param $problemId ID du problème.
     * @return array
     */
    public function associateProblemIdWithUserId($problemId, $userId){
        $allColumn = "`" . implode( "`,`",$this->getAllColumn()) . "`";
        $request = $this->_pdo->prepare("INSERT INTO " . $this->getTableName() . "(" . $allColumn . ")"
                    . " VALUES ( :" . implode(", :", $this->getAllColumn()) . ")" );
        return $request->execute([$this->getUserid() => $userId, $this->getProblemid() => $problemId]);

    }

    /**
     * Fonction qui supprime un problème d'un usager.
     *
     * @param $problemId le problème a dissocier.
     * @param $userId l'usager a dissocié.
     * @return bool
     */
    public function deleteProblemIdFromThisUserId($problemId, $userId){
        $request = $this->_pdo->prepare("DELETE FROM " . $this->getTableName() . " WHERE "
            . $this->getUserid() . " = :" . $this->getUserid() . " AND "
            . $this->getProblemid() . " = :" . $this->getProblemid());
        return $request->execute([$this->getUserid() => $userId,$this->getProblemid() => $problemId ]);

    }

}