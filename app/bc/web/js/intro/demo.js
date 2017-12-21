+function ($) {/*
  if(!getcookie('flag')){*/
    $(function(){

      var intro = introJs();

      intro.setOptions({
        steps: [
          {
            element: '.dropdown',
            intro: '<p class="h4 text-uc"><strong>1: User</strong></p><p>You can quick now information about yourself</p>',
            position: 'bottom'
          },
          {
            element: '.m-t',
            intro: '<p class="h4 text-uc"><strong>2: Style</strong></p><p>You can quickly change the theme</p>',
            position: 'bottom'
          },
          {
            element: '.nav-user',
            intro: '<p class="h4 text-uc"><strong>3: Quick Bar</strong></p><p>This is the notification, search and user information quick tool bar</p>',
            position: 'bottom'
          },
          {
            element: '#nav header',
            intro: '<p class="h4 text-uc"><strong>4: Project switch</strong></p><p>You can quick switch your projects here.</p>',
            position: 'right'
          },
          {
            element: '.nav-primary',
            intro: '<p class="h4 text-uc"><strong>5: Main menu</strong></p><p>Start chat with your friend.</p>',
            position: 'right'
          },
          /*{
            element: '#aside',
            intro: '<p class="h4 text-uc"><strong>3: Aside</strong></p><p>Aside guide here</p>',
            position: 'left'
          },*/
          {
            element: '#nav footer',
            intro: '<p class="h4 text-uc"><strong>n: Chat & Friends</strong></p><p>Start chat with your friend.</p>',
            position: 'top'
          },

        ],
        showBullets: true,
      });

      intro.start();
      /*setcookie('flag',true,35000);*/
    });
  /*}

  function setcookie(a,b) {
    if(a && b)
      document.cookie = a+'='+b;
    else return false;
  }
  function getcookie(a) {
    var b = new RegExp(a+'=([^;]){1,}');
    var c = b.exec(document.cookie);
    if(c) c = c[0].split('=');
    else return false;
    return c[1] ? c[1] : false;
  }*/



}(jQuery);