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
            if(colorElements.length > 0){

                for (var i = 0; i < colorElements.length; i++) {
                    // Initialize all colorpicker input elements.
                    var element = $(colorElements[i]),
                        id = "#" + element.attr('id'),
                        colorpickerclass = element.attr('id') + "_widget";
                    $(id).colorpicker({input: '#' + id, customClass: colorpickerclass});
                    $(id).after($('.' + colorpickerclass));
                }
            }
        }
    };
}(jQuery, Drupal));
