<?php

namespace Core;

class Repository
{
	private $repository;

	private $expiration;
	
	function __construct()
	{
		global $wpdb;
		$this->repository = $wpdb;
		$this->expiration = 60 * 60;
	}

	public function getAllPosts() {
		$table_name = $this->repository->prefix . "posts"; 
		$sql = "
				SELECT *
				FROM ${ table_name }
				WHERE post_type NOT IN ('attachment', 'revision', 'tablepress_table')
				";
		$result = $this->repository->get_results($sql);

		$result = get_transient( 'all_posts' );

		if ( empty($result) ) {
			$result = $this->repository->get_results($sql);
			$result = $this->createPermalink($result);
			set_transient( 'all_posts', $result, $this->expiration );
		}

		return $result;
	}
	public function getFeedPost() {
		$table_name = $this->repository->prefix . "posts"; 
		$sql = "
				SELECT distinct type.post_type 
				FROM ${ table_name } AS type
				WHERE type.post_type = 'feed'
				";
		$result = $this->repository->get_results($sql);

		return $result;
	}
	public function getPostTypeName() {
		$table_name = $this->repository->prefix . "posts"; 
		$sql = "
				SELECT distinct type.post_type 
				FROM ${ table_name } AS type
				WHERE type.post_type NOT IN ('attachment', 'revision', 'tablepress_table')
				";

		$result = get_transient( 'post_type2' );

		if ( empty($result) ) {
			$result = $this->repository->get_results($sql);
			foreach ($result as $key => $value) {
				$postObject = get_post_type_object( $value->post_type );
				$result[$key]->show_in_rest = $postObject->show_in_rest;
				$result[$key]->rest_base = $postObject->show_in_rest;			
			}
			$result = $this->createPermalink($result);
			set_transient( 'post_type2', $result, $this->expiration );
		}

		return $result;
	}

	public function getOnlyTypeName() {
		$Object = $this->getPostTypeName();
		$result = array_column($Object, 'post_type');

		return $result;
	}

	public function getPost($t) {
		$table_name = $this->repository->prefix . "posts"; 
		$sql = "
				SELECT *
				FROM ${ table_name } AS t
				WHERE t.post_type = %s
				";
		$query = $this->repository->prepare($sql,$t);

		$result = $this->repository->get_results($query);
		$result = $this->createPermalink($result);
		return $result;
	}		

	public function getMetaData($id) {
		$table_name = $this->repository->prefix . "postmeta"; 
		$sql = "
				SELECT meta_key, meta_value
				FROM ${ table_name } AS meta
				WHERE meta.post_id = %d
				AND meta.meta_key NOT LIKE %s ESCAPE '$'
				";
		$query = $this->repository->prepare($sql,$id,'$_%');
		$result = $this->repository->get_results($query);
		return $result;
	}

	public static function createPermalink($post) {
		foreach ($post as $key => $value) {
			$value->permalink = get_permalink( $value->ID );
		}
		return $post;	
	}

}

