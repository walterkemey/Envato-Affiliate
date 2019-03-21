/* Admin sidebar starts */

$(document).ready(function(){

  $(window).resize(function()
  {
    if($(window).width() >= 991){
      $(".sidey").slideDown(350);
    }                
  });

});
 
$(document).ready(function(){

  $(".has_submenu > a").click(function(e){
    e.preventDefault();
    var menu_li = $(this).parent("li");
    var menu_ul = $(this).next("ul");

    if(menu_li.hasClass("open")){
      menu_ul.slideUp(350);
      menu_li.removeClass("open")
    }
    else{
      $(".nav > li > ul").slideUp(350);
      $(".nav > li").removeClass("open");
      menu_ul.slideDown(350);
      menu_li.addClass("open");
    }
  });
  
});

$(document).ready(function(){
  $(".sidebar-dropdown a").on('click',function(e){
      e.preventDefault();

      if(!$(this).hasClass("dropy")) {
        // hide any open menus and remove all other classes
        $(".sidey").slideUp(350);
        $(".sidebar-dropdown a").removeClass("dropy");
        
        // open our new menu and add the dropy class
        $(".sidey").slideDown(350);
        $(this).addClass("dropy");
      }
      
      else if($(this).hasClass("dropy")) {
        $(this).removeClass("dropy");
        $(".sidey").slideUp(350);
      }
  });

});
/* Admin sidebar navigation ends */