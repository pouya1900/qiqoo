<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray( $request ) {
		return [
			'id'                    => $this->id,
			'title'                 => $this->title,
			'contentImages'         => $this->contentImages,
			'logo'                  => $this->logo,
			'video'         => $this->contentVideo,
			'isVideo'              => intval($this->is_video),
			'publishedCommentCount' => $this->publishedCommentCount ?? 0,
			'shareLink'             => $this->getShareLink(),
			'category'              => [
				'id'    => $this->category->id,
				'title' => $this->category->title,
			],
			'comments'              => CommentResource::collection( $this->publishedComments ),
			'shortDescription'      => $this->short_description ?? '',
			'text'                  => $this->text ?? '',
			'enText'                => $this->en_text ?? '',
			'sliderBlog'            => $this->slider_blog ?? 0,
			'createdAt'             => $this->created_at,
			'publishedAt'           => $this->published_at
		];

	}
}
