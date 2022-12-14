<?php

namespace DB;

use Exception;
use PDO;
use Util\ConstantesGenericasUtil;

class MySQL
{
    private static $db = null;
    private string $table;

    private static function setDB()
    {
        try {
            if (self::$db === null) {
                self::$db = new PDO(
                    'mysql:host=' . HOST . '; dbname=' . BANCO . ';', USER, SENHA
                );
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }
        return self::$db;
    }

    public function getAll()
    {
        try {
            $consulta = 'SELECT * FROM ' . $this->table;
            $stmt = $this->getDb()->query($consulta);
            return $stmt->fetchAll($this->getDb()::FETCH_ASSOC);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }
    }

    public function getOneByKey($id)
    {
        try {
            $consulta = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
            $stmt = $this->getDb()->prepare($consulta);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch($this->getDb()::FETCH_ASSOC);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }
    }

    public function insert($data)
    {

        try {
            $fields = implode(', ', array_keys($data));
            $places = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO " . $this->table . "(" . $fields . ") VALUES (" . $places . ")";
            $stmt = $this->getDb()->prepare($sql);
            foreach($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();
            $data['id'] = $this->getDb()->lastInsertId();
            return ConstantesGenericasUtil::MSG_INSERIDO_SUCESSO;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }

    }

    public function update($data, $id)
    {
        try {
            foreach($data as $key => $value) {
                $places[] = $key . ' = :' . $key;
            }
            $places = implode(', ', $places);
            $sql = "UPDATE " . $this->table . " SET " . $places . " WHERE id = :id";
            $stmt = $this->getDb()->prepare($sql);
            foreach($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }
    }

    public function delete($id)
    {
        try {
            $consultaDelete = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            $this->getDb()->beginTransaction();
            $stmt = $this->getDb()->prepare($consultaDelete);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $this->getDb()->commit();
                return ConstantesGenericasUtil::MSG_DELETADO_SUCESSO;
            }
            $this->getDb()->rollBack();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage(), 404);
        }
    }

    public function getDb()
    {
        return self::setDB();
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

}