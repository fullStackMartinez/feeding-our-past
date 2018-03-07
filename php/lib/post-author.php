<?php
/**
 * this is a function that will add an organization name to the post they made
 *
 * @param array $posts Array of posts made on site
 *
 * @author Jolynn Pruitt
 * @author Esteban Martinez
 **/

function addAuthorToPost($pdo, $posts): array {
	$postAuthor = [];
	foreach($posts as $post) {
		$postWriter = \Edu\Cnm\FeedPast\Organization::getOrganizationByOrganizationId($pdo, $post->getPostOrganizationId());
		$postEndDateTime = round(floatval($post->getPostEndDateTime()->format("U.u")) * 1000);
		$postStartDateTime = round(floatval($post->getPostStartDateTime()->format("U.u")) * 1000);

		$tempPost = (object)[
			"postId" => $post->getPostId(),
			"postOrganizationId" => $post->getPostOrganizationId(),
			"postContent" => $post->getPostContent(),
			"postEndDateTime" => $postEndDateTime,
			"postImageUrl" => $post->getPostImageUrl(),
			"postStartDateTime" => $postStartDateTime,
			"postTitle" => $post->getPostTitle(),
			"postWriter" => $postWriter->getOrganizationName()];
		$postAuthor[] = $tempPost;
	}
	return $postAuthor;
}