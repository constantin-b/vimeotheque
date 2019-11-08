(function($){
    
    var pro_options = function(){
        var trigger = $('.cvm-pro-options-trigger'),
            data = $(trigger).data(),
            items = $( data.selector );
            
        $(document).on( 'click', '.cvm-pro-options-trigger', function(e){
            e.preventDefault();
            if( data.visible ){
                $(items).hide(300);
                data.visible = 0;
                $(trigger).text( data.text_off );
            }else{
                $(items).show(300);
                data.visible = 1;
                 $(trigger).text( data.text_on );
            }
        })    
        
    }

    var disable_fields = function(){
        $('.cvm-pro-option input, .cvm-pro-option select').attr('disabled', 'disabled');
    }
    
    var start = function(){
        pro_options();
        disable_fields();
    }

    $(document).ready(start);
})(jQuery);