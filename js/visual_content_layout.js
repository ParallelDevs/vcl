/**
 * @file
 * Provides javascript methods for manage the visual help.
 */

(function ($, Drupal) {

  // Global variable base_path.
  var drupalBasePath = "";

  // Hide the visual help for document ready
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

      // Set the base path global.
      if (settings.visualContentLayout.base_path) {
        drupalBasePath = settings.visualContentLayout.base_path;
      }

      // Validate all the filter select for enable the button on it textArea.
      var filter = $('.filter-list');
      for (var i = 0; i < filter.length; i++) {

        // Get the parent of the actual select, if is undefined it send and error and stop de cycle.
        var filterParent = $(filter[i]).parents()[2];
        if(settings.visualContentLayout.enable_formats){
          // Show the enable/disable button for the visual help according the textFormat.
          if (settings.visualContentLayout.enable_formats[$(filter[i]).val()]) {
            $(filterParent).children('.visual-content-layout-button-wrap').show();
          }
          else {
            $(filterParent).children('.visual-content-layout-button-wrap').hide();
          }
        }
      }

      //--------------------------------------------------------------------------------
      //                          Event change the text format
      //--------------------------------------------------------------------------------
      $('.filter-list', context).change(function () {
        // Get the parent of the filter select.
        var selectParent = $(this).parents('.text-format-wrapper');

        // Validate if the textFormat use the visual help.
        if (settings.visualContentLayout.enable_formats[$(this).val()]) {
          selectParent.find('.visual-content-layout-btn').show();
        }
        else {
          selectParent.find('.visual-content-layout-btn').hide();
          selectParent.children('.visual-content-layout-visual-help').hide();
          $('.visual-content-layout-element').remove();
          selectParent.find('.visual-content-layout-btn').data('state', 'disable');
          selectParent.find('.visual-content-layout-btn').text('Enable Visual Content Layout');
          $(this).parents('.text-format-wrapper').find('.form-textarea').show();
          selectParent.children('#edit-body-0-format-guidelines').show();
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
        var buttonParent = $(button).parents()[0];

        if (enable) {
          $(buttonParent).children('.visual-content-layout-visual-help').show();
          $('#' + textArea).hide();
          $(buttonParent).find('.filter-guidelines').hide();
          $(button).data('state', 'enable');
          $(button).text('Disable Visual Content Layout');
          $(button).removeClass('fa-square-o');
          $(button).addClass('fa-check-square-o');
        }
        else {
          $(buttonParent).children('.visual-content-layout-visual-help').hide();
          $('#' + textArea).show();
          $(buttonParent).find('.filter-guidelines').show();
          $(button).data('state', 'disable');
          $(button).text('Enable Visual Content Layout');
          $(button).removeClass('fa-check-square-o');
          $(button).addClass('fa-square-o');
        }
      }

      //--------------------------------------------------------------------------------
      //                  Event click display swap select form
      //--------------------------------------------------------------------------------
      $('.visual-content-layout-form-button', context).click(function () {
        var textArea = $(this).data('textarea'),
          element = document.createElement('input'),
          id = $(this).parent().attr('id');

        $('<input>').attr("id", "visual-content-layout-actual-textarea")
          .attr("type", "hidden")
          .val(textArea)
          .appendTo($(".visual-content-layout-visual-help"));

        var position = $('<input>').attr("id", "visual-content-layout-element-position").attr("type", "hidden");

        if (id === 'vcl-top-link'){
          position.val('top');
        }
        else{
          position.val('bottom');
        }
        position.appendTo($(".visual-content-layout-visual-help"));

        // Delete the target class.
        $('.visual-content-layout-target').removeClass('visual-content-layout-target');
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

        var textArea = $('#visual-content-layout-actual-textarea').val(),
          textAreaElement = $('#' + textArea),
          textAreaParent = textAreaElement.parents()[2],
          visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help')
            .find('.visual-content-layout-elements-area'),
          position = $('#visual-content-layout-element-position').val();

        // Create the html element for the new swap.
        var element = document.createElement('div');

        $(element).addClass('visual-content-layout-element panel panel-default');

        // Create the icon for handle the drag.
        var dragIcon = $('<span/>').attr({class: 'fa fa-arrows dragIcon'});
        dragIcon.html(attributes.swapName);

        // Create the delete button for this element.
        var deleteButton = $('<i/>').attr({class: 'fa fa-trash-o fa-3 iconButton'})
          .on('click', deleteVisualElement);

        // Create the edit button for this element.
        var editButton = $('<i/>').attr({class: 'fa fa-pencil-square-o fa-3 iconButton'})
          .on('click', editVisualElement)
          .data('swapName', attributes.swapName);

        // Create the button for copy the element.
        var copyButton = $('<span/>').attr({class: 'fa fa-clone iconButton'})
          .on('click', copyVisualElement);

        dragIcon.appendTo(element);
        deleteButton.appendTo(element);
        editButton.appendTo(element);
        copyButton.appendTo(element);

        // Validate the swap can contain others swaps.
        if (attributes.container) {

          // Create the button for add swaps if have container.
          var addButton = $('<a>', {
            href: drupalBasePath + 'visual_content_layout/swap_select_form/' + attributes.swapId,
            class: 'fa fa-plus-square iconButton addButton'});
          // Add event to button.
          addButton.on('click', addContainerVisualElement);

          // Settings for create drupal ajax link.
          var element_settings = {};
          element_settings.url = addButton.attr('href');
          element_settings.event = 'click';
          element_settings.progress = {
            type: 'throbber',
            message: ''
          };
          var base = 'addButton';
          Drupal.ajax[base] = new Drupal.Ajax(base, addButton, element_settings);

          addButton.appendTo(element);
          $('<div>').addClass('visual-content-layout-container').appendTo($(element));
        }
        delete (attributes.container);

        var attrKeys = Object.keys(attributes);

        for (var i = 0; i < attrKeys.length; i++) {
          if (attrKeys[i] === 'swapId') {
            $(element).data(attrKeys[i], attributes[attrKeys[i]].replace('swap_', ''));
            continue;
          }
          if (attrKeys[i] !== '' && attrKeys[i] !== 'swapName') {
            $(element).data(attrKeys[i], attributes[attrKeys[i]]);
          }
        }

        var target = $('.visual-content-layout-target');

        if (position === 'top'){
          $(element).prependTo(visualHelpArea);
        }
        else{

          if (target[0]){
            $(element).prependTo(target);
          }
          else {
            $(element).appendTo(visualHelpArea);
          }
        }

        $('#' + textArea).val(getTextFromVisual(visualHelpArea));
        makeDragAndDrop();

        $('#visual-content-layout-actual-textarea').remove();
        $('#visual-content-layout-element-position').remove();
        $('.visual-content-layout-target').removeClass('visual-content-layout-target');
        delete (settings.visualContentLayout.attributes);
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
        // Get id of the respective textArea.
        var textArea = $(this).data('id');

        // Validate if the visual help is enable.
        if ($(this).data('state') === 'enable') {
          var swaps = settings.visualContentLayout.enable_swaps,
            swapNames = settings.visualContentLayout.swap_names;
          // Make the visual element with the text.
          getVisualElements(textArea, swaps, swapNames);
          makeDragAndDrop();
        }
        else {
          var buttonParent = $(this).parents()[0],
            visualHelpArea = $(buttonParent).children('.visual-content-layout-visual-help'),
            elementsContainer = visualHelpArea.children('.visual-content-layout-elements-area'),
            elements = elementsContainer.children('.visual-content-layout-element');
          // Delete elements.
          elements.remove();
        }
      });

      //--------------------------------------------------------------------------------
      //                  Transform text in visual elements
      //--------------------------------------------------------------------------------
      function getVisualElements(textArea, enableSwaps, swapNames) {

        var text = $('#' + textArea).val(),
          textAreaParent = $('#' + textArea).parents()[2],
          visualHelpArea = $(textAreaParent).children('.visual-content-layout-visual-help')
            .find('.visual-content-layout-elements-area'),
          chunks = text.split(/(\[{1,2}.*?\]{1,2})/),
          elements = [],
          father = [],
          count = 0,
          swap = null,
          swapText = false;

        for (var i = 0; i < chunks.length; i++) {

          // Save the original text in case of error in the swap pattern.
          var originalText = chunks[i],
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
              if (typeof enableSwaps[c.split(" ")[0]] === "undefined") {

                // Create a simple text swap.
                div = createHTMLDiv(originalText, null, swapNames);

                // Validate if the storage swap is a father of the created div.
                if (swap !== null) {
                  elements.push(swap);
                  father.push(elements.length - 1);
                }

                // Validate if that swap have a father.
                if (father.length > 0) {
                  elements.push(div);
                  swap = null;
                  swapText = false;
                  continue;
                }
                else {
                  $(div).appendTo($(visualHelpArea));
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
              var startIndex = c.indexOf("cssStyles"),
                middle_index = c.indexOf('"', startIndex),
                lastIndex = c.indexOf('"', middle_index + 1),
                cssStyle = c.substring(startIndex, lastIndex);

              // Save the attributes of the swaps.
              c = c.replace(" " + cssStyle, "");
              swap = c.trim().split(" ");
              swap.push(cssStyle);
              div = createHTMLDiv(originalText, swap, swapNames);

              // Validate if the swap can contain others swaps.
              if (enableSwaps[c.split(" ")[0]]) {
                // Insert addButton.
                var addButton = createAddButton(div.data('swapId'));
                addButton.appendTo($(div));
                $('<div>').addClass('visual-content-layout-container').appendTo($(div));
              }

              // Validate if that swap have a father.
              if (father.length > 0) {
                elements.push(div);
                swap = null;
                swapText = false;
                continue;
              }
              else {
                $(div).appendTo($(visualHelpArea));
                count = 0;
                continue;
              }
            }

            // Validate the chunk is a swap close.
            if (c[0] === '/') {
              c = c.substring(1, c.length);

              // Validate if the close character is for a father.
              if (swap === null) {
                var lastFather = father.pop(), fatherSwap = elements[lastFather];

                // Validate if exist a father.
                if (!fatherSwap) {
                  var div = createHTMLDiv(originalText, null, swapNames);
                  $(div).appendTo($(visualHelpArea));
                  count = 0;
                  continue;
                }

                // Validate the swap and close character are the same.
                if (fatherSwap[0] === c) {

                  // Create the father and add the child.
                  div = createHTMLDiv(originalText, fatherSwap, swapNames);
                  // Insert addButton.
                  addButton = createAddButton(div.data('swapId'));
                  addButton.appendTo($(div));
                  var ele = $('<div>').addClass('visual-content-layout-container').appendTo($(div));
                  while (elements[lastFather + 1]) {
                    $(elements[lastFather + 1]).appendTo(ele);
                    elements.splice(lastFather + 1, 1);
                  }

                  // Validate if the father have a father.
                  if (father.length === 0) {
                    $(div).appendTo($(visualHelpArea));
                    elements.splice(0, 1);
                  } else {
                    elements[lastFather] = div;
                  }
                  count = lastFather;
                  swapText = false;
                  continue;
                }
                else {
                  div = createHTMLDiv(originalText, null);
                  father.push(lastFather);
                  elements.push(div);
                  swap = null;
                  swapText = false;
                  continue;
                }
              }

              // Validate if the child swap and close character are the same.
              if (swap[0] === c) {
                div = createHTMLDiv(originalText, swap, swapNames);

                // Validate if the swap can contain others swaps.
                if (enableSwaps[c.split(" ")[0]]) {
                  // Insert addButton.
                  addButton = createAddButton(div.data('swapId'));
                  addButton.appendTo($(div));
                  $('<div>').addClass('visual-content-layout-container').appendTo($(div));
                }
                swap = null;
                swapText = false;

                //validate if that swap have a father
                if (father.length > 0) {
                  elements.push(div);
                  continue;
                }
                else {
                  $(div).appendTo($(visualHelpArea));
                  count = 0;
                  continue;
                }
              }
            }

            // Validate the swap is a valid swap.
            if (typeof enableSwaps[c.split(" ")[0]] === "undefined") {

              // Create a simple text swap.
              div = createHTMLDiv(originalText, null, swapNames);

              // Validate is the storage swap is a father of the created div.
              if (swap !== null) {
                elements.push(swap);
                father.push(elements.length - 1);
              }

              // Validate if that swap have a father.
              if (father.length > 0) {
                elements.push(div);
                swap = null;
                swapText = false;
                continue;
              }
              else {
                $(div).appendTo($(visualHelpArea));
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
            startIndex = c.indexOf("cssStyles");
            if(startIndex !== -1){
              lastIndex = c.indexOf('"', startIndex);
              lastIndex = c.indexOf('"', lastIndex + 1);
              cssStyle = c.substring(startIndex, lastIndex);
              c = c.replace(" " + cssStyle, "");
            }

            // Save the attributes of the swaps.
            swap = c.trim().split(" ");
            if (cssStyle) {swap.push(cssStyle); }
            swapText = true;
            count++;
            continue;
          }

          // Validate if the chunk is only text and is the first.
          if (swapText) {
            swap.push('text="' + originalText + '"');
          }
          else {
            div = createHTMLDiv(originalText, null, swapNames);

            // Validate if that swap have a father.
            if (father.length > 0) {
              elements.push(div);
              swap = null;
              swapText = false;
              continue;
            }
            else {
              $(div).appendTo($(visualHelpArea));
              count = 0;
              continue;
            }
          }
        }

        // Validate if are fathers in the array.
        if (father.length !== 0) {
          var remain_father = fatherSwap;
          lastFather = father.pop();
          fatherSwap = elements[lastFather];
          var fatherOriginalText = "[ " + fatherSwap.toString().replace(/,/gi, ' ') + " ]",
            errorFather = createHTMLDiv(fatherOriginalText, null, swapNames);

          elements.push(div);
          $(errorFather).appendTo($(visualHelpArea));
          while (elements[lastFather + 1]) {
            $(elements[lastFather + 1]).appendTo($(visualHelpArea));
            elements.splice(lastFather + 1, 1);
          }
        }
      }

      //--------------------------------------------------------------------------------
      //                       Create html object for the swap
      //--------------------------------------------------------------------------------
      function createHTMLDiv(originalText, swap, swapNames) {
        // Create the element and set the class.
        var element = $('<div>').addClass('visual-content-layout-element panel panel-default');

        // Create the delete button for this element.
        var deleteButton = $('<i/>').attr({class: 'fa fa-trash-o iconButton'})
          .on('click', deleteVisualElement);

        // Create the icon for handle the drag.
        var dragIcon = $('<span/>').attr({class: 'fa fa-arrows dragIcon'});

        // Validate if the swap is a valid swap.
        if (swap !== null) {

          var swapName = swapNames[swap[0]];

          // Create the edit button for this element.
          var editButton = $('<i/>').attr({class: 'fa fa-pencil-square-o fa-3 iconButton'})
            .on('click', editVisualElement)
            .data('swapName', swapName);

          // Set the name in data attributes.
          element.data('swapId', swap[0]);
          delete (swap[0]);

          // Set all other attributes.
          for (var idx = 1; idx < swap.length; idx++) {
            var attr = swap[idx].trim().replace(/\"/gi, '').split('=');
            if (attr.length < 3){
              element.data(attr[0], attr[1]);
            }
            else{
              element.data(attr[0], attr[1] + "=" + attr[2]);
            }
          }
          dragIcon.html(swapName);

          // Create the button for copy the element.
          var copyButton = $('<span/>').attr({class: 'fa fa-clone iconButton'})
            .on('click', copyVisualElement);

        } else {
          dragIcon.html("Text: " + originalText);
          element.data('swapId', "string");
          element.data('text', originalText);
        }

        dragIcon.appendTo(element);
        deleteButton.appendTo(element);
        if (editButton) {editButton.appendTo(element); }
        copyButton.appendTo(element);

        return element;
      }


      //--------------------------------------------------------------------------------
      //          Create button to display swap select form inside visual element
      //--------------------------------------------------------------------------------
      function createAddButton(swapId) {
        // Create the button for add swaps if have container.
        var addButton = $('<a>', {
          href: drupalBasePath + 'visual_content_layout/swap_select_form/' + swapId,
          class: 'fa fa-plus-square iconButton addButton'});
        // Add event to button.
        addButton.on('click', addContainerVisualElement);
        makeAjaxLink(addButton, 'addButton');

        return addButton;
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
      container = element.children('.visual-content-layout-container');

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
    $(".visual-content-layout-container").sortable({
      placeholder: "ui-state-highlight",
      connectWith: ".visual-content-layout-container",
      items: "div.visual-content-layout-element",
      axis: "y",
      opacity: 0.5,
      cursor: "move",
      handle: "span.dragIcon",
      stop: function (event, ui) {
        var elementsContainer = $(ui.item[0]).parents('.visual-content-layout-elements-area'),
          visualHelpArea = elementsContainer.parent(),
          addButton = $(visualHelpArea).find('a'),
          textArea = $(addButton[0]).data('textarea'),
          text = getTextFromVisual(elementsContainer);
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
      url = drupalBasePath + 'visual_content_layout/swap_attributes_update_form/' + swapName.replace(" ", ""),
      swapAttributes = $(this).parent().data(),
      dWidth = $(window).width() * 0.7;
    // Set a class in the div to identify which div actualize.
    $(this).parent().addClass("swap-actualize-div");

    // Place the progress icon.
    $('<i class="fa fa-clock-o dragIcon"></i>').insertAfter($(this));

    // Create a div for display the modal.
    $('<div id="visual-content-layout-update-modal"></div>').appendTo("body");

    // Execute ajax call.
    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'json',
      success: function (data) {

        // Place the for in the special div for update dialogs.
        $("#visual-content-layout-update-modal").html(data);

        $('.fa-clock-o').remove();

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
          minWidth: dWidth
        });

        $(".ui-dialog-titlebar-close").on("click", cancelVisualElement);

        Drupal.behaviors.visualContentLayoutElementsInit.attach($('#visual-content-layout-update-modal'));

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

        var inputType = $(input).attr('type');
        // Set attribute for checkbox.
        if(inputType === 'checkbox'){
          var checked = (attributes[attr] === '1') ? true : false;
          $(input).prop('checked', checked);
        }

        // Set own attributes.
        input = '#edit-swaps-' + attributes.swapId + '-' + attr;
        input = $(input.toLowerCase());
        input.val(attributes[attr]);

        inputType = $(input).attr('type');
        // Set attribute for checkbox.
        if(inputType === 'checkbox'){
          checked = (attributes[attr] === '1') ? true : false;
          $(input).prop('checked', checked);
        }
      }
    }

    // Validate exist the image manager
    var imageManager = $('.visual-content-layout-image-manager');

    if(imageManager.length > 0){

      $("[name = swaps_img_fid]").val(attributes.fid);
      $('.image_preview').attr('src', attributes.url);
      imageManager.attr('href', drupalBasePath + 'visual_content_layout/swap_image_manager/' + attributes.fid);
      makeAjaxLink(imageManager, 'visual-content-layout-image-manager');
    }

    // Define the function of accept button, negate submit form.
    $("#edit-swaps-accept").on("click", updateVisualElement);
    $("#edit-swaps-cancel").on("click", cancelVisualElement);
    $("#visual-content-layout-update-modal form").submit(function () {
      return false;
    });
  }

  //--------------------------------------------------------------------------------
  //                      Close update visual element modal
  //--------------------------------------------------------------------------------
  function cancelVisualElement() {
    // Close modal dialog
    $(".ui-dialog-content").dialog("close");
    $("#visual-content-layout-update-modal").remove();
  }

  //--------------------------------------------------------------------------------
  //      Take the attributes in the update form modal and set in respective
  //--------------------------------------------------------------------------------
  function updateVisualElement() {
    // Get the div to actualize and all inputs of the form.
    var div = $('.swap-actualize-div'),
      elements = $(".ui-dialog-content :input"),
      swap = div.data("swapId");

    // Clean all data from the swap
    div.removeData();
    div.data("swapId", swap);

    // Iterate all inputs.
    for (var i = 0; i < elements.length; i++) {

      // Get the value of the input and the id.
      var value = $(elements[i]).val(),
        data = $(elements[i]).attr('id'),
        inputType = $(elements[i]).attr('type');

      // Validate the input have id and is not the submit button.
      if (!data || data === "edit-swaps-accept" || data === "edit-swaps-cancel") {
        continue;
      }

      // Set value for checkbox.
      if(inputType === 'checkbox'){
        value = $(elements[i]).prop('checked') ? '1' : '0';
      }

      // Create the data name based in the id.
      data = data.replace("edit-swaps-", "");
      data = data.replace(swap + '-', "");

      // Set the data.
      div.data(data, value);
    }

    // Get the parents to find the textarea.
    var elementsContainer = div.parents('.visual-content-layout-elements-area'),
      visualHelpArea = elementsContainer.parent(),
      addButton = $(visualHelpArea).find('a'),
      textArea = $(addButton[0]).data('textarea');

    // Recreate the text in textarea.
    var text = getTextFromVisual(elementsContainer);
    $("#" + textArea).val(text);

    // Remove the class that identify which div actualize.
    div.removeClass("swap-actualize-div");

    // Close modal dialog
    $(".ui-dialog-content").dialog("close");
    $("#visual-content-layout-update-modal").remove();
  }

  //--------------------------------------------------------------------------------
  //                 Event click delete visual element
  //--------------------------------------------------------------------------------
  function deleteVisualElement() {
    var parent = $(this).parent('.visual-content-layout-element'),
      elementsContainer = parent.parents('.visual-content-layout-elements-area'),
      visualHelpArea = elementsContainer.parent(),
      addButton = $(visualHelpArea).find('a'),
      textArea = $(addButton[0]).data('textarea');

    // Delete the element
    $(parent).remove();

    // Recreate the text in textarea
    var text = getTextFromVisual(elementsContainer);
    $("#" + textArea).val(text);
  }

  //--------------------------------------------------------------------------------
  //                 Event click copy visual element
  //--------------------------------------------------------------------------------
  function copyVisualElement() {
    var parent = $(this).parent('.visual-content-layout-element'),
      elementsContainer = parent.parents('.visual-content-layout-elements-area'),
      visualHelpArea = elementsContainer.parent(),
      addButton = $(visualHelpArea).find('a'),
      textArea = $(addButton[0]).data('textarea');

    // Clone the element.
    parent.clone(true).insertAfter(parent);

    // Recreate the text in textarea.
    var text = getTextFromVisual(elementsContainer);
    $("#" + textArea).val(text);

  }

  //--------------------------------------------------------------------------------
  //                 Event click add visual element in container
  //--------------------------------------------------------------------------------
  function addContainerVisualElement() {
    // Delete the target class.
    $('.visual-content-layout-target').removeClass('visual-content-layout-target');

    var parent = $(this).parents('.visual-content-layout-element');
    if (parent.length > 1) {
      var element = parent.children('.visual-content-layout-container:first');
      element.addClass('visual-content-layout-target');
    }
    else{
      element = parent.children('.visual-content-layout-container');
      element.addClass('visual-content-layout-target');
    }

    // Set the actual textarea.
    var visualHelpArea = $(this).parents('.visual-content-layout-visual-help'),
      textArea = visualHelpArea.find('.visual-content-layout-form-button'),
      textAreaId = textArea.data('textarea');

    $('<input>').attr("id", "visual-content-layout-actual-textarea")
      .attr("type", "hidden")
      .val(textAreaId)
      .appendTo($(".visual-content-layout-visual-help"));

  }

  //--------------------------------------------------------------------------------
  //          Create button to display swap select form inside visual element
  //--------------------------------------------------------------------------------
  function makeAjaxLink(link, LinkClass) {
    // Settings for create drupal ajax link.
    var element_settings = {};
    element_settings.url = link.attr('href');
    element_settings.event = 'click';
    element_settings.progress = {
      type: 'throbber',
      message: ''
    };
    var base = LinkClass;
    Drupal.ajax[base] = new Drupal.Ajax(base, link, element_settings);
  }

}(jQuery, Drupal));
