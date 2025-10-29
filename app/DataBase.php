<?php
declare(strict_types=1);

class DataBase {
    private PDO $pdo;

    public function __construct(string $dsn, $user = null, $password = null) {
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    protected function query(string $query): PDOStatement {
        return $this->pdo->query($query);
    }

    public function getData(string $query, string $class): array {
        return $this
            ->query($query)
            ->fetchAll(PDO::FETCH_CLASS, $class) ?? [];
    }

    public function getRows(string $query): array {
        return $this->query($query)->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }

    public function getSiteBySlug(string $slug): ?Site {
        $statement = $this->pdo->prepare("SELECT * FROM site WHERE slug = :slug LIMIT 1");
        $statement->execute(["slug" => $slug]);
        $statement->setFetchMode(PDO::FETCH_CLASS, Site::class);
        $site = $statement->fetch();
        return $site instanceof Site ? $site : null;
    }

    public function execute(string $query): bool {
        return $this->pdo->exec($query) !== false;
    }

    public function prepare(string $query): PDOStatement {
        return $this->pdo->prepare($query);
    }
}
