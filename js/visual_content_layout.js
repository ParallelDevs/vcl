/**
 * @file
 * Provides javascript methods for manage the visual help.
 */

(function ($, Drupal) {

    //hide the visual help for document ready
    $('.visual-content-layout-visual-help').hide();
    $('.visual-content-layout-btn').text('Enable Visual Content Layout');

    /**
     * Manage the display of the visual help.
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
    Drupal.behaviors.visualContentLayoutForm = {
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

    /**
     * Manage the visual elements.
     *
     * Methods that are responsible for transform text in visual element and visual element in text
     *
     * @type {Drupal~behavior}
     */
    Drupal.behaviors.visualContentLayoutElements = {
        attach: function (context, settings) {

            //--------------------------------------------------------------------------------
            //                  event click enable/disable visual help button
            //--------------------------------------------------------------------------------
            $('.visual-content-layout-btn', context).click(function() {
                //get id of the respective textArea
                var textArea = $(this).data('id');
                //validate if the visual help is enable
                if($(this).data('state')=='enable'){
                    var swaps = settings.visualContentLayout.enable_swaps;
                    getVisualElements(this, textArea, swaps);
                }else{
                    $('.visual-content-layout-element').remove();
                }
            });

            //--------------------------------------------------------------------------------
            //                  transform text in visual elements
            //--------------------------------------------------------------------------------
            function getVisualElements(button, textArea, enableSwaps){

                var text = $('#'+textArea).val(),
                    chunks = text.split(/(\[{1,2}.*?\]{1,2})/),
                    elements = [],
                    count = 0,
                    textAreaParent = $('#' + textArea).parents()[2],
                    visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help'),
                    swap = null,
                    swapText = false,
                    father = [];

                for(var c in chunks){

                    //save the original text in case of error in the swap pattern
                    var originaltext = chunks[c];

                    c = chunks[c].trim();

                    if(c == ''){
                        continue;
                    }

                    //validate the chunk is a swap head
                    if(c[0] == '['){
                        //eliminate the first and last character
                        c = c.substring(1, c.length-1).trim();
                        //validate if the swap have the close character in the head
                        if(c[c.length-1] == '/'){
                            c = c.substring(0, c.length-1);
                            //validate if the new swap is a father
                            if(count > 0 && swap != null){
                                elements.push(swap);
                                father.push(elements.length - 1);
                            }
                            swap = c.trim().split(" ");
                            var div = createHTMLDiv(originaltext, swap);
                            //validate if that swap have a father
                            if(father.length > 0){
                                elements.push(div);
                                swap = null;
                                swapText = false;
                                continue;
                            }else{
                                $(div).appendTo($(visualHelpArea));
                                count = 0;
                                continue;
                            }
                        }
                        //validate the chunk is a swap close
                        if(c[0] == '/'){
                            c = c.substring(1, c.length);
                            //validate if the close character is for a father
                            if(swap == null){
                                var lastFather = father.pop(), fatherSwap = elements[lastFather];

                                //validate if exist a father
                                if(!fatherSwap){
                                    var div = createHTMLDiv(originaltext, null);
                                    $(div).appendTo($(visualHelpArea));
                                    count = 0;
                                    continue;
                                }

                                //validate the swap and close character are the same
                                if(fatherSwap[0] == c){
                                    //create the father and add the child
                                    var div = createHTMLDiv(originaltext, fatherSwap);
                                    while(elements[lastFather+1]){
                                        $(elements[lastFather+1]).appendTo($(div));
                                        elements.splice( lastFather+1, 1 );
                                    }
                                    //validate if the father have a father
                                    if(father.length == 0){
                                        $(div).appendTo($(visualHelpArea));
                                        elements.splice( 0, 1 );
                                    }else{
                                        elements[lastFather] = div;
                                    }
                                    count = lastFather;
                                    swapText = false;
                                    continue;
                                }else{
                                    var div = createHTMLDiv(originaltext, null);
                                    father.push(lastFather);
                                    elements.push(div);
                                    swap = null;
                                    swapText = false;
                                    continue;
                                }
                            }
                            //validate if the child swap and close character are the same
                            if(swap[0] == c){
                                var div = createHTMLDiv(originaltext, swap);
                                //validate if that swap have a father
                                if(father.length > 0){
                                    elements.push(div);
                                    swap = null;
                                    swapText = false;
                                    continue;
                                }else{
                                    $(div).appendTo($(visualHelpArea));
                                    count = 0;
                                    continue;
                                }
                            }
                        }

                        //validate the swap is a valid swap
                        if(!enableSwaps[c.split(" ")[0]]){
                            //create a simple text swap
                            var div = createHTMLDiv(originaltext, null);

                            //validate is the storage swap is a father of the created div
                            if(swap != null){
                                elements.push(swap);
                                father.push(elements.length - 1);
                            }

                            //validate if that swap have a father
                            if(father.length > 0){
                                elements.push(div);
                                swap = null;
                                swapText = false;
                                continue;
                            }else{
                                $(div).appendTo($(visualHelpArea));
                                count = 0;
                                continue;
                            }
                        }

                        //validate if the new swap is a father
                        if(count > 0 && swap != null){
                            elements.push(swap);
                            father.push(elements.length - 1);
                        }
                        //save the attributes of the swaps
                        swap = c.trim().split(" ");
                        swapText = true;
                        count++;
                        continue;
                    }

                    //validate if the chunk is only text and is the first
                    if(swapText){
                        swap.push('text="'+ originaltext + '"');
                    }else{
                        var div = createHTMLDiv(originaltext, null);
                        //validate if that swap have a father
                        if(father.length > 0){
                            elements.push(div);
                            swap = null;
                            swapText = false;
                            continue;
                        }else{
                            $(div).appendTo($(visualHelpArea));
                            count = 0;
                            continue;
                        }
                    }
                }
            }

            //--------------------------------------------------------------------------------
            //                       create html object for the swap
            //--------------------------------------------------------------------------------
            function createHTMLDiv(originaltext, swap){
                //create the element and set the class
                var element = document.createElement('div');
                $(element).addClass('visual-content-layout-element input-group');
                //validate if the swap is a valid swap
                if(swap != null){
                    $(element).html(swap[0]);
                    //set the name in data attributes
                    $(element).data('swapId', swap[0]);
                    delete(swap[0]);
                    //set all other attributes
                    for (idx in swap) {
                        var attr = swap[idx].trim().replace('"','').split('=');
                        $(element).data(attr[0], attr[1]);
                    }
                }else{
                    $(element).html("Text: " + originaltext);
                    $(element).data('swapId', "string");
                    $(element).data('text', originaltext);
                }

                return element;
            }
        }
    }

}(jQuery, Drupal));
