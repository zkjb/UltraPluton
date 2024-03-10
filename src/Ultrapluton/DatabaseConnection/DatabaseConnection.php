<?php

declare(strict_types=1);

namespace Ultrapluton\DatabaseConnection;

use PDO;
use Ultrapluton\DatabaseConnection\Exception\DatabaseConnectionException;

class DatabaseConnection implements DatabaseConnectionInterface
{

    /**
     * @var PDO
     */
    protected PDO $dbh;

    /**
     * @var array
     */
    protected array $credentials;


    /**
     * Main constructor class
     *
     * @param array $credentials
     * @return void
     */
    public function __construct(array $credentials)
    {
        return $this->credentials = $credentials;
    }


    #[\Override] public function open(): PDO
    {
        // TODO: Implement open() method.
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES =>false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            return $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );

        }catch (\PDOException $exception){
               throw new DatabaseConnectionException($exception->getMessage(),(int)$exception->getCode());
        }
    }

    #[\Override] public function close(): void
    {
        // TODO: Implement close() method.
       $this->dbh = null;
    }
}