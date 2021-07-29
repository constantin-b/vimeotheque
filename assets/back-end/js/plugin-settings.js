(function($){
    
    var pro_options = function(){
        var trigger = $('.cvm-pro-options-trigger');

        if( trigger.length == 0 ){
            return;
        }

        var data = $(trigger).data(),
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

    var togglerCheckboxes = function(){
        var trigger = $('.vmtq-toggler-checkbox');

        if( trigger.length == 0 ){
            return;
        }

        var data = $(trigger).data(),
            items = $( data.selector );

        $(document).on( 'change', '.vmtq-toggler-checkbox', function( e ){
            var checked = $(this).is(':checked');

            if( checked ){
                $(items).show(300);
            }else{
                $(items).hide(300);
            }
        })

    }

    var disable_fields = function(){
        $('.cvm-pro-option input, .cvm-pro-option select').attr('disabled', 'disabled');
    }

    var start = function(){
        pro_options();
        disable_fields();
        togglerCheckboxes();
    }

    $(document).ready(start);
})(jQuery);