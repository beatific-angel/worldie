<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Media_model extends CI_Model {
    public $table_name;
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->table_name = 'media_channel';
    }
    /**
     * deleteVideo()
     * Delete Media Channel Video
     */
    public function deleteVideo($id = null,$reported_video=true) {
        if($reported_video){
          $results = $this->db->query('SELECT * FROM reported_content WHERE id=' . $id)->row();
          $videoid = $results->report_id;
        }
        else{
          $videoid = $id;
        }
        
        //delete video comment
        $c_records = $this->db->query('SELECT * FROM media_video_comment WHERE element_id=' . $videoid)->result();
        if (count($c_records) > 0) {
            foreach ($c_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('media_video_comment');
            }
        }
        //delete video view
        $v_records = $this->db->query('SELECT * FROM media_video_views WHERE video_id=' . $videoid)->result();
        if (count($v_records) > 0) {
            foreach ($v_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('media_video_views');
            }
        }
        //delete video like
        $l_records = $this->db->query('SELECT * FROM media_video_like WHERE video_id=' . $videoid)->result();
        if (count($l_records) > 0) {
            foreach ($l_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('media_video_like');
            }
        }
        //delete video wall share
        $w_records = $this->db->query('SELECT * FROM wall_shared_post WHERE post_id=' . $videoid . ' AND content_type=3')->result();
        if (count($w_records) > 0) {
            foreach ($w_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('wall_shared_post');
            }
        }
        //delete video from playlist share
        $p_records = $this->db->query('SELECT * FROM media_user_play_list_item WHERE video_id=' . $videoid)->result();
        if (count($p_records) > 0) {
            foreach ($p_records as $record) {
                $this->db->where('id', $record->id);
                $this->db->delete('media_user_play_list_item');
            }
        }
        //delete video
        $get_record = $this->db->query('SELECT * FROM media_video WHERE id=' . $videoid)->row();
        $this->db->where('id', $get_record->id);
        $this->db->delete('media_video');

        if($reported_video){
            //delete reported content
            $this->db->where('id', $id);
            return $this->db->delete('reported_content');
        }
        else{
        return 1;
        }
    }
    /**
     * deleteVideoComment()
     * Delete Video Comment
     */
    public function deleteVideoComment($id = null) {
        $results = $this->db->query('SELECT * FROM reported_content WHERE id=' . $id)->row();
        $commentid = $results->report_id;
        $this->db->where('id', $commentid);
        return $this->db->delete('media_video_comment');
    }
    
    #----------------------Media List Pagination------------------------------#
    public function count_media_list() {
        $query = $this->_media_list(NULL);
        return $result = $this->db->query($query)->num_rows();
    }
    /*  Show all User  */
    public function mediaList($show_list = 'show_list') {
        $query = $this->_media_list($show_list);
        return $result = $this->db->query($query)->result();
    }
    
    var $column_order = array('v.id');
    var $column_search = array('b.first_name', 'b.last_name', 'v.title');

    private function _media_list($param = NULL) {
        $sql = $sql_custom = array();
        $f_sql = $f_sql_custom = $custom_where = '';
        foreach ($this->column_search as $cmn) {
            if (isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '') {
                $sql[] = '(' . $cmn . ' LIKE ' . "'%" . $_POST["search"]["value"] . "%'" . ')';
            }
        }
        $order_by = 'v.created_at DESC';
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
            $this->db->select("v.id AS data_item_id, v.user_id AS data_userid, v.description AS data_content, v.status AS status, v.share_count AS data_sharecount,  v.sponsered AS data_sponsered, vc.name AS data_category_name, v.created_at AS data_created_at,  v.updated_at AS data_updated_at, v.image_location AS data_image, v.video_location AS data_video, v.tags AS data_tags, v.title AS data_title, v.channel_id AS data_albumid, v.category_id AS data_category, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image");
            $this->db->from('media_video v');
            $this->db->join('media_video_category vc', 'vc.id = v.category_id', 'LEFT');
            $this->db->join('users b', 'b.id = v.user_id', 'LEFT');
            $this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');
            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->limit($limit, $offset);
            $this->db->get();
            return $query1 = $this->db->last_query();
        } else {
            $this->db->select("v.id AS data_item_id, v.user_id AS data_userid, v.description AS data_content, v.status AS status, v.share_count AS data_sharecount,  v.sponsered AS data_sponsered, vc.name AS data_category_name, v.created_at AS data_created_at,  v.updated_at AS data_updated_at, v.image_location AS data_image, v.video_location AS data_video, v.tags AS data_tags, v.title AS data_title, v.channel_id AS data_albumid, v.category_id AS data_category, b.first_name AS data_f_name, b.last_name AS data_l_name, b.gender AS data_gender, c.image AS data_userimage, c.thumb_image AS data_thumb_image");
            $this->db->from('media_video v');
            $this->db->join('media_video_category vc', 'vc.id = v.category_id', 'LEFT');
            $this->db->join('users b', 'b.id = v.user_id', 'LEFT');
            $this->db->join('users_profile c', 'b.id = c.user_id', 'LEFT');
            if (!empty($custom_where)) {
                $this->db->where($custom_where);
            }
            $this->db->order_by($order_by);
            $this->db->get();
            return $query1 = $this->db->last_query();
        }
    }


    public function getSingleVideoDetails($id = null, $type = null){

            $query = $this->db->query("select v.*,vc.id AS vc_id,vc.name AS vc_name,vc.status,b.first_name AS pu_f_name, b.last_name AS pu_l_name, b.gender AS pu_gender,c.image AS pu_image,c.thumb_image AS pu_thumb_image FROM media_video as v LEFT JOIN media_video_category as vc ON v.category_id = vc.id LEFT JOIN users AS b ON b.id = v.user_id LEFT JOIN users_profile c ON b.id = c.user_id where v.id='$id'");

            $result = $query->row();
            $result->comment = $this->get_comments('media_video', $result->id);
            return $result;
        }

    public function get_comments($module = null, $id = null){
        $table = $this->getCommentTable($module);
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
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
        $this->db->select('b.comment AS comment_text, b.raw_comment AS edit_comment, b.id AS comment_id,b.comment_id AS parent_id,b.user_id AS c_user_id,c.first_name AS cu_f_name,c.last_name AS cu_l_name,c.id AS cu_userid,c.gender AS cu_gender,d.image AS cu_image,d.thumb_image AS cu_thumb_image, c.ip_address');
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

    /* ss custom methods */
    public function ssDeleteComment($data){
        $result = $this->deleteCommentCommon($data);
        return $result;
    }
    public function deleteCommentCommon($data){

        $user_id = $this->session->userdata('user_id');
        $table = $this->getCommentTable($data['type']);
        // echo "<pre>"; print_r($table);die;

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
    
    public function getCommentTable($type)
    {
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
}
?>