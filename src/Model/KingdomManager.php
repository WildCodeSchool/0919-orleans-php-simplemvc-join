<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class KingdomManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'kingdom';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $kingdom
     * @return int
     */
    public function insert(array $kingdom): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (`name`) VALUES (:name)");
        $statement->bindValue('name', $kingdom['name'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $kingdom
     * @return bool
     */
    public function update(array $kingdom):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `name` = :name WHERE id=:id");
        $statement->bindValue('id', $kingdom['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $kingdom['name'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
