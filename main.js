var recaptcha_reg;
var recaptcha_log;

function site_fileload(e) { e.nextElementSibling.click(); }

function showProgressAjax()
{
  ajax_loading.style.display = 'block';
}

function hideProgressAjax()
{
  ajax_loading.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    
  document.querySelector("body").addEventListener("keydown", (e) => { if (e.target.closest(".form-validate")) { e.target.classList.remove('is-invalid'); } });
  document.querySelector("body").addEventListener("input", (e) => { if (e.target.closest(".form-validate")) { e.target.classList.remove('is-invalid'); } });    
    
  comment_input_message.oninput = () => {     
    m();           
  };
       
  document.querySelector("body").addEventListener("click", e => { 
    if (e.target.closest(".comment__action")) {

      var parent = e.target.closest(".comment");  
      var child = parent.querySelector('.comment__expand-branch');
      var parent_id = parent.dataset.id; 
      var level = parent.dataset.level; 
      var next = parent.dataset.next; 
        
      comment_form_data_reply.value = parent_id; 
      comment_form_data_level.value = level;
      comment_form_data_next.value = next;

      child.after(comment_form);
      
      comment_placeholder.textContent = "Написать ответ...";
      comment_input_message.innerHTML = ""; 

      if (user_id !== 0) {
        comment_input_message.focus();
      }
      comment_input_box.classList.add('thesis--empty');        
      comment_send_button.classList.add('v-button--disabled');
      comment_send_button_text.textContent = "Отправить"; 
      comment_send_button.setAttribute("onclick", "b();");
          
      comment_cancel.innerHTML = "<div onclick=\"q("+parent_id+");\">Цитировать</div><div data-name=\"cancel\" onclick=\"a(true, false);\">Отмена</div>";             
      
      e.stopPropagation(); e.preventDefault();        
      return false; 
    } 
  });
     
  document.querySelector("body").addEventListener("click", (e) => 
  { 
    if (e.target.closest(".comment-edit")) 
    { 
      var parent = e.target.closest(".comment");  
              
      var child = parent.querySelector('.comment__expand-branch');
      
      var parent_id = parent.dataset.id; 
      
      var level = parent.dataset.level;  
      
      var image = parent.dataset.image;  
      
      comment_form_data_reply.value = parent_id; 
      
      comment_form_data_level.value = level;
      
      comment_form_data_media.value = image;
      
      if (image)
      {
        var html = '<div class="andropov_uploader__preview_item__inner"><div class="andropov_preview--image" style="min-height: 80px; min-width: 80px"><img style="max-width: 80px; max-height: 80px;" src="/upload/'+image+'"></div><div class="andropov_uploader__preview_item__remove" onclick="f();"></div></div>';
      
        comment_uploader.innerHTML = html;          
      } 
      else
      {
        comment_uploader.innerHTML = "";
        
        parent.setAttribute("data-image", "");
      }    
      
      child.after(comment_form);
              
      var text = document.querySelector("#comment_text_"+parent_id).innerHTML;   
      
      comment_placeholder.textContent = "Редактировать комментарий...";
      comment_input_message.innerHTML = text; 

      if (user_id !== 0) {
        comment_input_message.focus();
      }
      comment_input_box.classList.remove('thesis--empty');        
      comment_send_button.classList.add('v-button--disabled');
      comment_send_button_text.textContent = "Редактировать";
      comment_send_button.setAttribute("onclick", "e();");
            
      comment_cancel.innerHTML = "<div onclick=\"a(true, false);\">Отмена</div>";             
      
      e.stopPropagation(); e.preventDefault();        
      return false; 
    } 
  
  });
     
  document.querySelector('body').addEventListener("change", (e) => {
    if (e.target.closest(".site_load_file")) { 

      let fd = new FormData();
      fd.append("loadfile", e.target.closest(".site_load_file").files[0]);         
          
      var req = new XMLHttpRequest();  
      req.responseType = "json";
      req.open("POST", 'upload.php', true);
      req.setRequestHeader("X-Requested-With", "XMLHttpRequest"); 
      req.ontimeout = () => { console.log('timeout'); };
      req.onerror = () => { console.log('error'); };
      req.onloadstart = () => { comment_input_box.classList.add("thesis--wait"); };
      req.onloadend = () => { comment_input_box.classList.remove("thesis--wait");  }; 
      req.onload = () => { let result = req.response; if (result["data"]) { try { eval(result["data"]); } catch (err) { console.log(err); } } };
      req.send(fd);
    }
    
    return false; 
  });

  //MODAL
  const body = document.getElementById('body');
  const recapLogin = document.getElementById('login_form_recaptcha');

  function openModalLogin(button, addClass) { //modal__login--open

    document.querySelectorAll(button)
    .forEach(btn => {
      btn.addEventListener('click',() => {

        if (typeof window.recapLoaded == 'undefined') { //if url__recaptcha--none

          recapLoading();

        } else {

          if (!recapLogin.hasChildNodes()) {

            recaptcha_log = grecaptcha.ready(function() {
              grecaptcha.render( recapLogin, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
            });
          }
        }
        
        document.getElementById('recapLoaded') // url__recaptcha--loaded
        .addEventListener('load',() => {

          body.className = '';
          body.classList.add(addClass);

          if (body.classList.contains('modal-open') && !recapLogin.hasChildNodes()) {

            recaptcha_log = grecaptcha.ready(function() {
              grecaptcha.render( recapLogin, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
            });
          }
        });

        body.className = '';
        body.classList.add(addClass);
        
        return false;
      });
    });
  }

  function recapLoading() {

    const script = document.createElement('script');
    script.setAttribute('id', 'recapLoaded');
    script.src  = "https://www.google.com/recaptcha/api.js";
    body.appendChild(script);
    var recapLoaded = true;
  }

  function timerRestore() {

    const countDownDate = new Date().getTime() + 10*60000;
        
    const timer = setInterval(() => {
      
      const now = new Date().getTime();
      const distance = countDownDate - now;
      const min = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const sec = Math.floor((distance % (1000 * 60)) / 1000);
      document.getElementById("restore_form_message").innerHTML =
        `<div class="alert">Повторный запрос пароля возможен через ${min} минут ${sec} секунд</div>`;  

      if (distance < 0) {
        
        clearInterval(timer);
        document.getElementById("restore_form_message").innerHTML = "";
      }
    }, 1000);
  }

  function ajaxOnSubmitForm (form, url) {

    document.getElementById(form)
    .addEventListener('submit', e => {
      
      e.preventDefault();
      ajax(url, `#` + `${form}`);

      if (form === 'form_restore') {

        setTimeout(() => {

          if (document.getElementById('userNotFound'))

          timerRestore();
        }, 2000);
      }
    });
  }
  
  document.querySelectorAll('.comment__icon') // modal__complaint--open
  .forEach(btn => {
    btn.addEventListener('click',() => {

      body.className = '';
      body.classList.add('modal-openComp');
    });
  });
  
  document.querySelectorAll('.btn-close') //modal--close
  .forEach(btn => {
    btn.addEventListener('click',

    () => body.className = '');  
  });

  document.querySelectorAll('.comments_pseudo_form') // form__pseudo --show/hide
  .forEach(btn => {
    btn.addEventListener('click', () => {
      
      document.querySelectorAll('.comments_pseudo_form')
      .forEach(btn => {

        btn.classList.toggle('--show');
      });
    });
  });

  document.getElementById('comment_send_button_text')// form__pseudo_up --show
  .addEventListener('click', () => {

    const fromPseudoUp = document.getElementById('comment_pseudo_form_up');

    if (fromPseudoUp) {

      fromPseudoUp.classList.add('--show');
      document.getElementById('comment_pseudo_form')
      .classList.remove('--show');
    } 
  });

  function getRes(get, post) {

    const xhr= new XMLHttpRequest();
    xhr.open('GET', get, true);
    xhr.onreadystatechange= function() {

      if (this.readyState!==4) return;
      if (this.status!==200) {
        document.getElementById(post).innerHTML = 
          `<div class="alert">Не удалось получить ${get}<br> Ошибка ${this.status}</div>`;
        return;
      }

      document.getElementById(post).innerHTML= this.responseText;
    };
    xhr.send();
  }

  if (user_id == 0) { //IF USER NOT LOGGED

    ajaxOnSubmitForm('form_login', 'log.php');
    ajaxOnSubmitForm('form_register', 'reg.php');
    ajaxOnSubmitForm('form_restore', 'restore.php');

    document.querySelectorAll('.btns_reg') // modal__registration--open
    .forEach(btn => {
      btn.addEventListener('click',() => {

        const recapReg = document.getElementById('register_form_recaptcha');
        
        if (typeof window.recapLoaded == 'undefined') { //if url__recaptcha--none

          recapLoading();
          
        } else {

          if (!recapReg.hasChildNodes()) {
            
            recaptcha_reg = grecaptcha.ready(function() {
              grecaptcha.render( recapReg, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
            });
          }
        }

        document.getElementById('recapLoaded') // url__recaptcha--loaded
        .addEventListener('load',() => {
          
          body.className = '';
          body.classList.add('modal-openReg');

          if (!recapReg.hasChildNodes()) {
            
            recaptcha_reg = grecaptcha.ready(function() {
              grecaptcha.render( recapReg, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
            });
          }
        });

        body.className = '';
        body.classList.add('modal-openReg');
        
        if (body.classList.contains('modal-openReg')) {

          [
            { get: './forms/form_register_country.html', post: 'form__country--onload' },
            { get: './forms/form_register_work.html', post: 'form__work--onload' },
            { get: './forms/form_register_post.html', post: 'form__post--onload' }
          ]
          .forEach(({get, post}) => getRes(get, post));
        }
      });
    });

    openModalLogin('.btns_login', 'modal-open'); // modal__login--open
    openModalLogin('.vote__button', 'modal-open');// modal__login--open
    openModalLogin('.comments_form', 'modal-open');// modal__login--open

    document.getElementById('btn_rest')// modal__restore--open
    .addEventListener('click',() => {

      body.className = '';
      body.classList.add('modal-openRest');
      const recapRes = document.getElementById('restore_form_recaptcha');

      if (body.classList.contains('modal-openRest') && !recapRes.hasChildNodes())

      recaptcha_res = grecaptcha.ready(function() {
        grecaptcha.render( recapRes, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
      });
    });
    
    document.querySelectorAll('.btn-primary')// modal__complaint--close/open login
    .forEach(btn => {
      btn.addEventListener('click',() => {

        if (body.classList.contains('modal-openComp')) {

          body.className = '';
          body.classList.add('modal-open');

          if (typeof window.recapLoaded == 'undefined') { //if url__recaptcha--none

            recapLoading();
  
          } else {
  
            if (!recapLogin.hasChildNodes()) {
  
              recaptcha_log = grecaptcha.ready(function() {
                grecaptcha.render( recapLogin, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
              });
            }
          }

          document.getElementById('recapLoaded') // url__recaptcha--loaded
          .addEventListener('load',() => {

            if (body.classList.contains('modal-open') && !recapLogin.hasChildNodes()) {

              recaptcha_log = grecaptcha.ready(function() {
                grecaptcha.render( recapLogin, { 'sitekey' : '6LcoBQQfAAAAAKnRfhH1orNPy1UVWUNTyWF7wa5j'});
              });
            }
          });
        } 
      });
    });

  } else { //IF USER LOGGED

    document.getElementById('btn__login').classList.add('hidden'); // btn__log--hide
    document.getElementById('el4-panel').classList.add('hidden'); // btn__reg--hide
    document.getElementById('exit').classList.add('show');  // btn__exit--show

    document.getElementById('exit')  // user -- log out
    .addEventListener('click', () => {

      document.cookie = 'PHPSESSID' + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=[/];";
      window.location.href ='/';
    });

    document.getElementById('form_complaint')
    .addEventListener('submit', e => e.preventDefault());

    body.addEventListener('click', e => {
      document.querySelectorAll('.ddMenu') // close dropdown menu
      .forEach(menu => {
        if (!menu.hasAttribute('open')) return;
        menu.removeAttribute('open');
      });

      if (!document.getElementById('rating__list')) return; // comment rating__list -- close
      if (e.target.closest('.vote__value')) return;
      document.getElementById('rating__list').remove();
    });


    document.querySelectorAll('.vote__value') // comment rating__list
    .forEach(list => {
      list.addEventListener('click', () => {

        if (list.textContent === '0') return;
        if (!document.getElementById('rating__list')) {

          const postId = list.getAttribute('id').slice(15);
          const ratingList = document.createElement('div');
          ratingList.setAttribute('id', 'rating__list');
          list.parentNode.appendChild(ratingList);
          getRes(`rating-list.php?id=${postId}`, 'rating__list');

        } else { document.getElementById('rating__list').remove(); }
      });
    });
  }
});
   
   function q(id)
   {
     var text = document.querySelector("#comment_text_"+id).innerHTML;    
          
     text = text.replace(/<span.*?>.*?<\/span>/ig,'');
     
     text = text.replace(/<br>/g,'[br]');
              
     text = stripHtmlToText(text);  
       
     comment_input_message.innerHTML = '[quote]'+text+'[/quote]';
     
     m();
   }
   
   function stripHtmlToText(html)
   {
     var tmp = document.createElement("DIV");
     tmp.innerHTML = html;
     var res = tmp.textContent || tmp.innerText || '';
     res.replace('\u200B', ''); 
     res = res.trim();
     return res;
   }
   
   function a(flag = true, focus = true) 
   { 
     if (flag == true)  
     {  
       comment_pseudo_form.after(comment_form); 
       comment_placeholder.textContent = "Написать комментарий...";
       comment_input_message.innerHTML = ""; 

       if (focus && user_id !== 0) { comment_input_message.focus(); }
       comment_input_box.classList.add('thesis--empty');
       comment_cancel.innerHTML = "";
       comment_send_button.classList.add('v-button--disabled');
       comment_form_data_reply.value = 0;
       comment_form_data_level.value = 0;
       comment_form_data_subscription.value = 0;
       comment_form_data_up.value = 0;
       comment_send_button_text.textContent = "Отправить";
       comment_send_button.setAttribute("onclick", "b();");
       comment_uploader.innerHTML = "";
       comment_form_data_media.value = "";
     }
     else
     {
       comment_pseudo_form_up.after(comment_form); 
       comment_placeholder.textContent = "Написать комментарий...";
       comment_input_message.innerHTML = ""; 
       
       if (focus && user_id !==0) { comment_input_message.focus(); }
       comment_input_box.classList.add('thesis--empty');
       comment_cancel.innerHTML = "";
       comment_send_button.classList.add('v-button--disabled');
       comment_form_data_reply.value = 0;
       comment_form_data_level.value = 0;
       comment_form_data_subscription.value = 0;
       comment_form_data_up.value = 1;
       comment_send_button_text.textContent = "Отправить";
       comment_send_button.setAttribute("onclick", "b();");
       comment_uploader.innerHTML = "";
       comment_form_data_media.value = "";
     }         
   }
   
   function b()
   {  
     comment_form_data_message.value = comment_input_message.innerHTML;  
       
     ajax("comment.php", "#comment_form_data");
   }
   
   function e()
   {  
     comment_form_data_message.value = comment_input_message.innerHTML;  
       
     ajax("edit.php", "#comment_form_data");
   }
   
   function c() 
   { 
     comment_pseudo_form.after(comment_form); 
     comment_placeholder.textContent = "Написать комментарий...";
     comment_input_message.innerHTML = ""; 
     comment_input_box.classList.add('thesis--empty');
     comment_cancel.innerHTML = "";
     comment_send_button.classList.add('v-button--disabled');
     comment_form_data_up.value = 0;     
   }
   
   function f()
   {
     comment_uploader.innerHTML = "";
     comment_form_data_media.value = "";
           
     m();   
   }
   
   function m()
   {
     if (comment_input_message.textContent.trim() == '') 
     {
       comment_input_box.classList.add('thesis--empty');
          
       comment_send_button.classList.add('v-button--disabled');          
     }
     else
     {
       comment_input_box.classList.remove('thesis--empty');
          
       comment_send_button.classList.remove('v-button--disabled');                    
     }   
   }
   
   function view_image(elem)
   {      
     modal_preview_image.style.display = "block";
     preview_image.src = elem.src;
   }
   
   function close_view_image()
   {      
     modal_preview_image.style.display = "none";    
   }
   
    function ajax(url = null, form = false)
   {           
     let post = form ? new FormData(document.querySelector(form)) : '';  
      
     const request = new XMLHttpRequest();
     request.timeout = 20000;  
     request.responseType = "json";
     request.open("POST", url, true);
     request.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
     request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
     request.ontimeout = () => {  };
     request.onerror = () => {  };
     request.onloadstart = () => { showProgressAjax(); };
     request.onloadend = () => { hideProgressAjax(); };
      
      request.onload = () => 
     { 
       let result = request.response;  
      

       if  ((result?.data)) 
       { 
         try 
         { 
           // Prepare new data
           let replyId = url.split("?")[1]?.split("=")[1];
           const path = url.split("?")[0];
           switch(path) {
            // Comment added
            case "comment.php":
              replyId = post?.get("reply_id");
              document.getElementById(`comment_id_${replyId}`)?.querySelector(".ddMenu")?.classList.add('hidden');
            break;

            // Comment deleted
            case "delete.php":
              if(!replyId) {
                replyId = post?.get("id");
              }
              const parentId = document.getElementById(`comment_id_${replyId}`)?.getAttribute("data-parent");
              const childs = document.querySelectorAll(`[data-parent="${parentId}"]`);
              if(childs.length === 1) {
                document.getElementById(`comment_id_${parentId}`)?.querySelector(".ddMenu")?.classList.remove('hidden');
              }
            break;
           }
           window.eval(result?.data);        
         } 
         catch (err) 
         { 
           console.log(err); 
         }        
       }
     };
  
     request.send(post);
 
     return false;
   }