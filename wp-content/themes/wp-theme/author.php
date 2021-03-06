<?php

if(!isset($_GET['preview'])){
	$user_id = get_query_var('author');

	// temporarily redirect to their configged URL
	$url = get_the_author_meta( 'user_url', $user_id );

	if(empty($url))
		$url = 'https://www.google.com/#q=' . get_the_author_meta( 'user_firstname', $user_id ) . ' ' . get_the_author_meta( 'user_lastname', $user_id );

	header('Location: ' . $url,true,301);
	exit;
}


// get user info
$user_id = get_query_var('author');
$user = get_user_by('id', $user_id);
$user_data = $user->data;

// load meta
$meta = get_user_meta($user_id);

// convert single-item array values to their first element
foreach($meta as $k=>$v){
	if(is_array($v) && count($v) == 1)
		$meta[$k] = $v[0];
}

// add email to $meta
$meta['email'] = $user->data->user_email;

// image
$user_image = get_wp_user_avatar_src( $user_id, 'large', null, $user_data->display_name );

// description
$user_desc = !empty($meta['about_you']) ? $meta['about_you'] : $meta['description'];
$user_desc = wpautop($user_desc);

// links
$contact_links = get_contact_links_arr($user_id, true, $meta);
$social_links = get_social_links_arr($user_id, true, $meta);

// media
$media = get_gallery_arr($user_id, true);


// build the $profile var for the partial
$profile = array(
	"title" => $user_data->display_name, //required
	"subhead" => get_user_business($user),
	"cover_img" => $meta['cover_img'],
	"featured_img" => $user_image,
	"description" => $user_desc,
	"contact_links" => $contact_links,
	"social_links" => $social_links,
	"media" => $media
);

require('partials/profile.php');
