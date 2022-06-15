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
          
      comment_cancel.innerHTML = "<div class=\"thesis__custom_button\" onclick=\"q("+parent_id+");\">Цитировать</div><div class=\"thesis__custom_button\" data-name=\"cancel\" onclick=\"a(true, false);\">Отмена</div>";             
      
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
        var html = '<div class="andropov_uploader__preview_item"><div class="andropov_uploader__preview_item__inner"><div class="andropov_preview andropov_preview--image" style="min-height: 80px; min-width: 80px"><img class="andropov_preview__image" style="max-width: 80px; max-height: 80px;" src="/upload/'+image+'"></div><div class="andropov_uploader__preview_item__remove" onclick="f();"></div></div></div>';
      
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
      comment_input_message.focus();
      comment_input_box.classList.remove('thesis--empty');        
      comment_send_button.classList.add('v-button--disabled');
      comment_send_button_text.textContent = "Редактировать";
      comment_send_button.setAttribute("onclick", "e();");
            
      comment_cancel.innerHTML = "<div class=\"thesis__custom_button\" onclick=\"a(true, false);\">Отмена</div>";             
      
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

  function openModal(button, addClass) { //modal--open

    document.querySelectorAll(button)
    .forEach(btn => {
      btn.addEventListener('click',() => {

        body.className = '';
        body.classList.add(addClass);
      });
    });
  }
 
  function closeModal(button) { //modal--close

    document.querySelectorAll(button)
    .forEach(btn => {
      btn.addEventListener('click',

      () => body.className = '');  
    });
  }
  
  openModal('.comment__icon', 'modal-openComp');// modal__complaint--open
  closeModal('.btn-close');

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

    const fromPseudoUp = document.querySelector('.comments_pseudo_form');

    if (!fromPseudoUp.classList.contains('--show')) {

      fromPseudoUp.classList.add('--show');
    }
  });

  if (user_id == 0) { //IF USER NOT LOGGED

    const formLogin = document.getElementById('form_login');
    const formLogPass = document.getElementById('form_login_password');
    let passEnter = 0;

    formLogin.addEventListener('submit', e => e.preventDefault());

    formLogin.addEventListener('change', () => {

      const storageGet = localStorage.getItem('pass');
      const btnLogin = document.getElementById('form_login--submit');
      const formLogError = document.getElementById('form_login_name_message');
    
      if (passEnter >= 3 && storageGet === null || passEnter == 0 && storageGet !== null) {

        btnLogin.disabled = true;
        formLogPass.setAttribute('readonly', '');
        formLogPass.classList.add('is-invalid');
        localStorage.setItem('pass', 'blocked');
        formLogError.innerHTML = 
        '<div>Число попыток ввода пароля превышено, доступ ограничен на 10 мин</div>';
      }

      if (localStorage.getItem('pass') !== null) {

        setTimeout(() => {
          formLogPass.removeAttribute('readonly');
          localStorage.removeItem('pass');
          formLogPass.classList.remove('is-invalid');
          formLogError.innerHTML = '';
          passEnter = 0;
          btnLogin.disabled = false;
        }, 10000); // 10 min 
      }
    });

    formLogPass.addEventListener('change', () => { // pass blocked

      if (passEnter <= 3 && localStorage.getItem('pass') === null) {
        
        passEnter++;
      }
    });

    document.getElementById('form_register')
    .addEventListener('submit', e => e.preventDefault());

    document.querySelectorAll('.btns_reg') // modal__registration--open
    .forEach(btn => {
      btn.addEventListener('click',() => {

        body.className = '';
        body.classList.add('modal-openReg');
        
        if (body.classList.contains('modal-openReg')) {

          getFormRegister (
            'form_register_country', 'form__country--onload' );
          getFormRegister (
            'form_register_work', 'form__work--onload' );
          getFormRegister (
            'form_register_post', 'form__post--onload' );
        }

        function getFormRegister(getFrom, sendTo) {

          const xhr= new XMLHttpRequest();
          xhr.open('GET', `./forms/${getFrom}.html`, true);
          xhr.onreadystatechange= function() {

            if (this.readyState!==4) return;
            if (this.status!==200) return;

            document.getElementById(sendTo).innerHTML= this.responseText;
          };
          xhr.send();
        }
      });
    });

    document.getElementById('form_restore')
    .addEventListener('submit', e => e.preventDefault());

    openModal('.btns_login', 'modal-open'); // modal__login--open
    openModal('.vote__button', 'modal-open');// modal__login--open
    openModal('.comments_form', 'modal-open');// modal__login--open

    document.getElementById('btn_rest')// modal__restore--open
    .addEventListener('click',() => {

      body.className = '';
      body.classList.add('modal-openRest');
    });
    
    document.querySelectorAll('.btn-primary')// modal__complaint--close/open login
    .forEach(btn => {
      btn.addEventListener('click',() => {

        if (body.classList.contains('modal-openComp')) {

          body.className = '';
          body.classList.add('modal-open');
        } 
      });
    });

  } else { //IF USER LOGGED

    document.getElementById('form_complaint')
    .addEventListener('submit', e => e.preventDefault());

    closeModal('.btn-primary');

    body.addEventListener('click', () => { // close dropdown menu

      document.querySelectorAll('.ddMenu')
      .forEach(btn => {
        
        if (btn.hasAttribute('open')) {

          btn.removeAttribute('open');
        }
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
       if (focus) { comment_input_message.focus(); }
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
       if (focus) { comment_input_message.focus(); }
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
   
   function d()
   {
     document.querySelector(".comments__content_wrapper").classList.add("comments__content_wrapper--open");
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