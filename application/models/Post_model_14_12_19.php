<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Post_model extends CI_Model {
    public $table_name;
    public $post_image_location;
    public $album_image_location;
    public $post_video_location;

    public $column_order = array(); public $column_search = array();

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table_name = 'post';
        $this->post_image_location = 'assets/member_images/post_image/';
        $this->album_image_location = 'assets/member_album_items/';
        $this->post_video_location = 'assets/member_videos/post_video/';
        $this->load->model('common_model','common');
    }

    public function checkUserPostOwner($post_id) {
        $user_data = $this->session->userdata();
        if ($user_data['role_id'] == 1 || $user_data['role_id'] == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function deletePostContent($id, $reported_post = true) {
        $user_id = $this->session->userdata('user_id');
        if ($reported_post) {
            $results = $this->db->query('SELECT * FROM reported_content WHERE id=' . $id)->row();
            $post_id = $results->report_id;
        } else {
            $post_id = $id;
        }
        //delete post comment
        $c_records = $this->db->query('SELECT * FROM post_comment WHERE element_id=' . $post_id)->result();
        if (count($c_records) > 0) {
            foreach ($c_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('post_comment');
            }
        }
        //delete post view
        $v_records = $this->db->query('SELECT * FROM post_views WHERE post_id=' . $post_id)->result();
        if (count($v_records) > 0) {
            foreach ($v_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('post_views');
            }
        }
        //delete post like
        $l_records = $this->db->query('SELECT * FROM post_like WHERE post_id=' . $post_id)->result();
        if (count($l_records) > 0) {
            foreach ($l_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('post_like');
            }
        }
        //delete post wall share
        $w_records = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id=' . $post_id . ' AND content_type=1')->result();
        if (count($w_records) > 0) {
            foreach ($w_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('wall_shared_post');
            }
        }
        //delete post
        $this->db->where('id', $post_id);
        $this->db->delete('post');
        if ($reported_post) {
            //delete reported content
            $this->db->where('id', $id);
            return $this->db->delete('reported_content');
        } else {
            return 1;
        }
        //return $this->db->delete('post');     
    }

    public function deletePostComment($data) {
        $id = $data['reportedcontent_id'];
        $results = $this->db->query('SELECT * FROM reported_content WHERE id=' . $id)->row();
        $commentid = $results->report_id;
        $records = $this->db->query('SELECT * FROM post_comment WHERE id=' . $commentid . ' OR comment_id=' . $commentid)->result();
        if (count($records) > 0) {
            foreach ($records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('post_comment');
            }
            //delete reported content
            $this->db->where('id', $id);
            return $this->db->delete('reported_content');
        }
    }

    public function updatePostStatus($data = null) {
        $user_id = $this->session->userdata('user_id');
        $savedata = array('updated_by' => $user_id, 'updated_at' => date('Y-m-d H:i:s'), 'status' => $data['new_status']);
        $this->db->where('id', $data['id']);
        $result = $this->db->update($this->table_name, $savedata);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteGroupPostContent($id, $reported_post = true) {
        $user_id = $this->session->userdata('user_id');
        if ($reported_post) {
            $results = $this->db->query('SELECT * FROM reported_content WHERE id=' . $id)->row();
            $post_id = $results->report_id;
        } else {
            $post_id = $id;
        }
        //delete group post comment (1)
        $c_records = $this->db->query('SELECT * FROM group_post_comment WHERE post_id=' . $post_id)->result();
        if (count($c_records) > 0) {
            foreach ($c_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('group_post_comment');
            }
        }

        //delete group post view (2)
        $v_records = $this->db->query('SELECT * FROM group_post_views WHERE post_id=' . $post_id)->result();
        if (count($v_records) > 0) {
            foreach ($v_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('group_post_views');
            }
        }

        //delete group post like (3)
        $l_records = $this->db->query('SELECT * FROM group_post_like WHERE post_id=' . $post_id)->result();
        if (count($l_records) > 0) {
            foreach ($l_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('group_post_like');
            }
        }
        
        //delete group post wall share (4)
        $w_records = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id=' . $post_id . ' AND content_type=14')->result();
        if (count($w_records) > 0) {
            foreach ($w_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('wall_shared_post');
            }
        }

        //delete group post (5)
        $get_record = $this->db->query('SELECT * FROM group_posts WHERE id='.$post_id)->row();
        if(!empty($get_record)){
          #get image & delete with subcomponent(6)
          if($get_record->image_id>0){
             $this->common->delete_image_with_component($get_record->image_id,'assets/group/post_image/');
          }

          #get video & delete
          if($get_record->video_id>0){
             $get_video = $this->db->query('SELECT * FROM video WHERE id='.$get_record->video_id)->row();
             if(!empty($get_video)){
                $vid_name = 'assets/group/post_video/'.$get_image->prefix.'video_'.$get_video->id.'.'.strtolower($get_video->type);

                if (file_exists($this->config->item('parent_folder_name').$vid_name)){
                    unlink($this->config->item('parent_folder_name').$vid_name);
                }

                $this->db->where('id', $get_video->id);
                $this->db->delete('video');
             }
          }

          #get album & delete
        if($get_record->album_id>0){
             $get_album = $this->db->query('SELECT * FROM group_albums WHERE id='.$get_record->album_id)->row();
             if(!empty($get_album)){

                $this->db->where('id', $get_album->id);
                $this->db->delete('group_albums');

                #delete album itmes
                $get_album_itmes = $this->db->query('SELECT * FROM group_album_items WHERE group_id='.$get_album->id)->result();
                if(count($get_album_itmes)>0){
                    foreach ($get_album_itmes as $record) {
                       $this->db->where('id', $record->id);
                       $this->db->delete('group_album_items');

                       #delete image or video
                       $item_id = $record->item_id;
                       if($record->item_type=='2'){//video type
                         $get_video = $this->db->query('SELECT * FROM video WHERE id='.$item_id)->row();
                         if(!empty($get_video)){
                            $vid_name = $record->image_name;

                            if (file_exists($this->config->item('parent_folder_name').$vid_name)){
                                unlink($this->config->item('parent_folder_name').$vid_name);
                            }

                            $this->db->where('id', $item_id);
                            $this->db->delete('video');
                         }
                       }
                       else{
                         #delete image & other subcomplnent(likes,views,comments)
                         $this->common->delete_image_with_component($item_id,$record->image_name,true);
                       }
                    }
                }
             }
        }

        $this->db->where('id', $post_id);
        $this->db->delete('group_posts');   
    }

        if ($reported_post) {
            //delete reported content
            $this->db->where('id', $id);
            return $this->db->delete('reported_content');
        } else {
            return 1;
        } 
}

    #----------------------Post List Pagination------------------------------#
    public function count_post_list() {
        $query = $this->_post_list(NULL);
        return $result = $this->db->query($query)->num_rows();
    }
    /*  Show all User  */
    public function postList($show_list = 'show_list') {
        $query = $this->_post_list($show_list);
        return $result = $this->db->query($query)->result();
    }
    // var $column_order = array('a.id');
    // var $column_search = array('b.first_name', 'b.last_name', 'a.raw_content');
    private function _post_list($param = NULL) {
        $sql = $sql_custom = array();
        $f_sql = $f_sql_custom = $custom_where = '';
        foreach ($this->column_search as $cmn) {
            if (isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '') {
                $sql[] = '(' . $cmn . ' LIKE ' . "'%" . $_POST["search"]["value"] . "%'" . ')';
            }
        }
        $order_by = 'a.created_at DESC';
        if (isset($_POST['order'])) {
            $order_by = $this->column_order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
        }
        if (sizeof($sql) > 0) {
            $f_sql = '(' . implode(' OR ', $sql) . ')';
        }
        if (sizeof($sql_custom) > 0) {
            $f_sql_custom = implode(' AND ', $sql_custom);
        }
        if (!empty($f_sql) && !empty($f_sql_custom)) {
            $custom_where = $f_sql . ' AND ' . $f_sql_custom;
        } else if (!empty($f_sql)) {
            $custom_where = $f_sql;
        } else if (!empty($f_sql_custom)) {
            $custom_where = $f_sql_custom;
        }
        if ($param == 'show_list' && isset($_POST["length"]) && $_POST["length"] != - 1) {
            $limit = $_POST['length'];
            $offset = $_POST['start'];
            $this->db->select("a.id AS data_item_id, a.user_id AS data_userid, a.content AS data_content, a.show_status AS data_privacy, a.share_count AS data_sharecount, a.sponsered AS data_sponsered, a.created_at AS data_created_at, a.updated_at AS data_updated_at, a.image_id AS data_image, a.video_id AS data_video, a.post_type AS data_item_type, a.album_id AS data_albumid, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image,a.status as status");
            $this->db->from("post a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->limit($limit, $offset);
            $this->db->get();
            return $query1 = $this->db->last_query();
            //return $results = "SELECT * FROM ($query1) AS unionTable ORDER BY $order_by LIMIT $limit OFFSET $offset";
            
        } else {
            $this->db->select("a.id AS data_item_id, a.user_id AS data_userid, a.content AS data_content, a.show_status AS data_privacy, a.share_count AS data_sharecount, a.sponsered AS data_sponsered, a.created_at AS data_created_at, a.updated_at AS data_updated_at, a.image_id AS data_image, a.video_id AS data_video, a.post_type AS data_item_type, a.album_id AS data_albumid, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image,a.status as status");
            $this->db->from("post a");
            $this->db->join('users b', 'b.id = a.user_id');
            $this->db->join('users_profile c', 'b.id = c.user_id');
            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->get();
            return $query1 = $this->db->last_query();
        }
    }

    #-----------Group Post List Pagination-------------#
    public function count_group_post_list() {
        $query = $this->_group_post_list(NULL);
        return $result = $this->db->query($query)->num_rows();
    }

    public function groupPostList($show_list = 'show_list') {
        $query = $this->_group_post_list($show_list);
        return $result = $this->db->query($query)->result();
    }

    // var $column_order = array('gp.id');
    // var $column_search = array('b.first_name', 'b.last_name', 'gp.content');

    private function _group_post_list($param = NULL) {
        $sql = $sql_custom = array();
        $f_sql = $f_sql_custom = $custom_where = '';
        foreach ($this->column_search as $cmn) {
            if (isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '') {
                $sql[] = '(' . $cmn . ' LIKE ' . "'%" . $_POST["search"]["value"] . "%'" . ')';
            }
        }
        $order_by = 'gp.created_at DESC';
        if (isset($_POST['order'])) {
            $order_by = $this->column_order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
        }
        if (sizeof($sql) > 0) {
            $f_sql = '(' . implode(' OR ', $sql) . ')';
        }
        if (sizeof($sql_custom) > 0) {
            $f_sql_custom = implode(' AND ', $sql_custom);
        }
        if (!empty($f_sql) && !empty($f_sql_custom)) {
            $custom_where = $f_sql . ' AND ' . $f_sql_custom;
        } else if (!empty($f_sql)) {
            $custom_where = $f_sql;
        } else if (!empty($f_sql_custom)) {
            $custom_where = $f_sql_custom;
        }
        if ($param == 'show_list' && isset($_POST["length"]) && $_POST["length"] != - 1) {
            $limit = $_POST['length'];
            $offset = $_POST['start'];

            $this->db->select("gp.id AS data_item_id, gp.user_id AS data_userid, gp.content AS data_content, gp.show_status AS data_privacy,gp.status AS status, gp.share_count AS data_sharecount, gp.sponsered AS data_sponsered, gp.created_at AS data_created_at, gp.updated_at AS data_updated_at, gp.image_id AS data_image, gp.video_id AS data_video, g.title AS data_group_title, g.name AS data_group_name, gp.post_type AS data_category, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image");

            $this->db->from('group_posts gp');
			$this->db->join('groups g', 'g.id = gp.user_id_written_on', 'LEFT');
			$this->db->join('users b', 'b.id = gp.user_id', 'LEFT');
			$this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');

            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->limit($limit, $offset);
            $this->db->get();
            return $query1 = $this->db->last_query();            
        } else {
            $this->db->select("gp.id AS data_item_id, gp.user_id AS data_userid, gp.content AS data_content, gp.show_status AS data_privacy,gp.status AS status, gp.share_count AS data_sharecount, gp.sponsered AS data_sponsered, gp.created_at AS data_created_at, gp.updated_at AS data_updated_at, gp.image_id AS data_image, gp.video_id AS data_video, g.title AS data_group_title, g.name AS data_group_name, gp.post_type AS data_category, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image");

            $this->db->from('group_posts gp');
			$this->db->join('groups g', 'g.id = gp.user_id_written_on', 'LEFT');
			$this->db->join('users b', 'b.id = gp.user_id', 'LEFT');
			$this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');
            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->get();
            return $query1 = $this->db->last_query();
        }
    }

    /* ss custom methods */
    public function ssDeleteComment($data){
        $result = $this->deleteCommentCommon($data);
        return $result;
    }
    public function deleteCommentCommon($data){

        $user_id = $this->session->userdata('user_id');
        $table = $this->getCommentTable($data['type']);
        $commentid = $data['id'];
        $records   = $this->db->query('SELECT * FROM '.$table.' WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
        if(count($records) > 0){
            foreach($records as $record){
                $this->db->where('id', $record->id);
                $this->db->delete($table);
            }
            return 'deleted';
        }else{
            return 'norecord';
        }
    }

    public function getCommentTable($type){
        if (filter_var($type, FILTER_VALIDATE_INT)) {
            $module = $this->getModuleOfComment($type);
        }else{
            $module = $type;
        }
        
        $table = '';
        switch ($module) {
            case 'events':
                $table = 'event_comment';
                break;
            case 'posts':
                $table = 'post_comment';
                break;
            case 'post':
                $table = 'post_comment';
                break;
            case 'wall_arts':
                $table = 'wall_arts_comment';
                break;
            case 'store_products':
                $table = 'store_product_comment';
                break;
            case 'media_video':
                $table = 'media_video_comment';
                break;    
        }

        return $table;
    }
    
    public function getModuleOfComment($type){
        $this->config->load('user_permission', TRUE);
        $c_type  = $this->config->item('ss_content_type', 'user_permission');
        if(count($c_type) > 0){
            foreach($c_type as $key => $value){
                if($type == $value){
                    return $key;
                }
            }
        }
    }

    public function getPostById($postid){

        $tablename = 'post';
        $user_id = $this->session->userdata('user_id');
        $this->db->select('a.*, b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image');
        $this->db->from("post a");
        $this->db->join('users b', 'b.id = a.user_id');
        $this->db->join('users_profile c', 'b.id = c.user_id');
        $this->db->where('a.status', 1);
        $this->db->where('a.id', $postid);
        $query = $this->db->get();        
        $results = $query->result();
        foreach($results as $result){ 



                //get comment count
            $comment_count_query = $this->db->query('SELECT * FROM post_comment WHERE element_id='.$result->id);
            $result->total_comment_count = $comment_count_query->num_rows();
                //$result->comment = $cmnt_results;
                //get post comments 
            $result->comment = $this->get_comments('posts', $result->id);
                //get image url if imageexist for the post
            if($result->image_id != 0){
                $this->db->select('*');
                $this->db->from("image");
                $this->db->where('id =', $result->image_id);
                $image_query = $this->db->get();        
                $image_result = $image_query->result();
                $file_ext = mb_strtolower($image_result[0]->type);
                $result->image_url =  base_url().$this->post_image_location.$image_result[0]->prefix.'image_'.$result->image_id.".".$file_ext;
            }else{
                $result->image_url = '';
            }

            if($result->video_id != 0){
                $video = $this->db->query('SELECT * FROM video WHERE id='.$result->video_id)->row();
                $file_ext = mb_strtolower($video->type);
                $result->video_ext = $file_ext;
                $result->video_url =  base_url().$this->post_video_location.$video->prefix.'video_'.$result->video_id.".".$file_ext;
            }else{
                $result->video_url = '';
            }
            if($result->post_type == 4){
                $album_id = $result->album_id;
                $user_album_query = $this->db->query('SELECT * FROM user_album_items WHERE album_id='.$album_id.' ORDER BY id DESC');
                $album_items = $user_album_query->result();
                $result->album_item_count = count($album_items);
                if($result->album_item_count > 0){
                    foreach($album_items as $album_item){
                        $album_item->image_url = base_url().$album_item->image_name;
                            //$album_item->image_url = base_url().$this->album_image_location.$album_item->user_id.'/'.$album_item->image_name;
                    }
                }
                $result->album_items = $album_items;
            }
        }
        return $results;
    }


    public function get_comments($module = null, $id = null){
        $table = $this->getCommentTable($module);
        // echo 'table '.$table;die;
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
        $this->db->from("$table b");
        $this->db->join('users c', 'b.user_id = c.id', 'left');
        $this->db->join('users_profile d','b.user_id = d.user_id','left');
        $cmnt_cond = array('b.element_id =' => $id, 'comment_id =' => 0);
        $this->db->where($cmnt_cond);
        $this->db->order_by('b.created_at','DESC');
        $cmnt_query = $this->db->get();
        $comment = $cmnt_query->result();
        if(count($comment) > 0){
            $i=0;
            foreach($comment as $cmnt){
                $comment[$i]->reply_count = $this->getModuleCommentReplyCount($table, $cmnt->comment_id);
                $comment[$i]->reply = $this->comment_replies($table, $cmnt->comment_id);
                $i++;
            }
            return $comment;
        }else{
            return (object) array();
        }
    }

    /* Function Name: getModuleCommentReplyCount() */
    public function getModuleCommentReplyCount($table, $comment_id){
        $query   = $this->db->query('SELECT * FROM '.$table.' WHERE comment_id= '.$comment_id);
        $results = $query->result();
        if(count($results) > 0){
            $reply_count = $query->num_rows();
            foreach($results as $result){
                $reply_count += $this->getModuleCommentReplyCount($table, $result->id);
            }
            return $reply_count;
        }else{
            return 0;
        }
    }

    public function comment_replies($table = null, $id = null){
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
        $this->db->from("$table b");
        $this->db->join('users c', 'b.user_id = c.id', 'left');
        $this->db->join('users_profile d', 'c.id = d.user_id', 'left');
        $this->db->where('b.comment_id =', $id);
        $this->db->order_by("b.created_at", "DESC");
        $reply_query = $this->db->get(); 
        $this->db->last_query();
        $comment_reply = $reply_query->result();
        if(count($comment_reply) > 0){
            $i=0;
            foreach($comment_reply as $reply){
                $comment_reply[$i]->reply = $this->comment_replies($table, $reply->comment_id);
                $i++;
            }
        }
        return $comment_reply;       
    }

    public function getGroupPostCommentById($groupPostId){
        //get post comments 
        $this->db->select('b.comment AS comment_text,b.id AS main_comment_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
        $this->db->from("group_post_comment b");
        $this->db->join('users c', 'b.user_id = c.id', 'left');
        $this->db->join('users_profile d', 'c.id = d.user_id', 'left');
        $cmnt_cond = array('b.post_id =' => $groupPostId, 'comment_id =' => 0);
        $this->db->where($cmnt_cond);
        $this->db->order_by("b.created_at", "DESC");
        $cmnt_query = $this->db->get();        
        $cmnt_results = $cmnt_query->result();

        // $result->comment = $cmnt_results;
        foreach($cmnt_results as  $cmnt_result){
                    //get reply of comments we are doing it for 2 level
            $this->db->select('b.comment AS comment_text,b.id AS main_comment_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image');
            $this->db->from("group_post_comment b");
            $this->db->join('users c', 'b.user_id = c.id', 'left');
            $this->db->join('users_profile d', 'c.id = d.user_id', 'left');
            $this->db->where('b.comment_id =', $cmnt_result->main_comment_id);
            $this->db->order_by("b.created_at", "DESC");
            $reply_query = $this->db->get();        
            $reply_result = $reply_query->result();
            $cmnt_result->comment_reply = $reply_result;
        }
        return $cmnt_results;
    }


    public function ssGroupPostComment_delete($data){
        $user_id = $this->session->userdata('user_id');
        $table = 'group_post_comment';
        $commentid = $data['comment_id'];
        $records   = $this->db->query('SELECT * FROM '.$table.' WHERE id='.$commentid.' OR comment_id='.$commentid)->result();
        if(count($records) > 0){
            foreach($records as $record){
                $this->db->where('id', $record->id);
                $this->db->delete($table);
            }
            return 'deleted';
        }else{
            return 'norecord';
        }
    }
}
?>

