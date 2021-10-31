<?php

namespace ByJG\AnyDataset\Semantic;

use SparQL\Connection;
use SparQL\ConnectionException;
use SparQL\Exception;

class SparQLDataset
{

    /**
     * @var object
     */
    private $connection;

    /**
     *
     * @param string $url
     * @param string $namespaces
     */
    public function __construct($url, $namespaces = null)
    {
        $this->connection = Connection::get($url);

        if (is_array($namespaces)) {
            foreach ($namespaces as $key => $value) {
                $this->connection->withNamespace($key, $value);
            }
        }

        if (function_exists('dba_open')) {
            $cache = sys_get_temp_dir() . "/caps.db";
            $this->connection->capabilityCache($cache);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getCapabilities()
    {
        $return = array();

        if (function_exists('dba_open')) {
            foreach ($this->connection->capabilityCodes() as $code) {
                $return[$code] = array(
                    $this->connection->supports($code),
                    $this->connection->capabilityDescription($code)
                );
            }
        }

        return $return;
    }

    /**
     * @param string $sparql
     * @return SparQLIterator
     * @throws ConnectionException
     * @throws Exception
     */
    public function getIterator($sparql)
    {
        $result = $this->connection->query($sparql);
        return new SparQLIterator($result);
    }
}
