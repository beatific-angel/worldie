<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
	class Store_model extends CI_Model {

		public $table_name;
		
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->table_name = 'store';
			$this->product_table_name = 'store_products';
		}

		/**
		 * deleteStoreProduct()
		 * Delete Store Product
		 */

		public function deleteStoreProduct($data){
			$user_id = $this->session->userdata('user_id');
			$id = $data['reportedcontent_id'];
			$results =  $this->db->query('SELECT * FROM reported_content WHERE id='.$id)->row();
			$report_id=$results->report_id;
			//delete product images
			$p_images = $this->db->query('SELECT * FROM store_product_images WHERE product_id='.$report_id)->result();
			if(count($p_images) > 0){
				foreach($p_images as $p_image){
					if (file_exists($p_image->image_url)){
			    		unlink($p_image->image_url); 
					}
					$this->db->where('id', $p_image->id);
                    $this->db->delete('store_product_images');
				}
			}
			//delete product
			$product  = $this->db->query('SELECT * FROM store_products WHERE id='.report_id)->row();
			
			$this->db->where('id', $product->id);
            return $this->db->delete('store_products'); 
		}
		
		
				/**
		 * deleteStore()
		 * Delete Store
		 */

		public function deleteStoreAndItsData($data){
			$storeid = $data['reportedcontent_id'];
			//delete store favourite
			$f_records  = $this->db->query('SELECT * FROM store_favourite WHERE store_id='.$storeid)->result();
			
			if(count($f_records) > 0){
				foreach($f_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('store_favourite'); 
					
				}
			}
			//delete store join
			$j_records  = $this->db->query('SELECT * FROM store_join WHERE store_id='.$storeid)->result();
			if(count($j_records) > 0){
				foreach($j_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('store_join');
				}
			}
			//delete store view
			$v_records  = $this->db->query('SELECT * FROM store_views WHERE store_id='.$storeid)->result();
			if(count($v_records) > 0){
				foreach($v_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('store_views');
				}
			}
			//delete store like
			$l_records  = $this->db->query('SELECT * FROM store_liked WHERE store_id='.$storeid)->result();
			if(count($l_records) > 0){
				foreach($l_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('store_liked');
				}
			}
			//delete store invited
			$a_records  = $this->db->query('SELECT * FROM store_invited WHERE store_id='.$storeid)->result();
			if(count($a_records) > 0){
				foreach($a_records as $record){
					$this->db->where('id', $record->id);
                    $this->db->delete('store_invited');
				}
			}

			//delete store product
			$products  = $this->db->query('SELECT * FROM store_products WHERE store_id='.$storeid)->result();
			if(count($products) > 0){
				foreach($products as $product){
					$this->deleteStoreProduct($product->id);
				}
			}
			
			//delete store
			$get_record  = $this->db->query('SELECT * FROM store WHERE id='.$storeid)->row();
			if($get_record->slider_image1 != ''){
				if (file_exists($get_record->slider_image1)){
			    	unlink($get_record->slider_image1); 
				}
			}
			if($get_record->slider_image2 != ''){
				if (file_exists($get_record->slider_image2)){
			    	unlink($get_record->slider_image2); 
				}
			}
			if($get_record->slider_image3 != ''){
				if (file_exists($get_record->slider_image3)){
			    	unlink($get_record->slider_image3); 
				}
			}
			
			$this->db->where('id', $get_record->id);
            return $this->db->delete('store');
		}

		
		


	}

?>
