<?php

use App\Model\ItemManager;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/config.php';

class ItemManagerTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void
    {
        // Création du mock de PDO
        $this->pdoMock = $this->createMock(PDO::class);

        // Création du mock de PDOStatement
        $this->stmtMock = $this->createMock(PDOStatement::class);
    }

    public function testSelectAll(): void
    {
        // Configuration du mock pour returner un array vide quand fetchAll est appelé
        $this->stmtMock->method('fetchAll')->willReturn([]);

        // Configuration du mock PDO pour returner le mock de PDOStatement quand query est appelé
        $this->pdoMock->method('query')->willReturn($this->stmtMock);

        // Passer le mock de PDO à l'instance de ItemManager
        $manager = new ItemManager($this->pdoMock);

        // Assertion pour vérifier que selectAll retourne un array
        $this->assertIsArray($manager->selectAll());
    }
}

