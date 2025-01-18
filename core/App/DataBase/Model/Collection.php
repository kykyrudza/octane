<?php

namespace Kernel\Application\DataBase\Model;

/**
 * Class Collection
 *
 * This class represents a collection of items and provides methods for managing, accessing, and manipulating the items.
 * It is a simple container for objects or arrays, with methods for adding, retrieving, counting, and checking items.
 * The collection can be used with any model type, and it supports iterating over items with custom callbacks.
 *
 * @package Kernel\Application\DataBase\Model
 */
class Collection
{
    /**
     * @var array $items The items in the collection
     */
    protected array $items = [];

    /**
     * @var string $model The model type associated with the collection (optional)
     */
    protected string $model;

    /**
     * Collection constructor.
     *
     * Initializes the collection with an array of items and an optional model type.
     *
     * @param array $items An array of items to initialize the collection with (default is an empty array)
     * @param string $model The model type associated with the collection (default is an empty string)
     */
    public function __construct(array $items = [], string $model = '')
    {
        $this->items = $items;
        $this->model = $model;
    }

    /**
     * Adds an item to the collection.
     *
     * @param mixed $item The item to add to the collection
     */
    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Retrieves all items in the collection.
     *
     * @return array An array of all items in the collection
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Retrieves an item by its index in the collection.
     *
     * @param int $index The index of the item to retrieve
     *
     * @return mixed|null The item at the specified index, or null if not found
     */
    public function get(int $index): mixed
    {
        return $this->items[$index] ?? null;
    }

    /**
     * Checks if an item exists in the collection.
     *
     * @param mixed $item The item to check for in the collection
     *
     * @return bool True if the item is in the collection, false otherwise
     */
    public function contains(mixed $item): bool
    {
        return in_array($item, $this->items, true);
    }

    /**
     * Iterates over all items in the collection and applies a callback function to each.
     *
     * @param callable $callback The callback function to apply to each item
     */
    public function each(callable $callback): void
    {
        foreach ($this->items as $item) {
            $callback($item);
        }
    }

    /**
     * Counts the number of items in the collection.
     *
     * @return int The number of items in the collection
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Converts the collection to a string representation.
     *
     * This method provides a detailed string representation of the collection,
     * including the model name, the number of items, and the details of each item.
     *
     * @return string A string representation of the collection
     */
    public function __toString(): string
    {
        $modelName = $this->model ?: 'Unknown Model';
        $count = count($this->items);

        $collectionDetails = sprintf(
            "%s Collection (%d items):\n",
            $modelName,
            $count
        );

        foreach ($this->items as $index => $item) {
            $collectionDetails .= sprintf(
                "\nItem %d:\n%s\n",
                $index + 1,
                $this->formatItem($item)
            );
        }

        return $collectionDetails;
    }

    /**
     * Formats an individual item for string representation.
     *
     * @param mixed $item The item to format
     *
     * @return string A formatted string representation of the item
     */
    private function formatItem(mixed $item): string
    {
        if (is_object($item)) {
            $formattedItem = '';
            foreach ($item as $key => $value) {
                $formattedItem .= sprintf("    %-20s : %s\n", ucfirst($key), $this->formatValue($value));
            }
            return $formattedItem;
        }

        return '[Object]';
    }

    /**
     * Formats a value for string representation.
     *
     * @param mixed $value The value to format
     *
     * @return string A formatted string representation of the value
     */
    private function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            return '[Array] ' . print_r($value, true);
        } elseif (is_object($value)) {
            return '[Object] ' . get_class($value);
        }
        return $value;
    }
}
