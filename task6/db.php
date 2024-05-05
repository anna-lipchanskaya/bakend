<?php
include('../db.php');
global $db;
$db = new PDO('mysql:host=localhost;dbname=' . $db_name, $db_login, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
function db_row($stmt) {
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
function db_row_ALL($stmt) {
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function db_query($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    while ($row = db_row($res)) {
      if (isset($row['id']) && !isset($r[$row['id']])) {
        $r[$row['id']] = $row;
      }
      else {
        $r[] = $row;
      }
    }
  }
  return $r;
}

function db_result($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  $res = $q->execute($args);
  if ($res) {
    if ($row = db_row($res)) {
      return $row[0];
    }
    else {
      return FALSE;
    }
  }
  else {
    return FALSE;
  }
}

function db_command($query) {
  global $db;
  $q = $db->prepare($query);
  $args = func_get_args();
  array_shift($args);
  return $res = $q->execute($args);
}function db_insert_id() {
  global $db;
  return $db->lastInsertId();
}

function db_get_Alluser($default = FALSE) {
  $query = "SELECT a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login, GROUP_CONCAT(DISTINCT l2.name SEPARATOR ', ') as languages
                        FROM application3 a
                        INNER JOIN users u ON a.userid = u.userid
                        LEFT JOIN ap_lan3 al3 ON a.userid = al3.userid
                        LEFT JOIN language2 l2 ON al3.id_language = l2.id
                        GROUP BY a.userid, a.name, a.phone, a.email, a.data, a.pol, a.bio, a.ok, u.login";
  $value = db_query($query);
  if ($value === FALSE) {
    return $default;
  }
  else {
    return $value;
  }
}

function db_set($name, $value) {
  if (strlen($name) == 0) {
    return;
  }

  $v = db_get($name);
  if ($v === FALSE) {
    $q = "INSERT INTO variable VALUES (?, ?)";
    return db_command($q, $name, $value) > 0;
  }
  else {
    $q = "UPDATE variable SET value = ? WHERE name = ?";
    return db_command($q, $value, $name) > 0;
  }
}



    function executeQuery($query) {
    global $db;
    try {
        $result = $db->query($query);
        if ($result) {
            // Запрос успешно выполнен
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Запрос не удался, логируем ошибку
            logError(db_error());
        }
    } catch (PDOException $e) {
        // Ошибка при выполнении запроса, логируем исключение
        logError($e->getMessage());
    }
}
    function Query($query) {
    global $db;
    try {
        $result = $db->query($query);
        if ($result) {
            // Запрос успешно выполнен
            return $result;
        } else {
            // Запрос не удался, логируем ошибку
            logError(db_error());
        }
    } catch (PDOException $e) {
        // Ошибка при выполнении запроса, логируем исключение
        logError($e->getMessage());
    }
}

function db_error() {
    global $db;
    return $db->errorInfo();
}

function logError($errorInfo) {
    error_log(print_r($errorInfo, true));
}
?>
