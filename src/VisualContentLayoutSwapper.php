<?php
/**
 * @file
 * Contains VisualContentLayoutSwapper.
 */

namespace Drupal\visual_content_layout;

class VisualContentLayoutSwapper {

  /**
   * Logic for replace the swap pattern with the html code corresponding.
   */
  public static function swapProcess($text) {

    // Get all the swaps plugins.
    $manager = \Drupal::service('plugin.manager.swaps');
    $swaps = $manager->getDefinitions();

    if (empty($swaps)) {
      return $text;
    }

    // Processing recursively,
    // now embedding tags within other tags is supported!
    $chunks = preg_split('!(\[{1,2}.*?\]{1,2})!', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

    $heap = array();
    $heap_index = array();

    foreach ($chunks as $c) {
      if (!$c) {
        continue;
      }

      $escaped = FALSE;

      if ((substr($c, 0, 2) == '[[') && (substr($c, -2, 2) == ']]')) {
        $escaped = TRUE;
        // Checks media tags, eg: [[{ }]].
        if ((substr($c, 0, 3) != '{') && (substr($c, -3, 1) != '}')) {
          // Removes the outer [].
          $c = substr($c, 1, -1);
        }
      }
      // Decide this is a swap tag or not.
      if (!$escaped && ($c[0] == '[') && (substr($c, -1, 1) == ']')) {
        // The $c maybe contains swap macro.

        // This is maybe a self-closing tag.
        // Removes outer [].
        $original_text = $c;
        $c = substr($c, 1, -1);
        $c = trim($c);

        $ts = explode(' ', $c);
        $tag = array_shift($ts);
        $tag = trim($tag, '/');

        $x = self::swapIsTag($tag, $swaps);
        if (!$x) {
          // The current tag is not enabled.
          array_unshift($heap_index, '_string_');
          array_unshift($heap, $original_text);
        }
        elseif (substr($c, -1, 1) == '/') {
          // Processes a self closing tag, - it has "/" at the end-
          /*
           * The exploded array elements meaning:
           * 0 - the full tag text?
           * 1/5 - An extra [] to allow for escaping Shortcode with double [[]].
           * 2 - The Swap name.
           * 3 - The Swap argument list.
           * 4 - The content of a Swap when it wraps some content.
           */

          $m = array(
            $c,
            '',
            $tag,
            implode(' ', $ts),
            NULL,
            '',
          );
          array_unshift($heap_index, '_string_');
          array_unshift($heap, self::processTag($m, $swaps));
        }
        elseif ($c[0] == '/') {
          // Indicate a closing tag, so we process the heap.
          $closing_tag = substr($c, 1);

          $process_heap = array();
          $process_heap_index = array();
          $found = FALSE;

          // Get elements from heap and process.
          do {
            $tag = array_shift($heap_index);
            $heap_text = array_shift($heap);

            if ($closing_tag == $tag) {
              // Process the whole tag.
              $m = array(
                $tag . ' ' . $heap_text,
                '',
                $tag,
                $heap_text,
                implode('', $process_heap),
                '',
              );
              $str = self::processTag($m, $swaps);
              array_unshift($heap_index, '_string_');
              array_unshift($heap, $str);
              $found = TRUE;
            }
            else {
              array_unshift($process_heap, $heap_text);
              array_unshift($process_heap_index, $tag);
            }
          } while (!$found && $heap);

          if (!$found) {
            foreach ($process_heap as $val) {
              array_unshift($heap, $val);
            }
            foreach ($process_heap_index as $val) {
              array_unshift($heap_index, $val);
            }
          }

        }
        else {
          // This is a starting tag. Put it to the heap.
          array_unshift($heap_index, $tag);
          array_unshift($heap, implode(' ', $ts));
        }
        // If escaped or not a Swap.
      }
      else {
        // Maybe not found a pair?
        array_unshift($heap_index, '_string_');
        array_unshift($heap, $c);
      }
    }

    return (implode('', array_reverse($heap)));
  }// End swap_process.

  /**
   * Validate the tag.
   */
  public static function swapIsTag($tag, $swaps) {
    if (isset($swaps[$tag])) {
      return TRUE;
    }

    return FALSE;
  }// End swap_is_tag.

  /**
   * Process attributes and call the plugin callback function.
   */
  public static function processTag($m, $swaps) {

    $tag = $m[2];

    if (!empty($swaps[$tag])) {
      // Process if tag exists (enabled).
      $attr = self::parseAttrs($m[3]);
      /*
      * 0 - the full tag text?
      * 1/5 - An extra [ or ] to allow for escaping shortcodes with double [[]]
      * 2 - The Swap name
      * 3 - The Swap argument list
      * 4 - The content of a Swap when it wraps some content.
      * */

      $swap = \Drupal::service('plugin.manager.swaps')->createInstance($tag);

      if (!is_null($m[4])) {
        // This is an enclosing tag, means extra parameter is present.
        return $m[1] . $swap->processCallback($attr, $m[4]) . $m[5];
      }
      else {
        // This is a self-closing tag.
        return $m[1] . $swap->processCallback($attr) . $m[5];
      }
    }
    elseif (is_null($m[4])) {
      return $m[4];
    }
    return '';
  }// End process_tag.


  /**
   * Extract attributes from the tag text.
   */
  public static function parseAttrs($text) {
    $attrs = array();
    $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
    $text = html_entity_decode($text);
    if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
      foreach ($match as $m) {
        if (!empty($m[1])) {
          $attrs[strtolower($m[1])] = stripcslashes($m[2]);
        }
        elseif (!empty($m[3])) {
          $attrs[strtolower($m[3])] = stripcslashes($m[4]);
        }
        elseif (!empty($m[5])) {
          $attrs[strtolower($m[5])] = stripcslashes($m[6]);
        }
        elseif (isset($m[7]) && strlen($m[7])) {
          $attrs[] = stripcslashes($m[7]);
        }
        elseif (isset($m[8])) {
          $attrs[] = stripcslashes($m[8]);
        }
      }
    }
    else {
      $attrs = ltrim($text);
    }
    return $attrs;
  }

}
