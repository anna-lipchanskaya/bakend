<html>
  <head>
    <style>
/* Сообщения об ошибках и поля с ошибками выводим с красным бордюром. */
.error {
  border: 2px solid red;
}
    </style>
  </head>
  <body>

<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>

    <form action="" method="POST">
      <input name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
      <input type="submit" name = "ok" value="ok" />
      <?php
$_SESSION['ok'] = $_POST['ok'];
?>
      <input type="submit" name="logout" value="Выход">
      <?php
        print($_POST['logout']);
      if (isset($_POST['logout'])) {
        print("NO");
        $_SESSION['logout'] = $_POST['logout'];
          header('Location: login.php');

}

// Далее выводим форму отмечая элементы с ошибками классом error
// и задавая начальные значения элементов ранее сохраненными.
?>
    </form>
  </body>
</html>
