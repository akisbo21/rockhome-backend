<?php

namespace Service;


class Mongo
{
    const HOST = 'MONGO_HOST';
    const DATABASE = 'MONGO_DATABASE';
    const COLLECTION = 'MONGO_COLLECTION';

	protected static $instance;

	protected $connection;
	protected $db;

    public static function getHosts()
    {
        return getenv(self::HOST);
    }

    public static function getDatabase()
    {
        return getenv(self::DATABASE);
    }

    public static function getCollection()
    {
        return getenv(self::COLLECTION);
    }

	private function __construct()
	{
//	    var_dump(self::getHosts());
//        var_dump(self::getDatabase());
//        var_dump(self::getCollection());
//        die();

		$this->connection = new \MongoDB\Client(
			'mongodb://' . self::getHosts()
		);

		$this->db = $this->connection->selectDatabase(self::getDatabase());
	}

	public static function get()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function updateEstate($externalId, $data)
	{
		$collection = $this->db->selectCollection(self::getCollection());
		$count = $collection->countDocuments(['external_id' => $externalId]);
//		echo '<pre>';var_dump($data);die();

		if (empty($count)) {
			return $collection->insertOne($data);
		}
		else {
			return $collection->updateOne(['external_id' => $externalId], [
				'$set' => $data
			]);
		}
	}

    public function count($query)
    {
        $collection = $this->db->selectCollection(self::getCollection());
        return $collection->countDocuments($query);
    }

    /**
     * @return array|null
     */
    public function findById($id, $options) {
        $query['external_id'] = (string)$id;

        $result = Mongo::get()->find($query, $options, false);
//        echo '<pre>';print_r($result);die();

        return $result ? $result[0] : null;
    }

    /**
     * @param $query
     * @param $options
     * @param bool $fetchOnlyId
     * @return array
     */
    public function find($query, $options)
    {
        $collection = $this->db->selectCollection(self::getCollection());
        $result     = $collection->find($query, $options);

        $items = [];
        foreach ($result as $row) {
            $items[] = (array)$row;
        }

//        echo '<pre>';
//        var_dump('$fetchOnlyId -> ' . (int)$fetchOnlyId);
//        var_dump('query->');print_r($query);
//        var_dump($items);
//        die();

        return $items;
    }

	public function findWithCount($query, $options)
	{
//	    echo '<pre>';print_r($query);print_r($options);var_dump(self::getCollection());die();

		$collection = $this->db->selectCollection(self::getCollection());
		$count      = $collection->countDocuments($query);
		$result     = $collection->find($query, $options);

		$items = [];
		foreach ($result as $row) {
            $items[] = (array)$row;
		}

//        echo '<pre>';
//        var_dump('query->');print_r($query);
//        var_dump("Total: " . $count);print_r($items);
//        die();

		return [
			'totalCount' => $count,
			'items' => $items
		];
	}
}