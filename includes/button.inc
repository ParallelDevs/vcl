<?php

function visual_content_layout_button_info(&$swaps) {
  $swaps['button'] = array(
    'title' => t('Button'),
    'description' => t('Insert a link formatted as a button.'),
    'process callback' => 'visual_content_layout_swap_button',
  );
}

/*
 * Provides process callback for Shortcode button.
 */
function visual_content_layout_swap_button($attrs, $text) {
  $attrs = visual_content_layout_attrs(array(
    'title' => 'title default',
    'class' => 'button',
    'url' => '',
    'path' => '<front>',
  ),
    $attrs
  );

  $class = visual_content_layout_add_class($attrs['class'], 'button');
  if ($attrs['url']) {
    $attrs['path'] = $attrs['url'];
  }
  $path = $attrs['path'];

  return visual_content_layout_button_theme($attrs);
}

function visual_content_layout_button_theme($attrs) {

  $theme = file_get_contents('./email.html');

  return $theme;

}

