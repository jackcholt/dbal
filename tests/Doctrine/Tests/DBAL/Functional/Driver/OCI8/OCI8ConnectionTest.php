<?php

namespace Doctrine\Tests\DBAL\Functional\Driver\OCI8;

use Doctrine\DBAL\Driver\OCI8\Driver;
use Doctrine\Tests\DbalFunctionalTestCase;

class OCI8ConnectionTest extends DbalFunctionalTestCase
{
    /**
     * @var \Doctrine\DBAL\Driver\OCI8\OCI8Connection
     */
    protected $driverConnection;

    protected function setUp()
    {
        if (! extension_loaded('oci8')) {
            $this->markTestSkipped('oci8 is not installed.');
        }

        parent::setUp();

        if (! $this->_conn->getDriver() instanceof Driver) {
            $this->markTestSkipped('oci8 only test.');
        }

        $this->driverConnection = $this->_conn->getWrappedConnection();
    }

    /**
     * @group DBAL-2595
     */
    public function testLastInsertIdAcceptsFqn()
    {
        $this->_conn->executeUpdate('CREATE SEQUENCE dbal2595_seq');
        $this->_conn->executeUpdate('CREATE TABLE DBAL2595(id NUMBER DEFAULT dbal2595_seq.NEXTVAL, foo NUMBER)');
        $this->_conn->executeUpdate('INSERT INTO DBAL2595 (foo) VALUES (1)');

        $schema = $this->_conn->getDatabase();

        $this->assertSame(1, $this->driverConnection->lastInsertId($schema . '.dbal2595_seq'));
    }
}
