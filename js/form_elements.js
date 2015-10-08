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

            $( ".colorpicker" ).click(function() {
                $('.colorpicker').colorpicker();
            });

        }
    }



}(jQuery, Drupal));