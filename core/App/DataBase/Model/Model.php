<?php

namespace Kernel\Application\DataBase\Model;

use Exception;
use Kernel\Application\DataBase\DB\DataBase;
use Kernel\Application\DataBase\DB\DataBaseConnect;

/**
 * Class Model
 *
 * An abstract class representing a database model. It provides common functionality for interacting with a database,
 * including creating, updating, deleting, and querying records. The model follows an Active Record pattern, allowing
 * instances to be associated with specific database tables.
 *
 * @package Kernel\Application\DataBase\Model
 */
abstract class Model implements ModelInterface
{
    /**
     * @var DataBase|null $db The database connection instance
     */
    protected static ?DataBase $db = null;

    /**
     * @var string $table The name of the associated database table
     */
    protected static string $table = '';

    /**
     * @var array $fillable An array of attributes that can be mass-assigned
     */
    protected static array $fillable = [];

    /**
     * @var array $attributes The model's attributes (properties)
     */
    private array $attributes = [];

    /**
     * Initializes the database connection and the database instance for the model.
     *
     * This method is called automatically before any database interaction. It ensures that the database connection
     * is initialized and associated with the model.
     * @throws Exception
     */
    private static function initialize(): void
    {
        if (is_null(self::$db)) {
            $pdo = (new DataBaseConnect(app()->get('config')))->connect();
            self::$db = new DataBase($pdo, static::$table);
        }
    }

    /**
     * Magic method to get the value of an attribute.
     *
     * @param string $name The name of the attribute to retrieve
     *
     * @return mixed|null The value of the attribute, or null if not set
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Magic method to set the value of an attribute.
     *
     * @param string $name The name of the attribute to set
     * @param mixed $value The value to set for the attribute
     */
    public function __set(string $name, mixed $value)
    {
        if (in_array($name, static::$fillable)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * Magic method to check if an attribute is set.
     *
     * @param string $name The name of the attribute to check
     *
     * @return bool True if the attribute is set, false otherwise
     */
    public function __isset(string $name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Model constructor.
     *
     * Initializes the model with an array of attributes. Only fillable attributes will be assigned.
     *
     * @param array $attributes An array of attributes to initialize the model with (optional)
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, static::$fillable)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * Creates a new record in the database and returns a collection of created models.
     *
     * @param array $data The data to insert into the database
     *
     * @return Collection A collection containing the created model(s)
     */
    public static function create(array $data): Collection
    {
        self::initialize();
        $filteredData = array_intersect_key($data, array_flip(static::$fillable));
        self::$db->create($filteredData);
        $newItem = self::$db->where($filteredData);

        $modelObjects = array_map(fn($item) => new static($item), $newItem);
        return new Collection($modelObjects, static::class);
    }

    /**
     * Updates an existing record by its ID and returns a collection of updated models.
     *
     * @param int $id The ID of the record to update
     * @param array $data The data to update the record with
     *
     * @return Collection A collection containing the updated model
     */
    public static function update(int $id, array $data): Collection
    {
        self::initialize();
        $filteredData = array_intersect_key($data, array_flip(static::$fillable));
        self::$db->update($id, $filteredData);

        $updatedItem = self::$db->find($id);
        return new Collection([new static($updatedItem)], static::class);
    }

    /**
     * Deletes a record by its ID and returns an empty collection.
     *
     * @param int $id The ID of the record to delete
     *
     * @return Collection An empty collection
     */
    public static function delete(int $id): Collection
    {
        self::initialize();
        self::$db->delete($id);

        return new Collection([], static::class);
    }

    /**
     * Finds a record by its ID and returns a collection containing the model.
     *
     * @param int $id The ID of the record to find
     *
     * @return Collection|null A collection containing the found model, or null if not found
     */
    public static function find(int $id)
    {
        self::initialize();
        $result = self::$db->find($id);
        return $result ? new Collection([new static($result)], static::class) : null;
    }

    /**
     * Retrieves all records from the database and returns a collection of models.
     *
     * @return Collection A collection containing all models
     */
    public static function all(): Collection
    {
        self::initialize();
        $results = self::$db->all();

        $modelObjects = array_map(fn($item) => new static($item), $results);
        return new Collection($modelObjects, static::class);
    }

    /**
     * Retrieves the first record from the database and returns a collection containing the model.
     *
     * @return Collection|null A collection containing the first model, or null if no records exist
     */
    public static function first(): ?Collection
    {
        self::initialize();
        $result = self::$db->first();
        return $result ? new Collection([new static($result)], static::class) : null;
    }

    /**
     * Finds records that match the specified conditions and returns a collection of models.
     *
     * @param array $conditions The conditions to filter the records by
     *
     * @return Collection A collection of models that match the conditions
     */
    public static function where(array $conditions): Collection
    {
        self::initialize();
        $results = self::$db->where($conditions);

        $modelObjects = array_map(fn($item) => new static($item), $results);
        return new Collection($modelObjects, static::class);
    }
}
