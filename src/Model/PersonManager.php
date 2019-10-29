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
class PersonManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'person';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $person
     * @return int
     */
    public function insert(array $person): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (`firstname`, `lastname`, `birthday`, `kingdom_id`) 
                                                    VALUES (:firstname, :lastname, :birthday, :kingdom_id)");
        $statement->bindValue('firstname', $person['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $person['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $person['birthday'], \PDO::PARAM_STR);
        $statement->bindValue('kingdom_id', $person['kingdom_id'], \PDO::PARAM_INT);

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
     * @param array $person
     * @return bool
     */
    public function update(array $person):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table 
                                SET `firstname` = :firstname, lastname=:lastname, birthday=:birthday, kingdom_id=:kingdom_id
                                WHERE id=:id");
        $statement->bindValue('id', $person['id'], \PDO::PARAM_INT);
        $statement->bindValue('kingdom_id', $person['kingdom_id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $person['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $person['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('birthday', $person['birthday'], \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function selectOneWithKingdom(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("
            SELECT p.*, k.name kingdom_name FROM $this->table p
                JOIN kingdom k ON k.id=p.kingdom_id
            WHERE p.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();

    }
}
