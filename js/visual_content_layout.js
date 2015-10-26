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
     * Manage the information send by each swap attributes form.
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
                    visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help'),
                    elementTitle = attributes.swapName + ": " ;

                elementTitle +=  (attributes.text && attributes.text!= "")?attributes.text:"";

                //create the html element for the new swap
                var element = document.createElement('div');

                $(element).addClass('visual-content-layout-element panel panel-default')
                          .html(elementTitle + attributes.link);

                //create the delete button for this element
                var deleteButton = $('<i/>').attr({class:'fa fa-trash-o fa-3 iconButton',})
                    .on('click', deleteVisualElement);

                //create the edit button for this element
                var editButton = $('<i/>').attr({class:'fa fa-pencil-square-o fa-3 iconButton',})
                    .on('click', editVisualElement)
                    .data('swapName', attributes.swapName);

                deleteButton.appendTo(element);
                editButton.appendTo(element);

                //validate the swap can contain others swaps
                if(attributes.container){
                    $('<div>').addClass('container').appendTo($(element));
                }
                delete(attributes.container);

                var attrKeys = Object.keys(attributes);

                for(var i = 0; i < attrKeys.length; i++){
                    if(attrKeys[i] != '' && attrKeys[i] != 'swapName'){
                        $(element).data(attrKeys[i],attributes[attrKeys[i]]);
                    }
                }

                $(element).appendTo(visualHelpArea)

                createTextSwap($(element));
                makeDragAndDrop();


            }

            //--------------------------------------------------------------------------------
            //          convert the visual help element into text for the textArea
            //--------------------------------------------------------------------------------
            function createTextSwap(element){
                var data = $(element).data(),
                    text = (data.text) ? ""+data.text : "" ,
                    attributesText = ' ',
                    swapText = '[' + data.swapId;

                for (attr in data) {
                    try
                    {
                        //validate the attribute is not the name, text o id
                        if(attr != 'swapId' && attr != 'text'){
                            attributesText += attr.trim() + '="'+ data[attr].trim() +'" ';
                        }

                    }
                    catch(err) { attributesText = attributesText.trim() }
                }
                swapText += attributesText + ']' + text + '[/' + data.swapId + ']';

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
                    var swaps = settings.visualContentLayout.enable_swaps,
                        swapnames = settings.visualContentLayout.swap_names;
                    getVisualElements(textArea, swaps, swapnames);
                    makeDragAndDrop();
                }else{
                    $('.visual-content-layout-element').remove();
                }
            });


            //--------------------------------------------------------------------------------
            //                  transform text in visual elements
            //--------------------------------------------------------------------------------
            function getVisualElements(textArea, enableSwaps, swapnames){

                var text = $('#'+textArea).val(),
                    textAreaParent = $('#' + textArea).parents()[2],
                    visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help'),
                    chunks = text.split(/(\[{1,2}.*?\]{1,2})/),
                    elements = [],
                    father = [],
                    count = 0,
                    swap = null,
                    swapText = false;

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

                            //validate the swap is a valid swap
                            if(typeof enableSwaps[c.split(" ")[0]] === "undefined"){
                                //create a simple text swap
                                var div = createHTMLDiv(originaltext, null, swapnames);
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

                            c = c.substring(0, c.length-1);
                            //validate if the new swap is a father
                            if(count > 0 && swap != null){
                                elements.push(swap);
                                father.push(elements.length - 1);
                            }
                            //get cssStyle
                            var startIndex = c.indexOf("cssStyles"),
                                lastIndex = c.indexOf('"', startIndex),
                                lastIndex = c.indexOf('"', lastIndex+1),
                                cssStyle = c.substring(startIndex, lastIndex);

                            //save the attributes of the swaps
                            c = c.replace(" "+cssStyle, "");
                            swap = c.trim().split(" ");
                            swap.push(cssStyle);
                            var div = createHTMLDiv(originaltext, swap, swapnames);
                            //validate if the swap can contain others swaps
                            if(enableSwaps[c.split(" ")[0]]){
                                $('<div>').addClass('container').appendTo($(div));
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
                        //validate the chunk is a swap close
                        if(c[0] == '/'){
                            c = c.substring(1, c.length);
                            //validate if the close character is for a father
                            if(swap == null){
                                var lastFather = father.pop(), fatherSwap = elements[lastFather];

                                //validate if exist a father
                                if(!fatherSwap){
                                    var div = createHTMLDiv(originaltext, null, swapnames);
                                    $(div).appendTo($(visualHelpArea));
                                    count = 0;
                                    continue;
                                }

                                //validate the swap and close character are the same
                                if(fatherSwap[0] == c){
                                    //create the father and add the child
                                    var div = createHTMLDiv(originaltext, fatherSwap, swapnames);
                                    var ele = $('<div>').addClass('container').appendTo($(div));
                                    while(elements[lastFather+1]){
                                        $(elements[lastFather+1]).appendTo(ele);
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
                                var div = createHTMLDiv(originaltext, swap, swapnames);
                                //validate if the swap can contain others swaps
                                if(enableSwaps[c.split(" ")[0]]){
                                    $('<div>').addClass('container').appendTo($(div));
                                }
                                swap = null;
                                swapText = false;
                                //validate if that swap have a father
                                if(father.length > 0){
                                    elements.push(div);
                                    continue;
                                }else{
                                    $(div).appendTo($(visualHelpArea));
                                    count = 0;
                                    continue;
                                }
                            }
                        }

                        //validate the swap is a valid swap
                        if(typeof enableSwaps[c.split(" ")[0]] === "undefined"){
                            //create a simple text swap
                            var div = createHTMLDiv(originaltext, null, swapnames);

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

                        //get cssStyle
                        var startIndex = c.indexOf("cssStyles"),
                            lastIndex = c.indexOf('"', startIndex),
                            lastIndex = c.indexOf('"', lastIndex+1),
                            cssStyle = c.substring(startIndex, lastIndex);

                        //save the attributes of the swaps
                        c = c.replace(" "+cssStyle, "");
                        swap = c.trim().split(" ");
                        swap.push(cssStyle);
                        swapText = true;
                        count++;
                        continue;
                    }

                    //validate if the chunk is only text and is the first
                    if(swapText){
                        swap.push('text="'+ originaltext + '"');
                    }else{
                        var div = createHTMLDiv(originaltext, null, swapnames);
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

                //validate if are fathers in the array
                if(father.length != 0){
                    var remainFather = fatherSwap,
                        remainFatherPosition = lastFather,
                        lastFather = father.pop(),
                        fatherSwap = elements[lastFather],
                        fatherOriginalText = "[ " + fatherSwap.toString().replace(/,/gi,' ') + " ]",
                        errorFather = createHTMLDiv(fatherOriginalText, null, swapnames);

                    if(remainFather == lastFather){

                    }

                    elements.push(div);
                    $(errorFather).appendTo($(visualHelpArea));
                    while(elements[lastFather+1]){
                        $(elements[lastFather+1]).appendTo($(visualHelpArea));
                        elements.splice( lastFather+1, 1 );
                    }
                }
            }

            //--------------------------------------------------------------------------------
            //                       create html object for the swap
            //--------------------------------------------------------------------------------
            function createHTMLDiv(originaltext, swap, swapnames){
                //create the element and set the class
                var element = $('<div>').addClass('visual-content-layout-element panel panel-default');

                //create the delete button for this element
                var deleteButton = $('<i/>').attr({class:'fa fa-trash-o fa-3 iconButton',})
                    .on('click', deleteVisualElement);


                //validate if the swap is a valid swap
                if(swap != null){

                    var swapName = swapnames[swap[0]],
                        text = "";

                    //create the edit button for this element
                    var editButton = $('<i/>').attr({class:'fa fa-pencil-square-o fa-3 iconButton',})
                        .on('click', editVisualElement)
                        .data('swapName', swapName);

                    //set the name in data attributes
                    element.data('swapId', swap[0]);
                    delete(swap[0]);
                    //set all other attributes
                    for (idx in swap) {
                        var attr = swap[idx].trim().replace(/\"/gi,'').split('=');
                        element.data(attr[0], attr[1]);
                        if(attr[0] == "text"){
                            text = attr[1];
                        }
                    }
                    element.html( swapName );
                }else{
                    element.html("Text: " + originaltext);
                    element.data('swapId', "string");
                    element.data('text', originaltext);
                }

                deleteButton.appendTo(element);
                editButton? editButton.appendTo(element) : "";

                return element;
            }

        }
    }

    //--------------------------------------------------------------------------------
    //                  transform visual elements in text
    //--------------------------------------------------------------------------------
    function getTextFromVisual(visualHelpArea){

        var children =  $(visualHelpArea).children('.visual-content-layout-element'),
            text = '';

        //process all children
        for (var i = 0; i<children.length; i++) {
            text += createText($(children[i]));
        }

        return text;
    }

    //--------------------------------------------------------------------------------
    //                  create the text based on one swap
    //--------------------------------------------------------------------------------
    function createText(element){
        //get all attributes from the data
        var data = $(element).data(),
            swapId = data['swapId'],
            swapText = data['text'],
            text = "[" + swapId,
            container = element.children('.container');

        if(swapId == "string"){
            return swapText;
        }

        //process all the data
        for (var attr in data) {
            //validate the attr have a single value
            if(typeof data[attr] === "string" && attr!= "swapId" && attr!= "text"){
                text += ' ' + attr + '="' + data[attr] + '"';
            }
        }

        text += " ]" + (swapText ? swapText : '');

        //validate if the swap can contain others swaps
        if(container.length > 0){
            //get the children of the swap
            var containerChildren = $(container[0]).children('.visual-content-layout-element');
            //validate the swap have children
            if(containerChildren.length > 0){
                //process all children of the swap
                for (var i = 0; i<containerChildren.length; i++) {
                    text += createText($(containerChildren[i]));
                }
            }
        }
        return text += "[/" + swapId + "]";
    }


    //--------------------------------------------------------------------------------
    //                 make the visual element able to drag and drop
    //--------------------------------------------------------------------------------
    function makeDragAndDrop() {

        $(".container").sortable({
            placeholder: "ui-state-highlight",
            connectWith: ".container",
            items: "div.visual-content-layout-element",
            axis: "y",
            opacity: 0.5,
            cursor: "move",
            stop: function( event, ui ) {
                var visualHelpArea = $(ui.item[0]).parents('.visual-content-layout-visual-help'),
                    addButton = $(visualHelpArea[0]).find('a'),
                    textArea = $(addButton[0]).data('textarea'),
                    text = getTextFromVisual($(visualHelpArea[0]));
                $("#" + textArea).val(text);
            }
        });

    }

    //--------------------------------------------------------------------------------
    //                 event click edit visual element
    //--------------------------------------------------------------------------------
    function editVisualElement(){
        //get the swap name of the swap to fin the respective form
        var swapName = $(this).data()['swapName'],
            url = '/VisualContentD8/visual_content_layout/swap_attributes_update_form/'+swapName,
            swapAttributes = $(this).parent().data();
        //set a class in the div to identify which div actualize
        $(this).parent().addClass("swap-actualize-div");
        //place the progress icon
        $('<div class="ajax-progress ajax-progress-throbber"><div class="throbber"></div></div>')
            .insertAfter($(this));
        //execute ajax call
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(data){
                //place the for in the special div for update dialogs
                $("#visual-content-layout-update-modal").html(data);
                //call set attributes
                setAttributesInForm(swapAttributes);
                //execute verticalTab behaviors to theme the vertical tabs
                Drupal.behaviors.verticalTabs.attach($('#visual-content-layout-update-modal'));
                //display modal dialog
                $("#visual-content-layout-update-modal").dialog({
                    modal: true,
                    draggable: false,
                    resizable: false,
                    minWidth: 1200
                });
            },
            complete: function(){
                $('.ajax-progress').remove();
            },
        });
    };

    //--------------------------------------------------------------------------------
    //                 place the actual attributes in update form modal
    //--------------------------------------------------------------------------------
    function setAttributesInForm(attributes){
        //iterate all attributes fd the div
        for(attr in attributes){
            //set default attributes
            var input = '#edit-swaps-' + attr,
                input = $(input.toLowerCase());
            input.val(attributes[attr]);
            //set own attributes
            input = '#edit-swaps-' + attributes.swapId + '-' + attr;
            input = $(input.toLowerCase());
            input.val(attributes[attr]);
        }
        //define the function of accept button, negate submit form
        $("#edit-swaps-accept").on("click", updateVisualElement);
        $("#visual-content-layout-update-modal form").submit(function(){ return false; });
    }

    //--------------------------------------------------------------------------------
    //      take the attributes in the update form modal and set in respective
    //--------------------------------------------------------------------------------
    function updateVisualElement(){
        //get the div to actualize and all inputs of the form
        var div = $('.swap-actualize-div'),
            elements = $(".ui-dialog-content :input"),
            swap = div.data("swapId");
        //iterate all inputs
        for(var i = 0; i<elements.length ; i++){
            //get the value of the input and the id
            var value = $(elements[i]).val(),
                data = $(elements[i]).attr('id');
            //validate the input have id and is not the submit button
            if(!data || data == "edit-swaps-accept"){
                continue;
            }
            //create the data name based in the id
            data = data.replace("edit-swaps-","");
            data = data.replace(swap+'-',"");
            //set the data
            div.data(data, value);
        }
        //get the parents to find the textarea
        var visualHelpArea = div.parents('.visual-content-layout-visual-help'),
            addButton = $(visualHelpArea[0]).find('a'),
            textArea = $(addButton[0]).data('textarea');
        //recreate the text in textarea
        var text = getTextFromVisual($(visualHelpArea[0]));
        $("#" + textArea).val(text);
        //remove the class that identify which div actualize
        div.removeClass("swap-actualize-div");
        //close modal dialog
        $(".ui-dialog-content").dialog("close");
    }

    //--------------------------------------------------------------------------------
    //                 event click delete visual element
    //--------------------------------------------------------------------------------
    function deleteVisualElement(){
        var parent = $(this).parent('.visual-content-layout-element'),
            visualHelpArea = $(parent).parents('.visual-content-layout-visual-help'),
            addButton = $(visualHelpArea[0]).find('a'),
            textArea = $(addButton[0]).data('textarea');
        //delete the element
        $(parent).remove();
        //recreate the text in textarea
        var text = getTextFromVisual($(visualHelpArea[0]));
        $("#" + textArea).val(text);
    };

}(jQuery, Drupal));
