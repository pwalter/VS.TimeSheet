$(document).ready(function(){
    $('form').submit(function(){
        if($(this).valid()) {
            $('input', this)
                .attr('readonly', 'readonly')
                .addClass('disabled');

            $('textarea', this)
                .attr('readonly', 'readonly')
                .addClass('disabled');

            $('input[type=submit]', this)
                .attr('disabled', 'disabled')
                .attr('value', 'Einen Moment bitte...')
                //.removeClass('primary')
                .addClass('disabled');

            //$(".overlayOuter").fadeIn(300);
        }
    });
});