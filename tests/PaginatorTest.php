<?php

use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    private $paginator;
    public $data;

    public function setUp()
    {
        $dbMock = new PDO('sqlite::memory');
        $dbMock->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbMock->exec("DROP table if exists users;");
        $createTable = $dbMock->exec("CREATE TABLE if not exists users (
                login VARCHAR(30) NOT NULL,
                name VARCHAR(30) NOT NULL,
                email VARCHAR(50)
                )");
  
       $q = $dbMock->prepare('insert into users (login,name,email) values("test","test","test")');
       $q->execute();
       $q = $dbMock->prepare('insert into users (login,name,email) values("test2","test2","test2")');
       $q->execute();
       $q = $dbMock->prepare('insert into users (login,name,email) values("test3","test3","test3")');
       $q->execute();
       $q = $dbMock->prepare('insert into users (login,name,email) values("test4","test4","test4")');
       $q->execute();

        $query = "select * from users";

        $this->paginator = new Naciri\SimplePaginator($dbMock, $query);

     
    }
    public function testTotal()
    {
        $this->assertEquals($this->paginator->total,4);
    }
    public function testFetchData()
    {
        $this->assertNotNull($this->paginator->fetchData(4,2));
        $this->assertEquals(count($this->paginator->fetchData(4,2)),4);
    }
     public function testPaginatingFetchData()
    {
        $this->assertNotNull($this->paginator->fetchData(1,2));
        $this->assertEquals(count($this->paginator->fetchData(1,2)),2);
    }

    public function testRenderLinks()
    {
        $this->assertNotNull($this->paginator->renderLinks(2,"test-class"));
    }
}
