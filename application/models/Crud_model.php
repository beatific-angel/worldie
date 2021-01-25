<?php
class Crud_model extends CI_Model{
    function __construct() {
        parent::__construct();
    }

    public function getRowById($where, $table){
        $this->db->select('*');
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
           return $query->row();
        } else {
            return false;
        }
    }

    public function insert($data, $table){
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $where, $table){
        $this->db->where($where);
        $result = $this->db->update($table, $data);
        return $result;
    }

    public function delete($where, $table){
        $this->db->where($where);
        $result = $this->db->delete($table);
        return $result;
    }

    public function getAllRecords($table, $where = '', $column = '*', $orderColumn = '', $order = ''){
        $this->db->select($column);
        if($where){
            $this->db->where($where);
        }
        $this->db->from($table);
        if($order && $orderColumn){
            $this->db->order_by($orderColumn, $order);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0) {
           return $query->result();
        } else {
            return false;
        }
    }

    public function getColumnValById($where, $table, $column){
        $this->db->select($column);
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
           return $query->row();
        } else {
            return false;
        }
    }

    public function getColumnValByWhereArray($where = '', $table, $column){
        $this->db->select($column);
        if($where){
            foreach($where as $key => $val){
                foreach ($val as $k => $v){
                    $this->db->where($k , $v);
                }
            }
        }
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
           return $query->row();
        } else {
            return false;
        }
    }

    public function getMax($table, $column){
        $this->db->select_max($column);
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
           return $query->row();
        } else {
            return false;
        }
    }

    public function getFromSQL($sql, $returnType = 'result'){
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            if($returnType == 'row') {
                return $query->row();
            } else {
                return $query->result();
            }
        } else {
            return false;
        }
    }

    public function get_count($table, $where){
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
    }
}