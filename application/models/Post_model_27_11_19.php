<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	class Post_model extends CI_Model {

		public $table_name;
		public $post_image_location;
		public $album_image_location;
		public $post_video_location;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->table_name = 'post';
			$this->post_image_location = 'assets/member_images/post_image/';
			$this->album_image_location = 'assets/member_album_items/';
			$this->post_video_location = 'assets/member_videos/post_video/';
		}
		
		public function checkUserPostOwner($post_id){
			$user_data = $this->session->userdata();
			if($user_data['role_id']==1){
				return true;
			}else{
				return false;
			}
						
		}
		
		public function deletePostContent($id){
			$user_id = $this->session->userdata('user_id');
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$post_id=$results->report_id;
			//delete post comment
			$c_records  = $this->db->query('SELECT * FROM post_comment WHERE element_id='.$post_id)->result();
			if(count($c_records) > 0){
			foreach($c_records as $record){
			$this->db->where('id', $record->id);
			                   $this->db->delete('post_comment');
			}
			}
			//delete post view
			$v_records  = $this->db->query('SELECT * FROM post_views WHERE post_id='.$post_id)->result();
			if(count($v_records) > 0){
			foreach($v_records as $record){
			$this->db->where('id', $record->id);
			                   $this->db->delete('post_views');
			}
			}
			//delete post like
			$l_records  = $this->db->query('SELECT * FROM post_like WHERE post_id='.$post_id)->result();
			if(count($l_records) > 0){
			foreach($l_records as $record){
			$this->db->where('id', $record->id);
			                   $this->db->delete('post_like');
			}
			}
			//delete post wall share
			$w_records  = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id='.$post_id.' AND content_type=1')->result();
			if(count($w_records) > 0){
			foreach($w_records as $record){
			$this->db->where('id', $record->id);
			                   $this->db->delete('wall_shared_post');
			}
			}
			//delete post
			
			$this->db->where('id', $post_id);
           	$this->db->delete('post');

           	//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');

            //return $this->db->delete('post');
		}

		public function deletePostComment($data){
			//echo"<pre>";
			//print_r($data);die;
			$id = $data['reportedcontent_id'];
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$commentid=$results->report_id;
			$records  = $this->db->query('SELECT * FROM post_comment WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
			if(count($records) > 0){
				foreach($records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('post_comment');
				}

				//delete reported content
			$this->db->where('id', $id);
			return $this->db->delete('reported_content');
			}				
		}
		
	}

?>
