<?php

namespace Kernel\Application\DataBase\Model;

/**
 * Interface ModelInterface
 *
 * This interface defines the basic contract for a model class in the database layer. The methods provide essential
 * functionality for creating, updating, deleting, and querying records from the database. Any model class that
 * implements this interface must define these methods, which interact with the database and return collections of
 * models.
 *
 * @package Kernel\Application\DataBase\Model
 */
interface ModelInterface
{
    /**
     * Creates a new record in the database and returns a collection of created models.
     *
     * @param array $data The data to insert into the database
     *
     * @return Collection A collection containing the created model(s)
     */
    public static function create(array $data): Collection;

    /**
     * Updates an existing record by its ID and returns a collection of updated models.
     *
     * @param int $id The ID of the record to update
     * @param array $data The data to update the record with
     *
     * @return Collection A collection containing the updated model
     */
    public static function update(int $id, array $data): Collection;

    /**
     * Deletes a record by its ID and returns an empty collection.
     *
     * @param int $id The ID of the record to delete
     *
     * @return Collection An empty collection
     */
    public static function delete(int $id): Collection;

    /**
     * Finds a record by its ID and returns a collection containing the model.
     *
     * @param int $id The ID of the record to find
     *
     * @return Collection|null A collection containing the found model, or null if not found
     */
    public static function find(int $id): ?Collection;

    /**
     * Retrieves all records from the database and returns a collection of models.
     *
     * @return Collection A collection containing all models
     */
    public static function all(): Collection;

    /**
     * Retrieves the first record from the database and returns a collection containing the model.
     *
     * @return Collection|null A collection containing the first model, or null if no records exist
     */
    public static function first(): ?Collection;

    /**
     * Finds records that match the specified conditions and returns a collection of models.
     *
     * @param array $conditions The conditions to filter the records by
     *
     * @return Collection A collection of models that match the conditions
     */
    public static function where(array $conditions): Collection;
}
