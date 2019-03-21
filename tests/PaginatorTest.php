<?php

use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    private $paginator;
    private $dbMock;

    public function setUp()
    {
        $this->dbMock = new PDO('sqlite::memory');
        $this->dbMock->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initDb();
        $this->paginator = new Naciri\SimplePaginator($this->dbMock, "select * from users");
    }
    public function testTotal()
    {
        $this->assertEquals($this->paginator->total, 10);
    }
    public function testFetchData()
    {
        $this->assertNotNull($this->paginator->fetchData(4, 1)->data);
        $this->assertEquals(count($this->paginator->fetchData(4, 1)->data), 4);
    }
    public function testPaginatingFetchData()
    {
        $this->assertNotNull($this->paginator->fetchData(2, 1)->data);
        $this->assertEquals(count($this->paginator->fetchData(2, 1)->data), 2);
    }

    public function testRenderLinks()
    {
        $this->assertNotNull($this->paginator->renderLinks(1, "test-class"));
    }
    private function initDb()
    {
        $this->dbMock->exec("DROP table if exists users;");
        $createTable = $this->dbMock->exec("CREATE TABLE if not exists users (
                login VARCHAR(30) NOT NULL,
                name VARCHAR(30) NOT NULL,
                email VARCHAR(50)
                )");

        $q = $this->dbMock->prepare('insert into users (login,name,email) values(?, ?,?)');
       
        for ($i = 0; $i < 10; $i++) {
            $q->execute(['testLogin'.$i, 'testName'.$i, 'testEmail'.$i]);
        }
    }
}
