<?php
namespace App\Utils;

class Db
{
  private static ?\PDO $pdo = null;

  /**
   * 单例模式获取PDO连接（配置来自 config/db.php）
   * 
   */
  public static function getInstance(): \PDO
  {
    if (self::$pdo === null) {
      try {
        $db = require __DIR__ . '/../config/db.php';

        self::$pdo = new \PDO(
          "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset={$db['charset']}",
          $db['username'],
          $db['password'],
          [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
          ]
        );
      } catch (\PDOException $e) {
        Response::error('数据库连接失败：' . $e->getMessage(), 500);
      }
    }
    return self::$pdo;
  }

  /**
   * 查询多条
   */
  public static function exec(string $sql, array $params = []): int
  {
    $stmt = self::getInstance()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
  }

  /**
   * 查询多条
   * 
   */
  public static function fetch(string $sql, array $params = []): ?array
  {
    $stmt = self::getInstance()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch() ?: null;
  }

  /**
   * 查询多条
   * 
   */
  public static function fetchAll(string $sql, array $params = []): array
  {
    $stmt = self::getInstance()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  /**
   * 通用分页查询
   * @param string $sql 原始查询SQL（不带 LIMIT）
   * @param array $params 绑定参数
   * @param int $page 当前页码
   * @param int $pageSize 每页条数
   * @return array [list, total, page, pageSize, totalPage]
   */
  public static function page(string $sql, array $params = [], int $page = 1, int $pageSize = 10): array
  {
    // 页码边界处理
    $page = max(1, $page);
    $pageSize = max(1, $pageSize);
    $offset = ($page - 1) * $pageSize;

    // 1. 查询分页数据
    $pageSql = $sql . " LIMIT {$offset}, {$pageSize}";
    $list = self::fetchAll($pageSql, $params);

    // 2. 查询总条数（替换为 COUNT 统计）
    $countSql = "SELECT COUNT(*) AS total FROM ({$sql}) AS t";
    $total = self::fetch($countSql, $params)['total'] ?? 0;

    // 3. 计算总页数
    $totalPage = ceil($total / $pageSize);

    return [
      'list' => $list,      // 列表数据
      'total' => $total,     // 总记录数
      'page' => $page,      // 当前页
      'pageSize' => $pageSize,  // 每页条数
      'totalPage' => $totalPage  // 总页数
    ];
  }
}