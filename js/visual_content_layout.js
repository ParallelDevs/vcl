(function ($, Drupal) {

    Drupal.behaviors.visual_content_layout = {
        attach: function (context, settings) {

            if(settings.enable_formats[$('.filter-list').val()]) {
                $('.visual-content-layout-button-wrap').show();
            }else{
                $('.visual-content-layout-button-wrap').hide();
            }

            var buttonText = $('.visual-content-layout-btn').data('state') + " Visual Content Layout";
            $('.visual-content-layout-btn').text(buttonText);

            $('.filter-list', context).change(function() {

                // Disable visual layout button if they enabled because the text format is changed
                var state_link = $('.visual-content-layout-btn').data('state'),
                    format_link = $('.visual-content-layout-btn').data('format');
                if(state_link == 'enable' && format_link != $(this).val()) {
                    //event for quit the visual help
                    //$(this).parents('.text-format-wrapper').find('.visual_content_layout_btn').click();
                }

                if(settings.enable_formats[$(this).val()]) {
                    // Setup the format for easy use then Layout will be rendered
                    $('.visual-content-layout-btn').data('format', $(this).val());
                    $('.visual-content-layout-button-wrap').show();
                    var data = $(this).parents('.text-format-wrapper').find('.visual_content_layout_btn').data('format');
                }
                else {
                    $('.visual-content-layout-button-wrap').hide();
                    $(this).parents('.text-format-wrapper').find('.form-textarea').show();
                }

            });


        } // End of attach behaviour
    }

}(jQuery, Drupal));
