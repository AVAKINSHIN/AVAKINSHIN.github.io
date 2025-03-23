<html>
  <head>
    <meta charset="utf-8" />
    <style>
        input {
                background-color: blue;
                color: white;
        }
        textarea {
                background-color: red;
                color: white;
                width: 500px;
                height: 500px;
         }
    </style>
  </head>
  <body>
    <form action="index.php" method="POST">
      <ol>
        <li><label for="fio">ФИО </label><input type="text" placeholder="ФИО" name="fio" /></li>
        <li><label for="phone">Номер телефона </label><input type="tel" placeholder="Номер телефона" name="phone" /></li>
        <li><label for="email">Email-адрес </label><input type="email" placeholder="Email-адрес" name="email" /></li>
        <li><label for="date">Дата рождения </label><input type="text" placeholder="Дата рождения" name="year"/></li>
        <li><label for="gender">Пол</label> <input type="radio" value="М" name="gender">Мужской</input>
                <input type="radio" value="Ж" name="gender">Женский</input></li>
        <li>Любимый язык программирования<br />
          <select size="12" name="abilities[]" multiple="multiple">
            <option value="7">Pascal</option>
            <option value="8">C</option>
            <option value="9">C++</option>
            <option value="10">JavaScript</option>
            <option value="3">PHP</option>
            <option value="4">Python</option>
            <option value="5">Java</option>
            <option value="6">Haskel</option>
            <option value="11">Clojure</option>
            <option value="12">Prolog</option>
            <option value="1">Scala</option>
            <option value="2">GO</option>
          </select>
        </li>
        <li><label for="biography">Биография</label><br/><textarea type="text" name="biography"></textarea></li>
        <li><label for="accept">С контрактом ознакомлен(а)<br/></label><input type="checkbox" name="accept" value="1">Да</input>
        <input type="checkbox" name="accept" value="0">Нет</input></li>
      </ol>
      <button>Сохранить</button>
    </form>
  </body>
</html>
