<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 01.07.17
 * Time: 12:27
 */

namespace Todo\Common;

use Psr\Container\ContainerInterface;
use Todo\Common\Exception\PersisterNotSetted;

class AbstractManager
{

    /** @var \Medoo\Medoo */
    protected $persister;
    /** @var string */
    protected $tableName;
    /** @var string */
    protected $entityName;

    /**
     * @param ContainerInterface $container
     *
     * @throws PersisterNotSetted
     */
    public function __construct(ContainerInterface $container)
    {
        $db = $container->get('db');
        if (!$db) {
            throw new PersisterNotSetted;
        }
        $this->persister = $db;
    }

    /**
     * @param int $id
     *
     * @return AbstractEntity
     */
    public function getById($id)
    {
        return $this->extractEntity(
            $this->persister->select($this->tableName, '*', ['id' => $id])
        );
    }

    /**
     * @param array $data
     *
     * @return AbstractEntity
     */
    protected function extractEntity($data)
    {
        if (is_array($data)) {
            $entity = new $this->entityName;
            foreach ($data as $key => $value) {
                $entity->{'set' . ucfirst($key)}($value);
            }
            return $entity;
        }
        return NULL;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return AbstractEntity
     */
    public function save(AbstractEntity $entity)
    {
        $id = $entity->getId();
        $time = $this->getDateTime();
        $entity->setUpdated($time);
        try {
            if ($id && $this->persister->count($this->tableName, ['id' => $id])) {
                $this->persister->update($this->tableName, $entity->toArray(), ['id' => $id]);
            } else {
                $entity->setCreated($time);
                $this->persister->insert($this->tableName, $entity->toArray());
            }
        } catch (\Exception $e) {
            return NULL;
        }
        return $entity;
    }

    /**
     * @return false|string
     */
    protected function getDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @param array $cond
     *
     * @return int
     */
    public function getCounts($cond = [])
    {
        return $this->persister->count($this->tableName, $cond);
    }

    /**
     * @param array $cond
     *
     * @return AbstractEntity[]
     */
    public function getAll($cond = [])
    {
        $results = [];
        $data = $this->persister->select($this->tableName, '*', $cond);
        foreach ($data as $row) {
            $results[] = $this->extractEntity($row);
        }
        return $results;
    }

    /**
     * @param array $cond
     *
     * @return AbstractEntity
     */
    public function getOne($cond = [])
    {
        $data = $this->persister->get(
            $this->tableName,
            '*',
            $cond
        );
        if ($data) {
            return $this->extractEntity($data);
        }
        return NULL;
    }
}