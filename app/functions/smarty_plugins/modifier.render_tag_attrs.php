<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier<br>
 * Name:     render_tag_attrs<br>
 * Purpose:  Renders tag attributes from array
 * Example:  {["data-ca-url":"http://example.com", "data-ca-image":"http://example.com/image.png"]|render_tag_attrs}
 * -------------------------------------------------------------
 *
 * @param array $attributes
 *
 * @return string
 */
function smarty_modifier_render_tag_attrs($attributes)
{
    $attributes = (array) $attributes;
    $result = [];

    foreach ($attributes as $name => $value) {
        if (is_bool($value)) {
            if ($value) {
                $result[] = $name;
            }
            continue;
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }

        $result[] = sprintf('%s="%s"', $name, htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE));
    }

    return implode(' ', $result);
}
