/**
 * @file
 * Provides javascript methods for initialize the form elements that use jquery.
 */

(function ($, Drupal) {

  /**
   * Manage the display of the visual help.
   *
   * Methods that are responsible for show and hide the visual help according on the textFormat
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.visualContentLayoutElementsInit = {
    attach: function (context, settings) {

      // Get all colorpicker input elements.
      var colorElements = $('.colorpicker_input');

      // Validate if there is a colorpicker input element.
      if (colorElements.length > 0) {

        for (var i = 0; i < colorElements.length; i++) {
          // Initialize all colorpicker input elements.
          var element = $(colorElements[i]),
            id = "#" + element.attr('id'),
            colorpickerclass = element.attr('id') + "_widget";
          $(id).colorpicker({input: '#' + id, customClass: colorpickerclass});
          $(id).after($('.' + colorpickerclass));
        }
      }

      getYoutubeUrl($('.swaps_youtube'));

      //--------------------------------------------------------------------------------
      //                        Event on blur in youtube input
      //--------------------------------------------------------------------------------
      $('.swaps_youtube', context).blur(function () {
        getYoutubeUrl($(this));
      });

      //--------------------------------------------------------------------------------
      //                       Create valid url for embed videos
      //--------------------------------------------------------------------------------
      function getYoutubeUrl(video_input) {
        var youtubeUrl = video_input.val();
        if (youtubeUrl) {
          var youtubeId = youtubeUrl.split("=")[1],
            imageUrl = 'http://img.youtube.com/vi/' + youtubeId + '/0.jpg;';
          $('.youtube_preview').attr('src', imageUrl);
        }
      }
    }
  };

  /**
   * Manage the information of image manager.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.visualContentLayoutImageInfo = {
    attach: function (context, settings) {

      var imageManager = $('.visual-content-layout-image-manager');
      if(imageManager.length > 0){
        makeAjax(imageManager, 'visual-content-layout-image-manager');
      }

      var attributes = settings.visualContentLayout.image_attributes,
          imageControl = $('[name = swaps_img_url]');

      if (attributes && imageControl.length > 0) {
        var imagePreview = $('.image_preview'),
            fid = $('[name = swaps_img_fid]');
        // Set url in image preview.
        imagePreview.attr('src', attributes.url);
        // Set attributes in controls.
        fid.val(attributes.fid);
        imageControl.val(attributes.url);
        // Change fid in call image manager
        var url = settings.visualContentLayout.base_path + 'visual_content_layout/swap_image_manager/' + attributes.fid;
        imageManager = $('.visual-content-layout-image-manager').attr('href', url);
        makeAjax(imageManager, 'visual-content-layout-image-manager');
        delete (settings.visualContentLayout.image_attributes);
      }

      function makeAjax(link, linkClass){
        // Settings for create drupal ajax link.
        var element_settings = {};
        element_settings.url = link.attr('href');
        element_settings.event = 'click';
        element_settings.progress = {
          type: 'throbber',
          message: ''
        };
        var base = linkClass;

        var ajaxLink = Drupal.ajax['visual-content-layout-image-manager'];

        if(ajaxLink){
          var newLink = $('<a>',
            { href: link.attr('href'),
              class: 'visual-content-layout-image-manager',
              text: 'Open Image Manager'});
          $('.visual-content-layout-image-manager').replaceWith(newLink);
          Drupal.ajax[base] = new Drupal.Ajax(base, newLink, element_settings);
        }
        else{
          Drupal.ajax[base] = new Drupal.Ajax(base, link, element_settings);
        }

        var otro = Drupal.ajax['visual-content-layout-image-manager'];

      }

    }
  };

  /**
   * Manage the display of image manager controls.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.visualContentLayoutImageManager = {
    attach: function (context, settings) {

      var imageId = $('.imageId'),
          deleted = settings.visualContentLayout.deleted;

      if(imageId.val() !== "0" && !deleted){
        $('.js-form-file').prop('disabled', true);
      }else{
        $('.js-form-file').prop('disabled', false);
        $('.visual-content-layout-deleteImage').addClass('hidden');
        delete (settings.visualContentLayout.deleted);
      }

    }
  };

}(jQuery, Drupal));
