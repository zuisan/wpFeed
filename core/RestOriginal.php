<?php

namespace Core;

class RestOriginal
{

	private $repository;
	private $util;

	function __construct($r,$u)
	{
		$this->repository = $r;
		$this->util = $u;
	}

	public function init() {
		add_action('wp_ajax_like', [$this,'action']);
		add_action('wp_ajax_nopriv_like', [$this,'action']);
		add_action( 'rest_api_init', [$this,'addEndpoitsRest']);
		$this->createEndpoitsRest();
	}

	public function addEndpoitsRest() {
		$post_type = $this->repository->getOnlyTypeName();

	    register_rest_field( $post_type,
	        'originalExcerpt',
	        array(
	            'get_callback'    => [$this,'slug_get_starship'],
	            'update_callback' => null,
	            'schema'          => null,
	        )
	    );
	}

	public function slug_get_starship( $object, $field_name, $request ) {
		
		$option = $this->util->getOption();
		$likeCount =  (int)get_post_meta( $object['id'], '_like' )[0];
		if(empty($likeCount)) {
			update_post_meta($object['id'],'_like',0,true);
			$likeCount = 0;
		}

		$metas = $meta = $this->repository->getMetaData($object[ 'id' ]);

		$res = [
			'content' => mb_strimwidth( $object["content"]['raw'], 0, $option["length"], "...", "UTF-8" ),
			'action' => $likeCount,
			'meta' => $metas
		];

	    if($option['thumbnail']) { 
	    	$res['thumb'] = wp_get_attachment_image_src(get_post_thumbnail_id($object['id']), 'thumbnail')[0];
	    }

	    return $res;
	}

	public function slug_get_thumb( $object, $field_name, $request ) {

		$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($object['id']), 'thumbnail');
	    return $image_url[0];
	}

	public function get_like( $object, $field_name, $request ) {
		$likeCount =  (int)get_post_meta( $object['id'], '_like' )[0];
		if(empty($likeCount)) {
			update_post_meta($object['id'],'_like',0,true);
			$likeCount = 0;
		}
	    return $likeCount;
	}

	public function action() {
		check_ajax_referer('favarite');
		$meta_values = get_post_meta($_POST['post_id'],'_like');
		$count = (int)$meta_values[0];

		$likeCount = [
			'count' => ++$count,
			'post_id' => $_POST['post_id'],
			'post_index' => $_POST['post_index']
		];
		update_post_meta($_POST['post_id'],'_like',$likeCount['count']);

		wp_send_json($likeCount);
	}

	public function createEndpoitsRest() {
		add_action( 'rest_api_init', function () {
			register_rest_route( 'custom/v0', '/show', array(
				'methods' => 'POST',
				'callback' => [$this,'show_item']
			) );
		} );

	}

	public function show_item(){
	  $Data = $this->repository->getPost('page');

	  $response = new \WP_REST_Response($Data);
	  $response->set_status(200);
	  $domain = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];
	  $response->header( 'Location', $domain );
	  return $response;
	}

}

