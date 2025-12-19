<?php
require_once 'config.php';

class DB
{
    private $pdo;
    private $table;

//    public $joinClause='';
    public function __construct($table)
    {
        try {

            $this->pdo = new PDO('mysql:host='.DATABASE_HOST_NAME.';dbname=' . DATABASE_DB_NAME, DATABASE_USER_NAME, DATABASE_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->table = $table;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function join($table, $on = null, $join = '')
    {
        $join = $join . " JOIN $table ON $on";
        if (empty($on)) {
            $table2 = substr($table, 0, strlen($table) - 1);
            $join .= $this->table . '.' . $table2 . "_id = " . $table . ".id";
        }
        $this->joinClause[] = $join;
        return $this;
    }


    public function where(string|array $condition, $value = null)
    {
        $this->whereClause[] = $this->getWhereClause($condition, $value);
        return $this;
    }

    public function select($fields)
    {
        $this->selectClause[] = $fields;
        return $this;
    }

    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function groupBy($by)
    {
        $this->groupBy = $by;
        return $this;
    }

    public function getOne()
    {
       $sql = $this->getSql();
       $stmt = $this->pdo->query($sql);
       return $stmt->fetch();
    }

    public function get()
    {
        $sql = $this->getSql();

//        return $sql;
        $stmt = $this->pdo->query($sql);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_BOTH);
        return $stmt->fetchAll();
    }

    public function getBy(string|array $condition, string $value = '')
    {
        $whereClause = $this->getWhereClause($condition, $value);
//        return $whereClause;
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE $whereClause");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public function getNextId($prefix = 'ORD-'): string
    {
        // استعلام للحصول على أحدث رقم تسلسلي
        $sql = "SELECT MAX(SUBSTRING(id, 5)) FROM $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_NUM);
// إذا كان هناك بيانات، احصل على الرقم الأخير، وإلا ابدأ من 1
        if ($stmt->rowCount() > 0) {
            $last_number = $stmt->fetch()[0] + 1;
        } else {
            $last_number = 1;
        }

// تكوين رقم الطلب الجديد
        return $prefix . str_pad($last_number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param $array_values like ['username'=>'mohammed','email'=>'ali@gmail.com']
     * @return string|int|bool primary key
     */
    public function insert($data): string|int|bool
    {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $stmt = $this->pdo->prepare("INSERT INTO $this->table ($columns) VALUES ($placeholders)");
            // ربط المعلمات بالقيم
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            return $stmt->execute();
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function update($data, $id)
    {
//        die($id);
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :$column";
        }
        $setClause = implode(', ', $setClause);
        $sql = "UPDATE $this->table SET $setClause WHERE id='" . $id . "'";

        // إعداد الاستعلام وتنفيذ
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        return $stmt->execute();
    }

    public function delete($id)
    {
        // بناء جملة SQL للحذف
        $sql = "DELETE FROM $this->table WHERE id= '{$id}'";

        // إعداد الاستعلام وتنفيذه
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    /**
     * @param array|string $condition
     * @param $value
     * @return string
     */
    private function getWhereClause(array|string $condition, $value): string
    {
        $whereClause = '';
        if (is_array($condition) and count($condition) > 0) {
            $keys = array_keys($condition);
            for ($i = 0; $i < count($keys); $i++) {
                $whereClause .= "{$keys[$i]} =  '{$condition[$keys[$i]]}'";
                if (($i + 1) != count($keys)) $whereClause .= ' AND ';
            }
        } elseif ($value != '') {
//            var_dump($value);
//            die();
            $whereClause = $condition . " = '{$value}'";
        } else {
            $whereClause = $condition;
        }
        return $whereClause;
    }

    /**
     * @return string
     */
    private function getSql(): string
    {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($this->selectClause))
            $sql = 'SELECT ' . implode(',', $this->selectClause) . " FROM  {$this->table}";
        if (!empty($this->joinClause)) {
            $sql .= ' ' . implode(' ', $this->joinClause);
        }
        if (!empty($this->whereClause)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->whereClause);
        }

        if (!empty($this->groupBy)) {
            $sql .= " group by {$this->groupBy}";
        }

        if (!empty($this->orderBy)) {
            $sql .= " order by {$this->orderBy}";
        }
        return $sql;
    }
}
