$(document).ready(function(){

        $('.container').each(function(){  
            
            var highestBox = 0;
            $('.info', this).each(function(){
            
                if($(this).height() > highestBox) 
                   highestBox = $(this).height(); 
            });  
            
            $('.info',this).height(highestBox);
			
    });    
});

$(window).resize(function(){
   $('.container').each(function(){  
            
            var highestBox = 0;
            $('.info', this).each(function(){
            
                if($(this).height() > highestBox) 
                   highestBox = $(this).height(); 
            });  
            
            $('.info',this).height(highestBox);
			
    });    
});