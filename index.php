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
    <link href="main.css" rel="stylesheet">
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
      <div class="el-panel btns_login">Вход</div>  <!-- Вход -->
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
    <div class="l-island-bg l-pv-30 lm-pv-15 lm-mt-20">
      <div class="comments comments--ready">
      <div class="comments__body">
        <div class="comments__title l-island-a l-pb-10 lm-pt-30 l-fs-18 l-fw-700 l-mb-15" data-count="5">
        <div class="comments-header__title comments__title"><span id="comment_count_all"><?php echo $count; ?></span>&nbsp;комментариев</div>
        </div>
        <?php
        if ($count > 10)
        {
          ?>
          <div class="comments__header l-island-a l-clear">
          <div id="comment_pseudo_form_up" class="comments_pseudo_form" data-inversion="-1" onclick="a(false);">
            <div class="comments_pseudo_form__text">Написать комментарий...</div>
            <div class="comments_pseudo_form__buttons">
            <div class="comments_pseudo_form__button">
              <svg class="icon icon--v_image" width="20" height="20" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#v_image"></use></svg>
            </div>
            </div>
          </div>
          </div>
          <?php
        }
        ?>
        <div class="comments__content_wrapper <?php echo $count < 10 ? 'comments__content_wrapper--open' : 'comments__content_wrapper--open'; ?>">
        <div class="comments__content_wrapper__container">
          <div id="comment_box" class="comments__content l-island-a">
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
              <div id="comment_id_<?php echo $row['id']; ?>" class="comment <?php echo $level_up > 1 ? 'comment--reply' : ''; ?>" data-next="<?php echo $arr_next; ?>" data-image="<?php echo !empty($row['image']) ? $row['image'] : ''; ?>" data-id="<?php echo $row['id']; ?>" data-level="<?php echo $level + 1; ?>" style="--level: <?php echo $level + 1; ?>;">
                <div class="comment__branches">
                <?php
                echo $branch;

                $row['message'] = replaceBBCode($row['message']);

                $ava = genUserAvatar($row['name'], $row['surname']);
                ?>
                </div>
                <div class="comment__content">
                <span class="comment__avatar" style="background-image: url(\'https://leonardo.osnova.io/2e384afa-663a-ce88-81cd-82136b61e56c/-/scale_crop/64x64/\');">
                  <div class="d-flex justify-content-center align-items-center rounded-circle" style="width: 32px; height:32px;  color: <?php echo $ava['color']; ?>; background-color: <?php echo $ava['bg']; ?>;"><span class="text-uppercase text-center font-weight-bold"><?php echo $ava['name']; ?></span></div>
                </span>
                <a class="comment__author" href="#"><?php echo $row['name']; ?> <?php echo $row['middlename']; ?> <?php echo $row['surname']; ?></a>
                <div class="comment__icon" onclick="form_complaint_id.value = '<?php echo $row['id']; ?>'; form_complaint.reset(); " title="Пожаловаться">
                  <svg height="20" width="20" class="icon icon--v_flag"><use xlink:href="#v_flag"></use></svg>
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
                if (isset($_SESSION['USER_ID']))
                {
                  if ($row['user_id'] == $_SESSION['USER_ID'] && time() - strtotime($row['time_add']) < 300)
                  {
                    ?>
                    <div id="dropdownMenuButton1" class="ddMenu" data-bs-toggle="dropdown" >
                      <svg class="icon icon--v_etc" width="16" height="16" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#v_etc"></use></svg>
                    </div>
                    <ul class="dropdown-menu">
                      <li><button class="dropdown-item comment-edit"><svg height="20" width="20" class="icon icon--v_pen"><use xlink:href="#v_pen"></use></svg> Редактировать</button></li>
                      <li><button class="dropdown-item" onclick="ajax('delete.php?id=<?php echo $row['id']; ?>');"><svg height="20" width="20" class="icon icon--v_pen"><use xlink:href="#v_delete"></use></svg> Удалить</button></li>
                    </ul>
                    <?php
                  }
                }

                $vote = $row['plus'] - $row['minus'];

                ?>
                <div id="comment_vote_<?php echo $row['id']; ?>" class="vote vote--comment <?php echo $vote > 0 ? 'vote--sum-positive' : ($vote < 0 ? 'vote--sum-negative' : ''); ?>">
                  <div class="vote__button vote__button--minus" onclick="ajax('rating.php?value=0&id=<?php echo $row['id']; ?>'); return false;">
                  <svg class="icon icon--v_arrow_down" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#thumbs-down"></use></svg>
                  </div>
                  <div id="comment_rating_<?php echo $row['id']; ?>" class="vote__value"><?php echo $vote; ?></div>
                  <div class="vote__button vote__button--plus" onclick="ajax('rating.php?value=1&id=<?php echo $row['id']; ?>'); return false;">
                  <svg class="icon icon--v_arrow_up" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#thumbs-up"></use></svg>
                  </div>
                </div>
                <div class="comment__break"></div>
                <div class="comment__expand-branch comment__inline-action">Развернуть ветку</div>
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

          <div class="comments__footer l-island-a">
          <div id="comment_pseudo_form" class="comments_pseudo_form " data-inversion="1" onclick="a();">
            <div class="comments_pseudo_form__text">Написать комментарий...</div>
            <div class="comments_pseudo_form__buttons">
            <div class="comments_pseudo_form__button">
              <svg class="icon icon--v_image" width="20" height="20" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#v_image"></use></svg>
            </div>
            </div>
          </div>
          <div id="comment_form" class="comments_form">
            <div class="comments_form__editor">
            <div id="comment_input_box" class="thesis thesis--empty">
              <div id="comment_placeholder" class="thesis__placeholder">Написать комментарий...</div>
              <div class="thesis__content">
              <div class="thesis__block thesis__block--text">
                <div class="herzen">
                  <p id="comment_input_message" class="content_editable" contenteditable="true"></p>
                  <div class="herzen__command_list"></div>
                </div>
              </div>
              </div>
              <div id="comment_uploader" class="thesis__uploaded andropov_uploader">
              </div>
              <div class="thesis__panel">
              <div class="thesis__attaches">
                <div class="thesis__upload_file thesis__attach_something" onclick="site_fileload(this);">
                <svg class="icon icon--v_image" width="20" height="20"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#v_image"></use></svg>
                </div>
                <input class="site_load_file" style="display: none;" accept="image/*" name="loadfile" type="file" />
                <span class="ui_preloader ">
                <span class="ui_preloader__dot"></span>
                <span class="ui_preloader__dot"></span>
                <span class="ui_preloader__dot"></span>
                </span>
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
              <div id="comment_cancel" class="thesis__custom_buttons d-flex gap-2"></div>
              <div class="thesis__user"><img class="thesis__user_avatar"></div>
              <div id="comment_send_button" class="thesis__submit v-button v-button--size-default v-button--blue v-button--disabled" onclick="b();">
                <span id="comment_send_button_text" class="v-button__label">Отправить</span>
              </div>
              </div>
            </div>
            </div>
          </div>
        </div>
        </div>

        <div class="comments__content_wrapper__button l-pt-30">
          <div class="ui-button ui-button--1" onclick="d();">
          Читать все <?php echo $count; ?> комментариев
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>

    <div style="position: absolute; width: 0px; height: 0px; overflow: hidden; z-index: -9999;">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <defs>
  <symbol viewBox="0 0 24 24" id="v_flag"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.286 3.449c.6-.24 1.477-.447 2.714-.447 1.319 0 2.41.437 3.318.8l.053.022c.955.382 1.723.678 2.63.678 1.012 0 1.635-.168 1.973-.303a1.773 1.773 0 00.352-.184.998.998 0 011.672.735v9a.998.998 0 01-.292.705l-.02.019c-.05.048-.143.128-.285.223a3.738 3.738 0 01-.686.354c-.6.24-1.477.446-2.715.446-1.318 0-2.408-.436-3.317-.8l-.053-.02c-.956-.383-1.723-.68-2.63-.68-1.012 0-1.635.169-1.973.304l-.03.012V20a.998.998 0 01-1.994 0V4.75c0-.265.105-.518.292-.705l.02-.019c.05-.048.142-.129.284-.223.163-.109.388-.235.687-.354zm.712 1.864v6.91A8.352 8.352 0 019 12.002c1.319 0 2.41.436 3.318.8l.053.02c.955.383 1.723.68 2.63.68 1.012 0 1.635-.169 1.973-.304l.029-.012v-6.91c-.527.13-1.186.22-2.003.22-1.318 0-2.408-.436-3.317-.8l-.053-.02c-.956-.383-1.723-.68-2.63-.68-1.012 0-1.635.169-1.973.304a2.43 2.43 0 00-.03.012z"></path></symbol>
  <symbol viewBox="0 0 24 24" id="v_etc"><path d="M5 14a2 2 0 100-4 2 2 0 000 4zM12 14a2 2 0 100-4 2 2 0 000 4zM19 14a2 2 0 100-4 2 2 0 000 4z"></path></symbol>
  <symbol fill="currentColor" id="thumbs-down" viewBox="0 0 16 16"><path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856 0 .289-.036.586-.113.856-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a9.877 9.877 0 0 1-.443-.05 9.364 9.364 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964l-.261.065zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a8.912 8.912 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.224 2.224 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.866.866 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1z"/></symbol>
  <symbol fill="currentColor" viewBox="0 0 16 16" id="thumbs-up"><path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/></symbol>
  <symbol viewBox="0 0 24 24" id="v_image"><path d="M8 9.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M7 3a4 4 0 00-4 4v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7zM5 7a2 2 0 012-2h10a2 2 0 012 2v5.252l-1.478-1.477a2 2 0 00-3.014.214L8.5 19H7a2 2 0 01-2-2V7zm11.108 5.19L19 15.08V17a2 2 0 01-2 2h-6l5.108-6.81z"></path></symbol>
          </defs>
        </svg>
    </div>


    <div class="modal fade" id="modal_register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Регистрация</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_register" novalidate="" autocomplete="off">
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="form-label">Имя <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm form-validate" id="form_register_name" maxlength="30" name="name" placeholder="">
            <div id="form_register_name_message" class="invalid-feedback"></div>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Фамилия <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm form-validate" id="form_register_surname" maxlength="30" name="surname" placeholder="">
            <div id="form_register_surname_message" class="invalid-feedback"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Отчество</label>
            <input type="text" class="form-control form-control-sm form-validate" id="form_register_middlename" maxlength="30" name="middlename" placeholder="">
            <div id="form_register_middlename_message" class="invalid-feedback"></div>
          </div>
          <div class="col-md-6">
            <label  class="form-label">Пол</label>
            <select class="form-select form-select-sm form-validate" name="gender" id="form_register_gender">
            <option value="0">Выбрать...</option>
            <option value="1">Мужской</option>
            <option value="2">Женский</option>
            <option value="3">Неопределившийся</option>
            </select>
            <div id="form_register_gender_message" class="invalid-feedback"></div>
          </div>
          <div class="col-12">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control form-control-sm form-validate" id="form_register_email" maxlength="100" name="email" placeholder="you@example.ru">
            <div id="form_register_email_message" class="invalid-feedback"></div>
          </div>

          <div>
            <label  class="form-label">Территория проживания</label>
            <select class="form-select form-select-sm form-validate" id="form_register_location">
            <option value="0">Выбрать...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            </select>
            <div id="form_register_location_message" class="invalid-feedback"></div>
          </div>

          <div>
            <label  class="form-label">Вид деятельности</label>
            <select class="form-select form-select-sm form-validate" id='form_register_activity' >
            <option value="0">Выбрать...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            </select>
            <div id="form_register_activity_message" class="invalid-feedback"></div>
          </div>

          <div>
            <label  class="form-label">Должность</label>
            <select class="form-select form-select-sm form-validate" id='form_register_jobTitle' >
            <option value="0">Выбрать...</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            </select>
            <div id="form_register_jobTitle_message" class="invalid-feedback"></div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Компания</label>
            <input type="text" class="form-control form-control-sm form-validate" id="form_register_company" maxlength="50" name="company" placeholder="">
            <div id="form_register_company_message" class="invalid-feedback"></div>
          </div>
          <div class="col-12">
            <label class="form-label">О себе</label>
            <textarea id="form_register_about" class="form-control form-control-sm form-validate" maxlength="500" name="about"></textarea>
            <div id="form_register_about_message" class="invalid-feedback"></div>
          </div>

          <div class="col-12">
            <div class="form-check">
            <input type="checkbox" class="form-check-input form-validate" name="rules" value="1" id="form_register_rules">
            <label class="form-check-label" for="form_register_rules">Соглашаюсь с обработкой персональных данных</label>
            </div>
          </div>

          <div class="d-flex justify-content-center"><div id="register_form_recaptcha"></div></div>

          <div id="register_form_message" class="d-none"></div>
        </div>
        <div>
        <hr>
        <div class="d-flex align-items-center justify-content-between">
          <div>Есть аккаунт? <a href="#" class="link-info btns_login">Войти</a></div>
          <button class="btn btn-primary" onclick="ajax('reg.php', '#form_register');">Зарегистрироваться</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="modal_login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Вход в аккаунт</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_login" novalidate="" autocomplent="off">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control form-control-sm form-validate" id="form_login_email" maxlength="100" name="email">
            <div id="form_login_email_message" class="invalid-feedback"></div>
          </div>
          <div class="col-12">
            <label class="form-label">Пароль <span class="text-danger">*</span></label>
            <input type="password" class="form-control form-control-sm form-validate" id="form_login_password" maxlength="30" name="password">
            <div id="form_login_name_message" class="invalid-feedback"></div>
          </div>
          <div class="d-flex justify-content-center"><div id="login_form_recaptcha"></div></div>
          <div id="login_form_message" class="d-none"></div>
        </div>
        <hr>
        <div class="d-flex align-items-center justify-content-between">
          <div>
            Нет аккаунта? 
            <a href="#" class="btns_reg link-info" >Регистрация</a>
            <a href="#" id='btn_rest' class="link-info">Забыли пароль</a>
          </div>
          <button class="btn btn-primary" onclick="ajax('log.php', '#form_login');">Войти</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>

    <div class="modal fade" id="modal_restore" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Вход в аккаунт</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_restore" novalidate="" autocomplent="off">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control form-control-sm form-validate" maxlength="100" name="email">
            <div class="invalid-feedback"></div>
          </div>
          <div class="d-flex justify-content-center"><div id="restore_form_recaptcha"></div></div>
          <div class="d-none"></div>
        </div>
        <hr>
        <div class="d-flex align-items-center justify-content-between">
          <div>
          </div>
          <button class="btn btn-primary" onclick="ajax('log.php', '#form_restore');">Отправить письмо с паролем</button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>

    <div class="modal fade" id="modal_complaint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Пожаловаться</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body">
        <form id="form_complaint" novalidate="" autocomplent="off">
        <input id="form_complaint_id" type="hidden" name="id" value="">
        <input type="hidden" name="theme_id" value="<?php echo $theme_id; ?>">
        <div class="d-flex justify-content-center">
          <div class="col-md-8">
            <select class="form-select form-select-sm form-validate" name="type">
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
  <div id="ajax_loading" class="position-fixed h-100 w-100 opacity-50"><div class="d-flex justify-content-center align-items-center w-100 h-100"><div class="spinner-border text-secondary" role="status"></div></div></div>
    
  <script>var user_id = <?php echo isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 0; ?>;</script>
    <?php
    if (!isset($_SESSION['USER_ID']))
    {
      ?>
      <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
      <script type="text/javascript">

        var onloadCallback = function()
        {
          recaptcha_log = grecaptcha.render(document.getElementById('login_form_recaptcha'), { 'sitekey' : '<?php echo RECHACHA_PUBLIC_KEY; ?>'});
          recaptcha_reg = grecaptcha.render(document.getElementById('register_form_recaptcha'), { 'sitekey' : '<?php echo RECHACHA_PUBLIC_KEY; ?>'});
          recaptcha_res = grecaptcha.render(document.getElementById('restore_form_recaptcha'), { 'sitekey' : '<?php echo RECHACHA_PUBLIC_KEY; ?>'});

        };
      </script>
      <?php
    }
    ?>
    <script src="main.js?v=9"></script>
  </body>
</html>