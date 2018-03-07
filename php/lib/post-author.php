<?php
/**
 * this is a function that will add an organization name to the post they made
 *
 * @param array $posts Array of posts made on site
 *
 * @author Jolynn Pruitt
 * @author Esteban Martinez
 **/

function addAuthorToPost ($pdo, $posts) : array {
	$postAuthor = [];
	foreach($posts as $post) {
		$postWriter = \Edu\Cnm\FeedPast\Organization::getOrganizationByOrganizationId($pdo, $post->getPostOrganizationId());
$tempPost = (object)[
	"postId" => $post->getPostId(),
	"postOrganizationId" => $post->getPostOrganizationId(),
	"postContent" => $post->getPostContent(),
	"postEndDateTime" => $post->getPostEndDateTime(),
	"postImageUrl" =>$post->getPostImageUrl(),
	"postStartDateTime" => $post->getPostStartDateTime(),
	"postTitle" => $post->getPostTitle(),
	"postWriter" => $postWriter];
$postAuthor[] = $tempPost;
	}
	return $postAuthor;
}