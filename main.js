var siteModal;
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

document.addEventListener('DOMContentLoaded', function ()
{       
    
     document.querySelector("body").addEventListener("keydown", (e) => { if (e.target.closest(".form-validate")) { e.target.classList.remove('is-invalid'); } });
     document.querySelector("body").addEventListener("input", (e) => { if (e.target.closest(".form-validate")) { e.target.classList.remove('is-invalid'); } });    
       
     comment_input_message.oninput = () =>
     {  
       if (user_id == 0)  
       {    
         if (typeof siteModal !== 'object')  
         {   
           siteModal = new bootstrap.Modal(document.getElementById('modal_login')); 
                          
           siteModal.show();                   
         }
         else if (!siteModal._isShown)
         {
           siteModal = new bootstrap.Modal(document.getElementById('modal_login')); 
                          
           siteModal.show();                     
         }          
       }    
         
       m();           
     }   
       
     document.querySelector("body").addEventListener("click", (e) => 
     { 
       if (e.target.closest(".comment__action")) 
       { 
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
         comment_input_message.focus();
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
         
         [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]')).map(function (elem) { new bootstrap.Dropdown(elem).hide(); });
         
         e.stopPropagation(); e.preventDefault();        
         return false; 
       } 
     
     });
     
     document.querySelector('body').addEventListener("change", (e) => 
  {
    if (e.target.closest(".site_load_file"))
    { 
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

  const body = document.getElementById('body');
  //modal--open
  function openModal(button, addClass) {
    document.querySelectorAll(button)
      .forEach(btn => {
        btn.addEventListener('click',() => {
          body.className = '';
          body.classList.add(addClass);
      });
    });
  }
  // modal--close
  document.querySelectorAll('.btn-close')
    .forEach(btn => {
      btn.addEventListener('click',
        () => body.className = '');
  });
  // modal__login--open
  openModal('.btns_login', 'modal-open');
  // modal__register--open
  openModal('.btns_reg', 'modal-openReg');
  // modal__complaint--open
  openModal('.comment__icon', 'modal-openComp');
  // modal__restore--open
  document.getElementById('btn_rest')
    .addEventListener('click',() => {
      body.className = '';
      body.classList.add('modal-openRest');
  });
     
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
      
       if (result["data"]) 
       { 
         try 
         { 
           eval(result["data"]);             
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