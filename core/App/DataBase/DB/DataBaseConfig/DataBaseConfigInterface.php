<?php

namespace Kernel\Application\DataBase\DB\DataBaseConfig;

/**
 * Interface DataBaseConfigInterface
 *
 * This interface defines the contract for retrieving database configuration parameters.
 * It provides methods for getting the database driver and driver-specific configuration data.
 *
 * @package Kernel\Application\DataBase\DB\DataBaseConfig
 */
interface DataBaseConfigInterface
{
    /**
     * Get the database driver.
     *
     * @return string The name of the database driver (e.g., 'mysql', 'pgsql', 'sqlite')
     */
    public function getDriver(): string;

    /**
     * Get the configuration settings for a specific database driver.
     *
     * @param string $driver The name of the database driver (e.g., 'mysql', 'pgsql', 'sqlite')
     *
     * @return array The configuration settings for the specified driver
     */
    public function getDriverConfig(string $driver): array;
}
