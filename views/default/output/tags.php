<?php

/**
 * Elgg tags
 * Tags can be a single string (for one tag) or an array of strings. Accepts all output/tag options
 *
 * @uses $vars['value']      Array of tags or a string
 * @uses $vars['entity']     Optional. Entity whose tags are being displayed (metadata ->tags)
 * @uses $vars['list_class'] Optional. Additional classes to be passed to <ul> element
 * @uses $vars['item_class'] Optional. Additional classes to be passed to <li> elements
 * @uses $vars['icon']       Optional. Icon name to be used (default: tag)
 *                           Set to false to not render an icon
 * @uses $vars['icon_class'] Optional. Additional classes to be passed to tags icon image
 * @uses $vars['separator']  Optional. HTML to place between tags. (default: ", ")
 */
if (isset($vars['entity'])) {
	$vars['tags'] = $vars['entity']->tags;
	unset($vars['entity']);
}

$value = elgg_extract('value', $vars);
unset($vars['value']);
if (empty($vars['tags']) && (!empty($value) || $value === 0 || $value === '0')) {
	$vars['tags'] = $value;
}

if (empty($vars['tags']) && $value !== 0 && $value !== '0') {
	return;
}

$tags = $vars['tags'];
unset($vars['tags']);

if (!is_array($tags)) {
	$tags = array($tags);
}

$list_class = "elgg-tags";
if (isset($vars['list_class'])) {
	$list_class = "$list_class {$vars['list_class']}";
	unset($vars['list_class']);
}

$item_class = "elgg-tag";
if (isset($vars['item_class'])) {
	$item_class = "$item_class {$vars['item_class']}";
	unset($vars['item_class']);
}

$icon_name = elgg_extract('icon', $vars, 'tag');
unset($vars['icon']);

$icon_class = elgg_extract('icon_class', $vars);
unset($vars['icon_class']);

if ($icon_name === false) {
	$icon = '';
} else {
	$icon = elgg_view_icon($icon_name, $icon_class);
}

$separator = elgg_extract('separator', $vars, ', ');
unset($vars['separator']);

$list_items = [];

$params = $vars;
foreach ($tags as $tag) {
	if (is_string($tag) && strlen($tag) > 0) {
		$params['value'] = $tag;
		$tag_view = elgg_view('output/tag', $params);
		$list_items[] = elgg_format_element([
			'#tag_name' => 'span',
			'#text' => $tag_view,
			'class' => $item_class,
		]);
	}
}

if (empty($list_items)) {
	return;
}

echo elgg_format_element([
	'#tag_name' => 'div',
	'#text' => $icon . implode($separator, $list_items),
	'class' => $list_class,
]);
