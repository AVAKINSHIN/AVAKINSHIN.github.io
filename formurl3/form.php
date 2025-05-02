<?php
        function checkLanguage($num)
        {
                global $languages;
                print(in_array($num, $languages) ? 'selected' : '');
        }
?>
<html>
  <head>
    <style>
        input.n {
                background-color: blue;
                color: white;
        }
        textarea {
                width: 500px;
                height: 500px;
        }
        textarea.n {
                background-color: red;
                color: white;
        }
        .error {
                border: 2px solid red;
                color: white;
                background-color: #781F19;
        }
        .save {
                border: 2px solid blue;
                background-color: green;
                color: white;
        }
    </style>
  </head>
  <body>
   <?php
        if (!empty($messages))
        {
                print('<div id="messages">');
                foreach ($messages as $m)
                {
                        if ($m == '1')
                        {
                                print('<div class="save">Спасибо, результаты сохранены</div>');
                        }
                        else
                         {
                                print($m);
                        }
                }
                print('</div>');
        }
    ?>
    <form action="" method="POST">
      ФИО:<input name="fio"<?php print'class=';if($errors['fio']){print '"error"';}else{print '"n"';}?>value="<?php print $values['fio'];?>"/><br />
      Номер телефона: <input placeholder="+7(XXX) XXX-XX-XX" name="phone" value="<?php print($values['phone'])?>"
        <?php print 'class='; if($errors['phone']) {print '"error"';} else{print '"n"';}?> /><br />
      E-mail: <input placeholder="example@mail.com" name="email" value="<?php print($values['email'])?>"
        <?php print 'class='; if($errors['email']) {print '"error"';} else{print '"n"';}?> /><br />
      Год рождения: <input placeholder="1990" name="year" value="<?php print($values['year'])?>"
        <?php print 'class='; if($errors['year']) {print '"error"';} else{print '"n"';}?> /><br />
      <div <?php if($errors['gender']) {print 'class="error"';}?>>Выберите ваш пол:</div>
      <input type="radio" name="gender" value="М" <?php if ($values['gender']=='М'){print($values['gender']?'checked':'');}?> />Мужской
      <input type="radio" name="gender" value="Ж" <?php if ($values['gender']=='Ж'){print($values['gender']?'checked':'');}?> />Женский<br />
      Выберите любимый(-ые) язык(-и) программирования:<br />
            <select multiple name="language[]" size="11" <?php if($errors['language']) {print 'class="error"';}?>>
                <option <?php checkLanguage(1)?> value="1">Pascal</option>
                <option <?php checkLanguage(2)?> value="2">C</option>
                <option <?php checkLanguage(3)?> value="3">C++</option>
                <option <?php checkLanguage(4)?> value="4">JavaScript</option>
                <option <?php checkLanguage(5)?> value="5">PHP</option>
                <option <?php checkLanguage(6)?> value="6">Python</option>
                <option <?php checkLanguage(7)?> value="7">Java</option>
                <option <?php checkLanguage(8)?> value="8">Haskel</option>
                <option <?php checkLanguage(9)?> value="9">Clojure</option>
                <option <?php checkLanguage(10)?> value="10">Prolog</option>
                <option <?php checkLanguage(11)?> value="11">Scala</option>
            </select><br />
      Расскажите о своей жизни (биография):<br />
<textarea name="biography" <?php print'class=';if($errors['biography']){print '"error"';}else{print '"n"';}?>><?php print $values['biography'];?></textarea>
      <div <?php if($errors['accept']) {print 'class="error"';}?>>С контрактом ознакомлен(а)<br />
        <input type="radio" name="accept" value="1" <?php if ($values['accept']=='1'){print($values['accept']?'checked':'');}?> />Да
        <input type="radio" name="accept" value="0" <?php if ($values['accept']=='0'){print($values['accept']?'checked':'');}?> />Нет</div>
      <input type="submit" class="n" value="Сохранить" />
    </form>
  </body>
</html>
