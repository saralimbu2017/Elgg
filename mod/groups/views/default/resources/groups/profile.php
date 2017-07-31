<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

elgg_push_context('group_profile');

elgg_push_breadcrumb($group->name);

groups_register_profile_buttons($group);

$content = elgg_view('groups/profile/layout', ['entity' => $group]);
$sidebar = '';

if (elgg_group_gatekeeper(false)) {
	if (elgg_is_active_plugin('search')) {
		$sidebar .= elgg_view('groups/sidebar/search', ['entity' => $group]);
	}
	$sidebar .= elgg_view('groups/sidebar/members', ['entity' => $group]);

	$sidebar .= elgg_view('groups/sidebar/my_status', [
		'entity' => $group,
	]);
}

$params = [
	'content' => $content,
	'sidebar' => $sidebar,
	'title' => $group->name,
];
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($group->name, $body);
