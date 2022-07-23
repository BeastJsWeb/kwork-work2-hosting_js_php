<?php
include 'init.php';

$theme_id = 0; $subscription = false; $comments = array();

if (isset($_SESSION['USER_ID']))
{
  $query = mysqli_query($db, "SELECT `id` FROM `subscription` WHERE `theme_id` = '".$theme_id."' and `user_id` = '".$_SESSION['USER_ID']."'") or die('ERROR BD');

  if (mysqli_num_rows($query))
  {
    $subscription = true;
  }
}

$query = mysqli_query($db, "SELECT t1.*, t2.name, t2.middlename, t2.surname FROM `comment` AS t1 LEFT JOIN `users` AS t2 ON t2.id = t1.user_id WHERE t1.theme_id = '".$theme_id."' ORDER BY t1.time_add ASC");

if ($count = mysqli_num_rows($query))
{
  while ($row = mysqli_fetch_assoc($query))
  {
    $row['childs'] = array();
    $comments[$row['id']] = $row;
  }
}

foreach ($comments as $k => &$v)
{
  if ($v['parent_id'] != 0)
  {
    $comments[$v['parent_id']]['childs'][] =& $v;
  }
}

unset($v);

foreach ($comments as $k => $v)
{
  if ($v['parent_id'] != 0)
  {
    unset($comments[$k]);
  }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video{margin:0;padding:0;border:0;font-family:Arial, Helvetica, sans-serif;vertical-align:inherit;list-style-type:none;}
      html, body{color:#000;font-size:16px;}
      @media (max-width: 640px) {
        html, body{font-size:14px;}
      }


      *{-webkit-box-sizing: border-box;box-sizing: border-box;}


      .container{max-width:1250px;margin:0 auto;padding:1%;background-color:#FFF;}
      .clear{position:relative;display:block;clear:both;}

      .frame-a {
        border: 2px solid #673434;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
      }


      /* Верхнее меню */

      #top_menu_wide, #top_menu_narrow{width: 100%; position: relative; display:none;}

      #top_menu_1, #top_menu_2, #top_menu_3, #top_menu_4 {position: absolute; bottom:5%; text-align: center; left:0; right:0; display:none;}
      #top_menu_1 a, #top_menu_2 a, #top_menu_3 a, #top_menu_4 a {text-decoration:none;color: #FFF;}

      .scope {width:100%; height:auto;}



      @media screen and (min-width:640px){
      #top_menu_wide {display:block;}
      #top_menu_narrow {display: none;}
      #top_menu_1 {display:block;font-size:16px; font-weight: bold;}
      #top_menu_2 {display: none;}
      #top_menu_3 {display: none;}
      #top_menu_4 {display: none;}
      }


      @media screen and (min-width:535px) and (max-width:640px){
      #top_menu_wide {display:block;}
      #top_menu_narrow {display: none;}
      #top_menu_1 {display: none;}
      #top_menu_2 {display:block;font-size:14px; font-weight: normal;}
      #top_menu_3 {display: none;}
      #top_menu_4 {display: none;}
      }



      @media screen and (min-width:385px) and (max-width:535px){
      #top_menu_wide {display:block;}
      #top_menu_narrow {display: none;}
      #top_menu_1 {display: none;}
      #top_menu_2 {display: none;}
      #top_menu_3 {display: block; font-size:14px; font-weight: normal;}
      #top_menu_4 {display: none;}
      }




      @media screen and (min-width:280px) and (max-width:385px){
      #top_menu_wide {display: none;}
      #top_menu_narrow {display: block;}
      #top_menu_1 {display: none;}
      #top_menu_2 {display: none;}
      #top_menu_3 {display: none;}
      #top_menu_4 {display:block;font-size:14px; font-weight: normal;}
      }

      /* Конец - Верхнее меню */


      /* Панель горизонтальная*/

      .grid {
          display: grid;
          grid-template-columns: auto 1fr auto;
      }


      #menu-more {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .el-panel {
        display: flex;
          height: 40px;
        align-items: center;
          justify-content: center;
        padding: 0 5px;
          margin: 0 5px;
          border: 2px solid #673434;
          border-radius: 5px;
          color: #673434;
          font-weight: bold;
      }

      /* Панель горизонтальная Конец */


      /* Левое меню */
      .left-menu a {text-decoration: none;}


      .toggleMenu {
          display:  inline-block;
          background: none;
          padding: 0px;
        color: #795548;
          font-weight: bold;
      }


      .nav {
          list-style: none;
          background: #420615;
          display: none;
        width:300px;
      }
      .nav:before,
      .nav:after {
          content: " ";
          display: table;
      }
      .nav:after {
          clear: both;
      }
      .nav ul {
          list-style: none;
          display: block;
          width: 100%;
      }
      .nav a {
          padding: 10px 15px;
          color:#fff;
      }
      .nav li {
          position: relative;
      }
      .nav > li {
          float: none;
          border-top: 1px solid #f1d50a;
      }
      .nav > li > .parent {
          background-image: url("/detal/downArrow.png");
          background-repeat: no-repeat;
          background-position: 95% 50%;
      }
      .nav > li > a {
          display: block;
      }
      .nav li  ul {
          position: absolute;
          left: -9999px;
      }
      .nav > li.hover > ul {
          left: 0;
      }
      .nav li li.hover ul {
          left: 100%;
          top: 0;
      }
      .nav li li a {
          display: block;
          background: #673434;
          position: relative;
          z-index:100;
          border-top: 1px solid  #796b6b;
        border-radius: 10px;
        margin: 3px;
      }
      .nav li li li a {
          background:#249578;
          z-index:200;
          border-top: 1px solid #1d7a62;
      }
      .nav li li .parent {
          background-image: url("/detal/downArrow.png");
          background-repeat: no-repeat;
          background-position: 95% 50%;
      }
      .nav > li.hover > ul , .nav li li.hover ul {
          position: static;
      }

      @media (max-width: 315px) {
          .nav {
          width:255px;
          }
      }


      /* Левое меню конец */


      /* Поиск */


      .form-search {
          background: none;
          border-radius: 5px;
          padding: 4px;
      }

      .form-search input {
          width: 100px;
        height: 40px;
          font-size: 16px;
          transition: all .2s ease-out;
          padding: 0 40px 0 6px;
          background-image: url("/my-design/search.png");
          background-repeat: no-repeat;
          background-position: 100% 50%, 100% 100%;
          border-radius: 3px;
          border: 2px solid #795548;
          box-shadow: none;
      }

      .form-search input:focus {
          width: 100%;
          color: #000;
          background-image: url("search.png");
        border: 2px solid #ebd053;
      }


      .grid-form input {
          display: block;
          margin-left: auto;
      }


        ::-webkit-input-placeholder {
          color: #795548;
      }
        ::-moz-placeholder { /* Firefox 19+ */
          color: #795548;
      }
        :-ms-input-placeholder {
          color: #795548;
      }
        :-moz-placeholder { /* Firefox 18- */
          color: #795548;
      }


      @media (max-width: 640px) {
          .form-search input {
          height: 30px;
          font-size: 14px;
          }

        .el-panel {
            height: 30px;
        }
      }


      @media (max-width: 485px) {
          #el4-panel {
          display:none;
          }
      }


      @media (min-width: 375px) {
          #el2-panel {
          display:none;
          }
      }


      @media (max-width: 300px) {
          .form-search input {
          width:80px;
          }
      }


      .decor_a {
        display:flex;
      }

      .decor_b {
        display: flex;
        align-items: center;
        justify-content: center;
      }


      .decor_left {
        margin-left:10px;
      }

      #exit {
        display:none;
      }



      .position {
        position:absolute;
        border-radius: 10px;
        z-index: 5;
      }



      /* Конец Поиск */


      /* Панель интересов */
      .interest {
        display: flex;
          height: 40px;
        align-items: center;
          justify-content: center;
        padding: 0 5px;
          margin: 0 5px;
          border: 2px solid #673434;
          border-radius: 5px;
          color: #673434;
          font-weight: bold;
      }

      #interest {display: flex; align-items: center; justify-content: center; flex-wrap: wrap;}
      #interest > div {text-align:center; margin:10px 0;}
      #interest a {text-decoration: none;}


      .interest-v {
        display: flex;
        position: relative;
        height: 40px;
        align-items: center;
          justify-content: center;
        padding: 5px;
          margin: 5px;
          border: 2px solid #673434;
          border-radius: 5px;
        width: 240px;
      }

      .interest-v a {text-decoration: none; color:#673434;}


      details {
      }
      details div {
          position: absolute;
        margin-top: 15px;
          z-index: 10;
          background-color: white;
          left: 1%;
          width: 250px;
      }


      @media (max-width: 375px) {
          #interest {
        display:none;
          }
      }

      </style>
      <link href="commentsAndModal.css" rel="stylesheet" type="text/css">
  </head>

  <body id='body'>
  <header>
  <div class="frame-a"> <!-- header страницы  -->

  <div id="top_menu_wide">
    <a href="/"><img src="/my-design/logo-3-tmp.jpg" alt="Логотип клуба 1" class="scope"></a>


    <div id="top_menu_1">
      <a href="/s1.html">один&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <a href="/s2.html">Два&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Три&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Четыре&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Пять</a>
    </div>


    <div id="top_menu_2">
      <a href="/s1.html">Один&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <a href="/s2.html">Два&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Три&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Четыре&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Пять</a>
    </div>


    <div id="top_menu_3">
      <a href="/s1.html">Один&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <a href="/s2.html">Два&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Три&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Четыре</a>
    </div>

  </div> <!-- top_menu_wide -->


  <div id="top_menu_narrow">
    <a href="/"><img src="/my-design/logo-42-tmp.jpg" alt="Логотип клуба 2" class="scope"></a>


    <div id="top_menu_4">
      <a href="/s1.html">Один&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <a href="/s2.html">Два&nbsp;&nbsp;&nbsp;&nbsp;</a>
      <a href="/s2.html">Три</a>
    </div>

  </div> <!-- top_menu_narrow -->


          <!-- Панель меню, поиска и регистрации -->
  <div class="grid">
    <div id="menu-more"> <!-- Меню и Ещё-->

      <div class="decor_a" > <!-- Элемент меню (левое) -->

    <nav class="left-menu"><a class="toggleMenu" href="#"> <!-- Левое  меню  -->
    <div class="el-panel">МЕНЮ</div></a>

  <ul class="nav position" > <!-- Ширина меню -->
    <li class="akk_metka"><a href="#">Начало</a>
      <ul class="decor_left">
        <li>
          <a href="zero.html">Миссия</a>
        </li>
        <li>
          <a href="#">Дорога</a>
        </li>
        <li>
          <a href="#">Друзья</a>
        </li>
        <li>
          <a href="#">Дела</a>
        </li>
        <li>
          <a href="#">Коллектив</a>
        </li>
      </ul>
    </li>
      <li>
          <a href="#">Новости</a>
    </li>
      <li>
          <a href="#">Новости местные</a>
    </li>
    <li class="akk_metka"><a href="#">Проекты</a>
      <ul class="decor_left">
        <li>
          <a href="zero.html">Разные проекты</a>
        </li>
        <li>
          <a href="#">Инвестиции</a>
        </li>
        <li>
          <a href="#">услуг</a>
        </li>
        <li>
          <a href="#">строительство и недвижимость</a>
        </li>
        <li>
          <a href="#">искусства</a>
        </li>
        <li>
          <a href="#">пищепром</a>
        </li>
        <li>
          <a href="#">особые</a>
        </li>
      </ul>
    </li>


    <li class="akk_metka"><a href="#">Франция</a>
      <ul class="decor_left">
        <li>
          <a href="zero.html">Автомобили</a>
        </li>
        <li>
          <a href="#">редиты</a>
        </li>
        <li>
          <a href="#">Консульты</a>
        </li>
        <li>
          <a href="#">Дежурные</a>
        </li>
        <li>
          <a href="#">кофе</a>
        </li>
        <li>
          <a href="#">магазина</a>
        </li>
        <li>
          <a href="#">лаборатории</a>
        </li>
        <li>
          <a href="#">кафе</a>
        </li>
        <li>
          <a href="#">салон красоты</a>
        </li>
        <li>
          <a href="#">гостиницы</a>
        </li>
        <li>
          <a href="#">Особые предложения</a>
        </li>
      </ul>
    </li>


    <li class="akk_metka"><a href="#">Создание</a>
      <ul class="decor_left">
        <li>
          <a href="zero.html">название</a>
        </li>
        <li>
          <a href="#">как сделать</a>
        </li>
        <li>
          <a href="#">как усилить</a>
        </li>
        <li>
          <a href="#">Отдельные решения</a>
        </li>
        <li>
          <a href="#">Работа</a>
        </li>
        <li>
          <a href="#">Методология</a>
        </li>
        <li>
          <a href="#">Продажи</a>
        </li>
        <li>
          <a href="#">личности</a>
        </li>
        <li>
          <a href="#">Жизнь</a>
        </li>
        <li>
          <a href="#">карьера</a>
        </li>
        <li>
          <a href="#">ошибки</a>
        </li>
        <li>
          <a href="#">Моя жизнь</a>
        </li>
        <li>
          <a href="#">Коллекция</a>
        </li>
      </ul>
    </li>

      <li>
          <a href="#">Личная жизнь</a>
    </li>

    <li class="akk_metka"><a href="#">Воспоминание</a>
      <ul class="decor_left">
        <li>
          <a href="zero.html">Другие люди</a>
        </li>
        <li>
          <a href="#">За рубежом</a>
        </li>
        <li>
          <a href="#">Успех</a>
        </li>
      </ul>
    </li>

      <li>
          <a href="#">Партнеры</a>
    </li>

      <li>
          <a href="#">Представители</a>
    </li>

      <li>
          <a href="#">Мой сервис</a>
    </li>

      <li>
          <a href="#">Контакты</a>
    </li>

      <li>
          <a href="#">Подписка</a>
    </li>
  </ul>
  </nav> <!-- Левое  меню Конец -->

  <script src="/js/left-menu.js"></script>

      <div class="el-panel" id="el2-panel"> <!--  Ещё -->
    <!-- Панель Интересов вертикальная -->
  <details>
    <summary>Ещё</summary>
    <div>
    <div class="interest-v" ><a href="#">Увеличить</a></div>
    <div class="interest-v" ><a href="#">Сделать</a></div>
    <div class="interest-v" ><a href="#">Простой чемодан</a></div>
    <div class="interest-v" ><a href="#">Консультанты</a></div>
    <div class="interest-v" ><a href="#">Трения</a></div>
    <div class="interest-v" ><a href="#">Неурядицы</a></div>
    <div class="interest-v" ><a href="#">Отдых</a></div>
    </div>
  </details>
    <!-- Панель Интересов вертикальная Конец -->


      </div> <!-- Конец Еще -->

      </div>  <!-- Меню и Ещё-->

  </div>

    <div class="grid-form"> <!-- Поиск -->
      <form class="form-search" method="POST" action="search-2.php" target="_blank">
            <input type="text" name="vopros" placeholder="Найти" value="">
          </form>
    </div>

                      <!-- Вход, Регистрация и Выход -->
      <div class="decor_b">
      <div class="el-panel btns_login" id='btn__login' >Вход</div>  <!-- Вход -->
          <div class="el-panel btns_reg" id="el4-panel">Регистрация</div>  <!-- Регистрация -->
          <div class="el-panel" id="exit">Выход</div>  <!-- Выход -->
    </div>


      </div> <!-- Панель меню, поиска и регистрации Конец -->



    <!-- Панель Интересов горизонтальная -->
    <div id="interest">
          <div>
          <a href="#">
            <div class="interest">Увеличить</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Сделать</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Простои</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Советы</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Трения</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Неурядицы</div>
          </a>
          </div>
          <div>
          <a href="#">
            <div class="interest">Отдых</div>
          </a>
          </div>
  </div> <!-- Панель Интересов горизонтальная Конец -->


  </div> <!-- header страницы Конец -->

  </header>


          <p>Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы Это контент стораницы </p>




  <br />



    <main>Контент</main>
    <footer>..</footer>







  </div> <!-- container -->


















    <div id="page_wrapper">
    <div>
      <div class="comments comments--ready">
      <div id='comments__body' class="comments__body">
        <div class="comments__title" data-count="5">
        <div class="comments__title"><span id="comment_count_all"><?php echo $count; ?></span>&nbsp;комментариев</div>
        </div>
        <?php
        if ($count > 10)
        {
          ?>
          <div class="comments__header">
          <div id="comment_pseudo_form_up" class="comments_pseudo_form --show" data-inversion="-1" onclick="a(false);">
            <div class="comments_pseudo_form__text">Написать комментарий...</div>
            <div class="comments_pseudo_form__buttons">
            <div class="comments_pseudo_form__button">
              <img src="/my-design/knv.jpg" width="17" height="17">
            </div>
            </div>
          </div>
          </div>
          <?php
        }
        ?>
        <div class="comments__content_wrapper <?php echo $count < 10 ? 'comments__content_wrapper--open' : 'comments__content_wrapper--open'; ?>">
        <div class="comments__content_wrapper__container">
          <div id="comment_box" class="comments__content">
          <?php
          function comments(array $comments, $level = 0, $next = 0, $arr = array())
          {
            foreach ($comments as $key => $row)
            {
              $level_up = $level + 1;

              $branch = '';

              $next_comment = isset($comments[$key+1]) ? true : false;

              $arr_next = '';

              if ($level_up > 1)
              {
                for ($n = 2; $n <= $level_up; $n++)
                {
                  if ($n == $level_up)
                  {
                    if ($next_comment)
                    {
                      $branch .= '<div class="comment__branch"></div>';
                      $arr_next .= '1#';
                    }
                    else
                    {
                      $branch .= '<div class="comment__branch comment__branch--no-border"></div>';
                      $arr_next .= '2#';
                    }
                  }
                  else
                  {
                    if (!in_array($n, $arr))
                    {
                      $branch .= '<div class="comment__branch comment__branch--hidden"></div>';
                      $arr_next .= '0#';
                    }
                    else
                    {
                      $branch .= '<div class="comment__branch"></div>';
                      $arr_next .= '1#';
                    }
                  }
                }
              }

              ?>
              <div
                id="comment_id_<?php echo $row['id']; ?>"
                class="
                  comment
                  <?php echo $level_up > 1 ? 'comment--reply' : ''; ?>
                "
                data-next="<?php echo $arr_next; ?>"
                data-image="<?php echo !empty($row['image']) ? $row['image'] : ''; ?>"
                data-id="<?php echo $row['id']; ?>"
                data-level="<?php echo $level + 1; ?>"
                style="--level: <?php echo $level + 1; ?>;"
                data-parent="<?php echo $row['parent_id']; ?>"
              >
                <div class="comment__branches">
                <?php
                echo $branch;

                $row['message'] = replaceBBCode($row['message']);

                $ava = genUserAvatar($row['name'], $row['surname']);
                ?>
                </div>
                <div class="comment__content">
                <span class="comment__avatar">
                  <div class="avatar__bg" style="color: <?php echo $ava['color']; ?>; background-color: <?php echo $ava['bg']; ?>;"><span><?php echo $ava['name']; ?></span></div>
                </span>
                <a class="comment__author" href="#"><?php echo $row['name']; ?> <?php echo $row['middlename']; ?> <?php echo $row['surname']; ?></a>
                <div class="comment__icon" onclick="form_complaint_id.value = '<?php echo $row['id']; ?>'; form_complaint.reset(); " title="Пожаловаться">
                  <img src="/my-design/flag.jpg" height="13" width="13">
                </div>
                <div class="comment__break"></div>
                <span class="comment__detail">
                  <time class="time" title="<?php echo $row['time_add']; ?>"><?php echo relDateTime($row['time_add']); ?></time>
                </span>
                <div class="comment__text" style="margin-top: 12px;">
                  <div id="comment_text_<?php echo $row['id']; ?>"><?php echo $row['message']; ?></div>
                </div>

                <?php
                if (!empty($row['image']))
                {
                  ?>
                  <div id="comment_image_<?php echo $row['id']; ?>" class="comment__attaches comment__media">
                    <div class="andropov_image andropov_image--zoomable" style="max-height: 300px;max-width: 410px;">
                    <div class="andropov_image__inner" style="padding-bottom: 0px; background: transparent none repeat scroll 0% 0%;"><img onclick="view_image(this);" src="/upload/<?php echo $row['image']; ?>"></div>
                    </div>
                  </div>
                  <?php
                }
                ?>

                <div class="comment__action">Ответить</div>
                <?php
                if (@$row['user_id'] == @$_SESSION['USER_ID']) {
                    ?>
                    <details
                      id="dropdownMenuButton1"
                      class="
                        ddMenu
                        <?php
                          if (
                            end($row['childs'])
                            || time() - strtotime($row['time_add']) > 300
                          ) {
                        ?>
                        hidden
                        <?php } ?>
                      "
                      data-bs-toggle="dropdown"
                    >
                      <summary><img src="/my-design/try.jpg" width="16" height="16"></summary>
                      <ul class="dropdown-menu">
                        <li><button class="dropdown-item comment-edit" >Редактировать</button></li>
                        <li><button class="dropdown-item" onclick="ajax('delete.php?id=<?php echo $row['id']; ?>');">Удалить</button></li>
                      </ul>
                    </details>
                    <?php
                }

                 $vote = $row['plus'];

                ?>
                <div id="comment_vote_<?php echo $row['id']; ?>" class="vote <?php echo $vote > 0 ? 'vote--sum-positive' : ($vote < 0 ? 'vote--sum-negative' : ''); ?>">
                  <div class="vote__button vote__button--minus" onclick="ajax('rating.php?value=0&id=<?php echo $row['id']; ?>'); return false;">
                    <img src="/my-design/lado-minus.jpg" width="20" height="20">
                  </div>
                  <div id="comment_rating_<?php echo $row['id']; ?>" class="vote__value"><?php echo $vote; ?></div>
                  <div class="vote__button vote__button--plus" onclick="ajax('rating.php?value=1&id=<?php echo $row['id']; ?>'); return false;">
                    <img src="/my-design/lado-plus.jpg" width="20" height="20">
                  </div>
                </div>
                <div class="comment__break"></div>
                <div class="comment__expand-branch"></div>
                </div>
              </div>
              <?php

              if (!empty($row['childs']))
              {
                $next = $next_comment ? $next + 1 : $next;

                if ($next_comment) { $arr[] = $level_up; } else { delete_value_from_array($level_up, $arr); }

                comments($row['childs'], $level + 1, $next, $arr);
              }
            }
          }

          comments($comments);
          ?>

          </div>


          <form id="comment_form_data" autocomplete="off">
            <input type="hidden" name="theme_id" value="<?php echo $theme_id; ?>">
            <input id="comment_form_data_reply" type="hidden" name="reply_id" value="0">
            <input id="comment_form_data_next" type="hidden" name="next" value="">
            <input id="comment_form_data_media" type="hidden" name="media" value="">
            <input id="comment_form_data_level" type="hidden" name="level" value="0">
            <input id="comment_form_data_message" type="hidden" name="message" value="">
            <input id="comment_form_data_up" type="hidden" name="up" value="0">
            <input id="comment_form_data_subscription" type="hidden" name="subscription" value="0">
          </form>

          <div class="comments__footer">
          <div id="comment_pseudo_form" class="comments_pseudo_form " data-inversion="1" onclick="a();">
            <div class="comments_pseudo_form__text">Написать комментарий...</div>
            <div class="comments_pseudo_form__buttons">
            <div class="comments_pseudo_form__button">
              <img src="/my-design/knv.jpg" width="17" height="17">
            </div>
            </div>
          </div>
          <div id="comment_form" class="comments_form">
            <div class="comments_form__editor">
            <div id="comment_input_box" class="thesis thesis--empty">
              <div id="comment_placeholder" class="thesis__placeholder">Написать комментарий...</div>
              <div class="thesis__content">
              <div>
                <div>
                  <p
                    id="comment_input_message"
                    contenteditable="true"
                    style="display:inline-block; width: 100%;"
                  ></p>
                  <div></div>
                </div>
              </div>
              </div>
              <div id="comment_uploader" class="andropov_uploader">
              </div>
              <div class="thesis__panel">
              <div class="thesis__attaches">
                <div class="thesis__upload_file" onclick="site_fileload(this);">
                  <img src="/my-design/knv.jpg" width="17" height="17">
                </div>
                <input class="site_load_file" style="display: none;" accept="image/*" name="loadfile" type="file" />
              </div>
              <?php
              if (isset($_SESSION['USER_ID']))
              {
                if (!$subscription)
                {
                  ?>
                  <div id="comment_subscription_box" class="form-check">
                    <input type="checkbox" class="form-check-input" value="1" id="comment_subscription_input" onclick="if (this.checked == false) { comment_form_data_subscription.value = 0; } else { comment_form_data_subscription.value = 1; }">
                    <label id="comment_subscription_label" class="form-check-label" for="comment_subscription_input">Следить за темой</label>
                  </div>
                  <?php
                }
              }
              ?>
              <div id="comment_cancel"></div>
              <div id="comment_send_button" class="v-button--disabled" onclick="b();">
                <span id="comment_send_button_text">Отправить</span>
              </div>
              </div>
            </div>
            </div>
          </div>
        </div>
        </div>

        <div>
          <div class="ui-button" onclick="d();">
          Читать все <?php echo $count; ?> комментариев
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>

    <div class="modal" id="modal_register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Регистрация</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_register" novalidate="" autocomplete="off">
        <div class="row">
          <div>
            <label class="form-label">Имя <span class="text-danger">*</span></label>
            <input type="text" class="form-validate" id="form_register_name" maxlength="30" name="name" placeholder="">
            <div id="form_register_name_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label class="form-label">Фамилия <span class="text-danger">*</span></label>
            <input type="text" class="form-validate" id="form_register_surname" maxlength="30" name="surname" placeholder="">
            <div id="form_register_surname_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label class="form-label">Отчество</label>
            <input type="text" class="form-validate" id="form_register_middlename" maxlength="30" name="middlename" placeholder="">
            <div id="form_register_middlename_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label  class="form-label">Пол</label>
            <select class="form-validate" name="gender" id="form_register_gender">
            <option value="0">Выбрать...</option>
            <option value="1">Мужской</option>
            <option value="2">Женский</option>
            <option value="3">Неопределившийся</option>
            </select>
            <div id="form_register_gender_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-validate" id="form_register_email" maxlength="100" name="email" placeholder="you@example.ru">
            <div id="form_register_email_message" class="invalid-feedback"></div>
          </div>

          <div>
            <label  class="form-label">Территория проживания</label>
            <div id='form__country--onload' ></div>
          </div>

          <div>
            <label  class="form-label">Вид деятельности</label>
            <div id='form__work--onload' ></div>
          </div>

          <div>
            <label  class="form-label">Должность</label>
            <div id='form__post--onload' ></div>
          </div>

          <div>
            <label class="form-label">Компания</label>
            <input type="text" class="form-validate" id="form_register_company" maxlength="50" name="company" placeholder="">
            <div id="form_register_company_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label class="form-label">О себе</label>
            <textarea id="form_register_about" class="form-validate" maxlength="500" name="about"></textarea>
            <div id="form_register_about_message" class="invalid-feedback"></div>
          </div>

          <div>
            <div class="form-check">
            <input type="checkbox" class="form-check-input form-validate" name="rules" value="1" id="form_register_rules">
            <label id='form_register_rules_label' class="form-check-label" for="form_register_rules">Соглашаюсь с обработкой персональных данных</label>
            </div>
          </div>

          <div class="recaptcha__reg"><div id="register_form_recaptcha"></div></div>

          <div id="register_form_message" class="d-none"></div>
        </div>
        <div>
        <hr>
        <div class="modal__footer">
          <div>Есть аккаунт? <a href="#" class="btns_login">Войти</a></div>
          <button class="btn btn-primary" >Зарегистрироваться</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>
    </div>

    <div class="modal" id="modal_login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Вход в аккаунт</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_login" novalidate="" autocomplent="off">
        <div class="row">
          <div>
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-validate" id="form_login_email" maxlength="100" name="email">
            <div id="form_login_email_message" class="invalid-feedback"></div>
          </div>
          <div>
            <label class="form-label">Пароль <span class="text-danger">*</span></label>
            <input type="password" class="form-validate" id="form_login_password" maxlength="30" name="password">
            <div id="form_login_name_message" class="invalid-feedback"></div>
          </div>
          <div class="recaptcha__log"><div id="login_form_recaptcha"></div></div>
          <div id="login_form_message" class="d-none"></div>
        </div>
        <hr>
        <div class="modal__footer">
          <div>
            Нет аккаунта?
            <a href="#" class="btns_reg" >Регистрация</a>
            <a href="#" id='btn_rest' >Забыли пароль</a>
          </div>
          <button id='form_login--submit' class="btn btn-primary" >Войти</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>

    <div class="modal" id="modal_restore" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Создание нового пароля</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_restore" novalidate="" autocomplent="off">
        <div class="row">
          <div>
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-validate" id="form_restore_email" maxlength="100" name="email">
            <div id="form_restore_email_message" class="invalid-feedback"></div>
          </div>
          <div class="recaptcha__res"><div id="restore_form_recaptcha"></div></div>
          <div id="restore_form_message" class="d-none"></div>
        </div>
        <hr>
        <div class="modal__footer">
          <div>
          </div>
          <button class="btn btn-primary" >Отправить письмо с паролем</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>

    <div class="modal" id="modal_complaint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Пожаловаться</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_complaint" novalidate="" autocomplent="off">
        <input id="form_complaint_id" type="hidden" name="id" value="">
        <input type="hidden" name="theme_id" value="<?php echo $theme_id; ?>">
        <div class="form__selector">
          <div>
            <select class="form-validate" name="type">
            <?php
            foreach ($complaint as $key => $value)
            {
              ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
            }
            ?>
            </select>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="ajax('complaint.php', '#form_complaint');">Отправить</button>
      </div>
      </div>
    </div>
    </div>


  <div id="modal_preview_image" class="modal-image">
    <span class="close" onclick="close_view_image();">&times;</span>
    <img class="modal-content-image" id="preview_image">
  </div>
  <div id="ajax_loading"><div><div role="status"></div></div></div>

    <script>var user_id = <?php echo isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 0; ?>;</script>
    <script src="main.js"></script>
  </body>
</html>