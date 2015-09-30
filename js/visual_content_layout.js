/**
 * @file
 * Provides javascript methods for manage the visual help.
 */

(function ($, Drupal) {

    //hide the visual help for document ready
    $('.visual-content-layout-visual-help').hide();
    $('.visual-content-layout-btn').text('Enable Visual Content Layout');

    /**
     * Manage the display for the visual help.
     *
     * Methods that are responsible for show and hide the visual help according on the textFormat
     *
     * @type {Drupal~behavior}
     */
    Drupal.behaviors.visualContentLayoutDisplay = {
        attach: function (context, settings) {

            //validate all the filter select for enable the button on it textArea
            var filter = $('.filter-list');
            for (select in filter) {
                try
                {
                    //get the parent of the actual select, if is undefined it send and error and stop de cicle
                    var parent = $(filter[select]).parents()[2];
                    //show the enable/disable button for the visual help according the textFormat
                    if(settings.visualContentLayout.enable_formats[$(filter[select]).val()]) {
                        $(parent).children('.visual-content-layout-button-wrap').show();
                    }else{
                        $(parent).children('.visual-content-layout-button-wrap').hide();
                    }
                }
                catch(err) { break; }
            }

            //--------------------------------------------------------------------------------
            //                          event change the text format
            //--------------------------------------------------------------------------------
            $('.filter-list', context).change(function() {
                //get the parent of the select
                var parent = $(this).parents()[2];
                //validate if the textFormat use the visual help
                if(settings.visualContentLayout.enable_formats[$(this).val()]) {
                    $(parent).children('.visual-content-layout-button-wrap').show();
                }
                else {
                    $(parent).children('.visual-content-layout-button-wrap').hide();
                    $(parent).children('.visual-content-layout-visual-help').hide();
                    $(parent).children('.visual-content-layout-button-wrap')
                        .find('.visual-content-layout-btn').data('state','disable');
                    $(parent).children('.visual-content-layout-button-wrap')
                        .find('.visual-content-layout-btn').text('Enable Visual Content Layout');
                    $(this).parents('.text-format-wrapper').find('.form-textarea').show();
                    $(parent).children('#edit-body-0-format-guidelines').show();
                }
            });

            //--------------------------------------------------------------------------------
            //                  event click enable/disable visual help button
            //--------------------------------------------------------------------------------
            $('.visual-content-layout-btn', context).click(function() {
                //get id of the respective textArea
                var textArea = $(this).data('id');
                //validate if the visual help is enable
                if($(this).data('state')=='disable'){
                    showVisualHelp(this, textArea);
                }else{
                    hideVisualHelp(this, textArea);
                }
            });


            //--------------------------------------------------------------------------------
            //                          show the visual help
            //--------------------------------------------------------------------------------
            function showVisualHelp(button, textArea){
                //show the visual help section of the respective textArea
                var parent = $(button).parents()[1];
                $(parent).children('.visual-content-layout-visual-help').show();
                //hide the respective textArea and change button text
                $('#' + textArea).hide();
                $(parent).find('.filter-guidelines').hide();
                $(button).data('state','enable');
                $(button).text('Disable Visual Content Layout');
            }

            //--------------------------------------------------------------------------------
            //                          hide the visual help
            //--------------------------------------------------------------------------------
            function hideVisualHelp(button, textArea){
                //hide the visual help section of the respective textArea
                var parent = $(button).parents()[1];
                $(parent).children('.visual-content-layout-visual-help').hide();
                //show the respective textArea and change button text
                $('#' + textArea).show();
                $(parent).find('.filter-guidelines').show();
                $(button).data('state','disable');
                $(button).text('Enable Visual Content Layout');
            }

            //--------------------------------------------------------------------------------
            //                  event click display swap select form
            //--------------------------------------------------------------------------------
            $('.visual-content-layout-form-button', context).click(function() {
                var textArea = $(this).data('textarea'),
                    element = document.createElement('input');
                $(element).attr("id","visual-content-layout-actual-textarea")
                    .attr("type","hidden")
                    .val(textArea)
                    .appendTo($(".visual-content-layout-visual-help"));
            });

        } // End of attach behaviour
    }

    /**
     * Manage the information send by swap attributes form.
     *
     * Methods that are responsible for take the information save the attributes and create the visual element
     *
     * @type {Drupal~behavior}
     */
    Drupal.behaviors.visualContentLayoutElements = {
        attach: function (context, settings) {

            var attributes = settings.visualContentLayout.attributes;

            if(attributes) {

                var textArea = $('#visual-content-layout-actual-textarea').val(),
                    textAreaElement = $('#' + textArea),
                    textAreaParent = textAreaElement.parents()[2],
                    visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help');

                //create the html element for the new swap
                var element = document.createElement('div');
                $(element).addClass('visual-content-layout-element')
                    .html(attributes.swapName)
                    .appendTo(visualHelpArea);
                //set the attributes
                for (attr in attributes) {
                    $(element).data(attr, attributes[attr]);
                }
                createTextSwap($(element));
            }

            //--------------------------------------------------------------------------------
            //          convert the visual help element into text for the textArea
            //--------------------------------------------------------------------------------
            function createTextSwap(element){
                var data = $(element).data(),
                    text = data.text,
                    swapName = data.swapName,
                    swapId = data.swapId,
                    attributesText = ' ',
                    swapText = '[' + swapId;
                //delete the name, id and text from the attributes array
                delete(data['swapName']);
                delete(data['swapId']);
                delete(data['text']);

                for (attr in data) {
                    try
                    {
                        attributesText += attr.trim() + '="'+ data[attr].trim() +'" ';
                    }
                    catch(err) { attributesText = attributesText.trim() }
                }
                swapText += attributesText + ']' + text + '[/' + swapId + ']';

                var textArea = $('#visual-content-layout-actual-textarea').val();
                $('#' + textArea).val($('#' + textArea).val() + swapText);
                $('#visual-content-layout-actual-textarea').remove();
            }
        }
    }

}(jQuery, Drupal));
