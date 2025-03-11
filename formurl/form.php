<html>
    <form action="index.php" method="POST">
      <ol>
        <li><label for="name">ФИО </label><input type="text" placeholder="ФИО" id="name" /></li>
        <li><label for="telephone">Номер телефона </label><input type="tel" placeholder="Номер телефона" id="telephone" /></li>
        <li><label for="email">Email-адрес </label><input type="email" placeholder="Email-адрес" id="email" /></li>
        <li><label for="date">Дата рождения </label><input type="date" placwholder="Дата рождения" id="date"/></li>
        <li><p>Пол</p> <button>Мужской</button> <button>Женский</button></li>
        <li>Любимый язык программирования<br />
          <select size="11" name="select" multiple>
            <option>Pascal</option>
            <option>C</option>
            <option>C++</option>
            <option>JavaScript</option>
            <option>PHP</option>
            <option>Python</option>
            <option>Java</option>
            <option>Haskel</option>
            <option>Clojure</option>
            <option>Prolog</option>
            <option>Scala</option>
          </select>
        </li>
        <li><p>Биография</p><textarea></textarea></li>
        <li><p>С контрактом ознакомлен(а)</p><button>ДА</button><button>НЕТ</button></li>
      </ol>
      <button>Сохранить</button>
    </form>
</html>
