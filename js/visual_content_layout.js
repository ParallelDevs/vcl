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

      // Validate all the filter select for enable the button on it textArea.
      var filter = $('.filter-list');
      for (var i = 0; i < filter.length; i++) {

        // Get the parent of the actual select, if is undefined it send and error and stop de cycle.
        var filter_parent = $(filter[i]).parents()[2];

        // Show the enable/disable button for the visual help according the textFormat.
        if (settings.visualContentLayout.enable_formats[$(filter[i]).val()]) {
          $(filter_parent).children('.visual-content-layout-button-wrap').show();
        }
        else {
          $(filter_parent).children('.visual-content-layout-button-wrap').hide();
        }
      }

      //--------------------------------------------------------------------------------
      //                          Event change the text format
      //--------------------------------------------------------------------------------
      $('.filter-list', context).change(function () {
        // Get the parent of the filter select.
        var select_parent = $(this).parents()[2];

        // Validate if the textFormat use the visual help.
        if (settings.visualContentLayout.enable_formats[$(this).val()]) {
          $(select_parent).children('.visual-content-layout-button-wrap').show();
        }
        else {
          $(select_parent).children('.visual-content-layout-button-wrap').hide();
          $(select_parent).children('.visual-content-layout-visual-help').hide();
          $(select_parent).children('.visual-content-layout-button-wrap')
            .find('.visual-content-layout-btn').data('state', 'disable');
          $(select_parent).children('.visual-content-layout-button-wrap')
            .find('.visual-content-layout-btn').text('Enable Visual Content Layout');
          $(this).parents('.text-format-wrapper').find('.form-textarea').show();
          $(select_parent).children('#edit-body-0-format-guidelines').show();
        }
      });

      //--------------------------------------------------------------------------------
      //                  Event click enable/disable visual help button
      //--------------------------------------------------------------------------------
      $('.visual-content-layout-btn', context).click(function () {
        // Get id of the respective textArea.
        var textArea = $(this).data('id');

        // Validate if the visual help is disable.
        if ($(this).data('state') === 'disable') {
          handleVisualHelp(this, textArea, true);
        }
        else {
          handleVisualHelp(this, textArea, false);
        }
      });


      //--------------------------------------------------------------------------------
      //                        Show and hide the visual help
      //--------------------------------------------------------------------------------
      function handleVisualHelp(button, textArea, enable) {
        // Show the visual help section of the respective textArea.
        var button_parent = $(button).parents()[1];

        if (enable) {
          $(button_parent).children('.visual-content-layout-visual-help').show();
          $('#' + textArea).hide();
          $(button_parent).find('.filter-guidelines').hide();
          $(button).data('state', 'enable');
          $(button).text('Disable Visual Content Layout');
        }
        else {
          $(button_parent).children('.visual-content-layout-visual-help').hide();
          $('#' + textArea).show();
          $(button_parent).find('.filter-guidelines').show();
          $(button).data('state', 'disable');
          $(button).text('Enable Visual Content Layout');
        }
      }

      //--------------------------------------------------------------------------------
      //                  Event click display swap select form
      //--------------------------------------------------------------------------------
      $('.visual-content-layout-form-button', context).click(function () {
        var textArea = $(this).data('textarea'),
          element = document.createElement('input');
        $(element).attr("id", "visual-content-layout-actual-textarea")
          .attr("type", "hidden")
          .val(textArea)
          .appendTo($(".visual-content-layout-visual-help"));
      });

    }
  };

  /**
   * Manage the information send by each swap attributes form.
   *
   * Methods that are responsible for take the information save the attributes and create the visual element.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.visualContentLayoutForm = {
    attach: function (context, settings) {

      var attributes = settings.visualContentLayout.attributes;

      if (attributes) {

        var text_area = $('#visual-content-layout-actual-textarea').val(),
          text_area_element = $('#' + text_area),
          text_area_parent = text_area_element.parents()[2],
          visual_help_area = $(text_area_parent).children('.visual-content-layout-visual-help');

        // Create the html element for the new swap.
        var element = document.createElement('div');

        $(element).addClass('visual-content-layout-element panel panel-default')
          .html(attributes.swapName);

        // Create the delete button for this element.
        var delete_button = $('<i/>').attr({class: 'fa fa-trash-o fa-3 iconButton'})
          .on('click', deleteVisualElement);

        // Create the edit button for this element.
        var edit_button = $('<i/>').attr({class: 'fa fa-pencil-square-o fa-3 iconButton'})
          .on('click', editVisualElement)
          .data('swapName', attributes.swapName);

        delete_button.appendTo(element);
        edit_button.appendTo(element);

        // Validate the swap can contain others swaps.
        if (attributes.container) {
          $('<div>').addClass('container').appendTo($(element));
        }
        delete (attributes.container);

        var attr_keys = Object.keys(attributes);

        for (var i = 0; i < attr_keys.length; i++) {
          if (attr_keys[i] !== '' && attr_keys[i] !== 'swapName') {
            $(element).data(attr_keys[i], attributes[attr_keys[i]]);
          }
        }

        $(element).appendTo(visual_help_area);

        createTextSwap($(element), text_area);
        makeDragAndDrop();

      }

      //--------------------------------------------------------------------------------
      //          Convert the visual help element into text for the textArea
      //--------------------------------------------------------------------------------
      function createTextSwap(visual_element, actual_text_area) {
        var data = $(visual_element).data(),
          text = (data.text) ? "" + data.text : "",
          attributes_text = ' ',
          swap_text = '[' + data.swapId;

        for (var attr in data) {
          // Validate the attribute is not the name, text o id.
          if (attr !== 'swapId' && attr !== 'text') {
            attributes_text += attr.trim() + '="' + data[attr].trim() + '" ';
          }
        }
        swap_text += attributes_text + ']' + text + '[/' + data.swapId + ']';

        $('#' + actual_text_area).val($('#' + actual_text_area).val() + swap_text);
        $('#visual-content-layout-actual-textarea').remove();
      }
    }
  };

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
      //                  Event click enable/disable visual help button
      //--------------------------------------------------------------------------------
      $('.visual-content-layout-btn', context).click(function () {
        // Get id of the respective text_area.
        var text_area = $(this).data('id');

        // Validate if the visual help is enable.
        if ($(this).data('state') === 'enable') {
          var swaps = settings.visualContentLayout.enable_swaps,
            swap_names = settings.visualContentLayout.swap_names;
          // Make the visual element with the text.
          getVisualElements(text_area, swaps, swap_names);
          makeDragAndDrop();
        }
        else {
          $('.visual-content-layout-element').remove();
        }
      });

      //--------------------------------------------------------------------------------
      //                  Transform text in visual elements
      //--------------------------------------------------------------------------------
      function getVisualElements(text_area, enable_swaps, swap_names) {

        var text = $('#' + text_area).val(),
          text_area_parent = $('#' + text_area).parents()[2],
          visual_help_area = $(text_area_parent).children('.visual-content-layout-visual-help'),
          chunks = text.split(/(\[{1,2}.*?\]{1,2})/),
          elements = [],
          father = [],
          count = 0,
          swap = null,
          swap_text = false;

        for (var i = 0; i < chunks.length; i++) {

          // Save the original text in case of error in the swap pattern.
          var original_text = chunks[i],
            c = chunks[i].trim();

          if (c === '') {
            continue;
          }

          // Validate the chunk is a swap head.
          if (c[0] === '[') {

            // Delete the first and last character
            c = c.substring(1, c.length - 1).trim();

            // Validate if the swap have the close character in the head.
            if (c[c.length - 1] === '/') {

              // Validate the swap is a valid swap.
              if (typeof enable_swaps[c.split(" ")[0]] === "undefined") {

                // Create a simple text swap.
                div = createHTMLDiv(original_text, null, swap_names);

                // Validate if the storage swap is a father of the created div.
                if (swap !== null) {
                  elements.push(swap);
                  father.push(elements.length - 1);
                }

                // Validate if that swap have a father.
                if (father.length > 0) {
                  elements.push(div);
                  swap = null;
                  swap_text = false;
                  continue;
                }
                else {
                  $(div).appendTo($(visual_help_area));
                  count = 0;
                  continue;
                }
              }
              c = c.substring(0, c.length - 1);

              // Validate if the new swap is a father.
              if (count > 0 && swap != null) {
                elements.push(swap);
                father.push(elements.length - 1);
              }
              // Get cssStyle.
              var start_index = c.indexOf("cssStyles"),
                middle_index = c.indexOf('"', start_index),
                last_index = c.indexOf('"', middle_index + 1),
                css_style = c.substring(start_index, last_index);

              // Save the attributes of the swaps.
              c = c.replace(" " + css_style, "");
              swap = c.trim().split(" ");
              swap.push(css_style);
              div = createHTMLDiv(original_text, swap, swap_names);

              // Validate if the swap can contain others swaps.
              if (enable_swaps[c.split(" ")[0]]) {
                $('<div>').addClass('container').appendTo($(div));
              }

              // Validate if that swap have a father.
              if (father.length > 0) {
                elements.push(div);
                swap = null;
                swap_text = false;
                continue;
              }
              else {
                $(div).appendTo($(visual_help_area));
                count = 0;
                continue;
              }
            }

            // Validate the chunk is a swap close.
            if (c[0] === '/') {
              c = c.substring(1, c.length);

              // Validate if the close character is for a father.
              if (swap === null) {
                var last_father = father.pop(), father_swap = elements[last_father];

                // Validate if exist a father.
                if (!father_swap) {
                  var div = createHTMLDiv(original_text, null, swap_names);
                  $(div).appendTo($(visual_help_area));
                  count = 0;
                  continue;
                }

                // Validate the swap and close character are the same.
                if (father_swap[0] === c) {

                  // Create the father and add the child.
                  div = createHTMLDiv(original_text, father_swap, swap_names);
                  var ele = $('<div>').addClass('container').appendTo($(div));
                  while (elements[last_father + 1]) {
                    $(elements[last_father + 1]).appendTo(ele);
                    elements.splice(last_father + 1, 1);
                  }

                  // Validate if the father have a father.
                  if (father.length === 0) {
                    $(div).appendTo($(visual_help_area));
                    elements.splice(0, 1);
                  } else {
                    elements[last_father] = div;
                  }
                  count = last_father;
                  swap_text = false;
                  continue;
                }
                else {
                  div = createHTMLDiv(original_text, null);
                  father.push(last_father);
                  elements.push(div);
                  swap = null;
                  swap_text = false;
                  continue;
                }
              }

              // Validate if the child swap and close character are the same.
              if (swap[0] === c) {
                div = createHTMLDiv(original_text, swap, swap_names);

                // Validate if the swap can contain others swaps.
                if (enable_swaps[c.split(" ")[0]]) {
                  $('<div>').addClass('container').appendTo($(div));
                }
                swap = null;
                swap_text = false;

                //validate if that swap have a father
                if (father.length > 0) {
                  elements.push(div);
                  continue;
                }
                else {
                  $(div).appendTo($(visual_help_area));
                  count = 0;
                  continue;
                }
              }
            }

            // Validate the swap is a valid swap.
            if (typeof enable_swaps[c.split(" ")[0]] === "undefined") {

              // Create a simple text swap.
              div = createHTMLDiv(original_text, null, swap_names);

              // Validate is the storage swap is a father of the created div.
              if (swap !== null) {
                elements.push(swap);
                father.push(elements.length - 1);
              }

              // Validate if that swap have a father.
              if (father.length > 0) {
                elements.push(div);
                swap = null;
                swap_text = false;
                continue;
              }
              else {
                $(div).appendTo($(visual_help_area));
                count = 0;
                continue;
              }
            }

            // Validate if the new swap is a father.
            if (count > 0 && swap !== null) {
              elements.push(swap);
              father.push(elements.length - 1);
            }

            // Get cssStyle.
            start_index = c.indexOf("cssStyles");
            last_index = c.indexOf('"', start_index);
            last_index = c.indexOf('"', last_index + 1);
            css_style = c.substring(start_index, last_index);

            // Save the attributes of the swaps.
            c = c.replace(" " + css_style, "");
            swap = c.trim().split(" ");
            swap.push(css_style);
            swap_text = true;
            count++;
            continue;
          }

          // Validate if the chunk is only text and is the first.
          if (swap_text) {
            swap.push('text="' + original_text + '"');
          }
          else {
            div = createHTMLDiv(original_text, null, swap_names);

            // Validate if that swap have a father.
            if (father.length > 0) {
              elements.push(div);
              swap = null;
              swap_text = false;
              continue;
            }
            else {
              $(div).appendTo($(visual_help_area));
              count = 0;
              continue;
            }
          }
        }

        // Validate if are fathers in the array.
        if (father.length !== 0) {
          var remain_father = father_swap;
          last_father = father.pop();
          father_swap = elements[last_father];
          var father_original_text = "[ " + father_swap.toString().replace(/,/gi, ' ') + " ]",
            errorFather = createHTMLDiv(father_original_text, null, swap_names);

          elements.push(div);
          $(errorFather).appendTo($(visual_help_area));
          while (elements[last_father + 1]) {
            $(elements[last_father + 1]).appendTo($(visual_help_area));
            elements.splice(last_father + 1, 1);
          }
        }
      }

      //--------------------------------------------------------------------------------
      //                       Create html object for the swap
      //--------------------------------------------------------------------------------
      function createHTMLDiv(original_text, swap, swap_names) {
        // Create the element and set the class.
        var element = $('<div>').addClass('visual-content-layout-element panel panel-default');

        // Create the delete button for this element.
        var delete_button = $('<i/>').attr({class: 'fa fa-trash-o iconButton'})
          .on('click', deleteVisualElement);

        // Create the icon for handle the drag.
        var drag_icon = $('<span/>').attr({class: 'fa fa-arrows dragIcon'});

        // Validate if the swap is a valid swap.
        if (swap !== null) {

          var swap_name = swap_names[swap[0]];

          // Create the edit button for this element.
          var edit_button = $('<i/>').attr({class: 'fa fa-pencil-square-o fa-3 iconButton'})
            .on('click', editVisualElement)
            .data('swapName', swap_name);

          // Set the name in data attributes.
          element.data('swapId', swap[0]);
          delete (swap[0]);

          // Set all other attributes.
          for (var idx = 1; idx < swap.length; idx++) {
            var attr = swap[idx].trim().replace(/\"/gi, '').split('=');
            element.data(attr[0], attr[1]);
          }
          drag_icon.html(swap_name);
        } else {
          drag_icon.html("Text: " + original_text);
          element.data('swapId', "string");
          element.data('text', original_text);
        }

        drag_icon.appendTo(element);
        delete_button.appendTo(element);

        if (edit_button){
          edit_button.appendTo(element);
        }

        return element;
      }
    }
  };

  //--------------------------------------------------------------------------------
  //                  Transform visual elements in text
  //--------------------------------------------------------------------------------
  function getTextFromVisual(visualHelpArea) {

    var children = $(visualHelpArea).children('.visual-content-layout-element'),
      text = '';

    // Process all children.
    for (var i = 0; i < children.length; i++) {
      text += createText($(children[i]));
    }

    return text;
  }

  //--------------------------------------------------------------------------------
  //                  Create the text based on one swap
  //--------------------------------------------------------------------------------
  function createText(element) {

    // Get all attributes from the data.
    var data = $(element).data(),
      swapId = data.swapId,
      swapText = data.text,
      text = "[" + swapId,
      container = element.children('.container');

    if (swapId === "string") {
      return swapText;
    }

    // Process all the data.
    for (var attr in data) {

      // Validate the attr have a single value.
      if (typeof data[attr] === "string" && attr !== "swapId" && attr !== "text") {
        text += ' ' + attr + '="' + data[attr] + '"';
      }
    }

    text += " ]" + (swapText ? swapText : '');

    // Validate if the swap can contain others swaps.
    if (container.length > 0) {

      // Get the children of the swap.
      var containerChildren = $(container[0]).children('.visual-content-layout-element');

      // Validate the swap have children.
      if (containerChildren.length > 0) {

        // Process all children of the swap.
        for (var i = 0; i < containerChildren.length; i++) {
          text += createText($(containerChildren[i]));
        }
      }
    }

    text += "[/" + swapId + "]";
    return text;
  }


  //--------------------------------------------------------------------------------
  //                 Make the visual element able to drag and drop
  //--------------------------------------------------------------------------------
  function makeDragAndDrop() {
    $(".container").sortable({
      placeholder: "ui-state-highlight",
      connectWith: ".container",
      items: "div.visual-content-layout-element",
      axis: "y",
      opacity: 0.5,
      cursor: "move",
      handle: "span",
      stop: function (event, ui) {
        var visualHelpArea = $(ui.item[0]).parents('.visual-content-layout-visual-help'),
          addButton = $(visualHelpArea[0]).find('a'),
          textArea = $(addButton[0]).data('textarea'),
          text = getTextFromVisual($(visualHelpArea[0]));
        $("#" + textArea).val(text);
      }
    });

  }

  //--------------------------------------------------------------------------------
  //                 Event click edit visual element
  //--------------------------------------------------------------------------------
  function editVisualElement() {
    // Get the swap name of the swap to fin the respective form.
    var swapName = $(this).data().swapName,
      url = '/VisualContentD8/visual_content_layout/swap_attributes_update_form/' + swapName,
      swapAttributes = $(this).parent().data();

    // Set a class in the div to identify which div actualize.
    $(this).parent().addClass("swap-actualize-div");

    // Place the progress icon.
    $('<div class="ajax-progress ajax-progress-throbber"><div class="throbber"></div></div>').insertAfter($(this));

    // Execute ajax call.
    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'json',
      success: function (data) {

        // Place the for in the special div for update dialogs.
        $("#visual-content-layout-update-modal").html(data);

        // Call set attributes.
        setAttributesInForm(swapAttributes);

        // Execute verticalTab behaviors to theme the vertical tabs.
        Drupal.behaviors.verticalTabs.attach($('#visual-content-layout-update-modal'));

        // Display modal dialog.
        $("#visual-content-layout-update-modal").dialog({
          title: "Swap Atributes",
          modal: true,
          draggable: false,
          resizable: false,
          minWidth: 1200
        });
        Drupal.behaviors.visualContentLayoutElementsInit.attach($('#visual-content-layout-update-modal'));
      },
      complete: function () {
        $('.ajax-progress').remove();
      }
    });
  }

  //--------------------------------------------------------------------------------
  //                 Place the actual attributes in update form modal
  //--------------------------------------------------------------------------------
  function setAttributesInForm(attributes) {

    // Iterate all attributes of the div.
    for(var attr in attributes) {
      // Validate is a key value position.
      if(typeof attributes[attr] !== 'object'){
        // Set default attributes.
        var input = '#edit-swaps-' + attr;
        input = $(input.toLowerCase());
        input.val(attributes[attr]);

        // Set own attributes.
        input = '#edit-swaps-' + attributes.swapId + '-' + attr;
        input = $(input.toLowerCase());
        input.val(attributes[attr]);
      }
    }

    // Define the function of accept button, negate submit form.
    $("#edit-swaps-accept").on("click", updateVisualElement);
    $("#visual-content-layout-update-modal form").submit(function () {
      return false;
    });
  }

  //--------------------------------------------------------------------------------
  //      Take the attributes in the update form modal and set in respective
  //--------------------------------------------------------------------------------
  function updateVisualElement() {
    // Get the div to actualize and all inputs of the form.
    var div = $('.swap-actualize-div'),
      elements = $(".ui-dialog-content :input"),
      swap = div.data("swapId");

    // Iterate all inputs.
    for (var i = 0; i < elements.length; i++) {

      // Get the value of the input and the id.
      var value = $(elements[i]).val(),
        data = $(elements[i]).attr('id');

      // Validate the input have id and is not the submit button.
      if (!data || data === "edit-swaps-accept") {
        continue;
      }

      // Create the data name based in the id.
      data = data.replace("edit-swaps-", "");
      data = data.replace(swap + '-', "");

      // Set the data.
      div.data(data, value);
    }

    // Get the parents to find the textarea.
    var visualHelpArea = div.parents('.visual-content-layout-visual-help'),
      addButton = $(visualHelpArea[0]).find('a'),
      textArea = $(addButton[0]).data('textarea');

    // Recreate the text in textarea.
    var text = getTextFromVisual($(visualHelpArea[0]));
    $("#" + textArea).val(text);

    // Remove the class that identify which div actualize.
    div.removeClass("swap-actualize-div");

    // Close modal dialog
    $(".ui-dialog-content").dialog("close");
  }

  //--------------------------------------------------------------------------------
  //                 Event click delete visual element
  //--------------------------------------------------------------------------------
  function deleteVisualElement() {
    var parent = $(this).parent('.visual-content-layout-element'),
      visualHelpArea = $(parent).parents('.visual-content-layout-visual-help'),
      addButton = $(visualHelpArea[0]).find('a'),
      textArea = $(addButton[0]).data('textarea');

    // Delete the element
    $(parent).remove();

    // Recreate the text in textarea
    var text = getTextFromVisual($(visualHelpArea[0]));
    $("#" + textArea).val(text);
  }

}(jQuery, Drupal));
