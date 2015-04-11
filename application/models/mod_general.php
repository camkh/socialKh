<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mod_general extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('dbtable');
    }

    function count($tableName,$where = array())
    {
        $this->load->database('default', true);
        if(!empty($where)) {
            $this->db->like($where);
        }
        $count = $this->db->count_all_results($tableName);
        return $count;
    }

    public function select($table, $field = '*', $where = array(), $order = 0, $group =
        0, $limit = 0, $offset = 0)
    {
        $this->load->database('default', true);
        $this->db->select($field);
        $this->db->from($table);
        if (!empty($where)) {
            if (!empty($where['where_in'])) {
                foreach ($where['where_in'] as $key_w => $value_w) {
                    $this->db->where_in($key_w, $value_w);
                }
            } else
                if (!empty($where['where_not_in'])) {
                    foreach ($where['where_not_in'] as $key_w => $value_w) {
                        $this->db->where_not_in($key_w, $value_w);
                    }
                } else {
                    $this->db->where($where);
                }
        }
        if (!empty($order)) {
            $this->db->order_by($order);
        }
        if (!empty($group)) {
            $this->db->group_by($group);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function like($table, $field = '*', $search = array(), $where = array())
    {
        $this->db->select($field);
        $this->db->from($table);
        $this->db->like($search);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function insert($table, $data = array())
    {
        $this->load->database('default', true);
        $this->db->simple_query('SET NAMES \'utf-8\'');
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function update($table, $data = array(), $where = array())
    {
        $this->load->database('default', true);
        $this->db->where($where);
        $this->db->simple_query('SET NAMES \'utf-8\'');
        $ok = $this->db->update($table, $data);
        return (($ok) ? true : false);
    }

    function delete($table, $where = array())
    {
        $this->load->database('default', true);
        $ok = $this->db->delete($table, $where);
        return (($ok) ? true : false);
    }

    public function record_count($table, $where = '')
    {
        if (!empty($where)) {

        } else {
            return $this->db->count_all($table);
        }
    }

    /**
     * 
     * @param undefined $table
     * @param undefined $tablejoin
     * @param undefined $fields
     * @param undefined $where
     * @param undefined $order
     * @param undefined $group
     * @param undefined $limit
     * 
     */
    function join($table, $tablejoin, $fields = '*', $where = null, $order =0, $group=0, $limit = 0, $offset = 0)
    {
        $this->db->select($fields);
        $this->db->from($table);
        if (is_array($tablejoin)) {
            foreach ($tablejoin as $value) {
                //$this->db->join('comments', 'comments.id = blogs.id');
                $this->db->join($value['table'], $value['field1'] . '=' . $value['field2']);
            }
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($order)) {
            $this->db->order_by($order);
        }
        if (!empty($group)) {
            $this->db->group_by($group);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result();
    }
    function jointable($table, $tablejoin, $fields = '*', $where = null, $order =0, $group=0, $limit = 0, $offset = 0)
    {
        $this->db->select($fields);
        $this->db->from($table);
        if (is_array($tablejoin)) {
            foreach ($tablejoin as $value) {
                //$this->db->join('comments', 'comments.id = blogs.id');
                $this->db->join($value['table'], $value['field1'] . '=' . $value['field2']);
            }
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($order)) {
            $this->db->order_by($order);
        }
        if (!empty($group)) {
            $this->db->group_by($group);
        }
        if (!empty($limit)) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result();
    }
    function checkUser()
    {
        $action = $this->uri->segment(1);
        $id = $this->uri->segment(2);
        if (!empty($id)) {
            $id = '/' . $id;
        } else {
            $id = '';
        }
        $page = $action . $id;
        $user = $this->session->userdata('email');
        $log_id = $this->session->userdata('user_id');

        $user_type = $this->session->userdata('user_type');
        if ($user) {
            if ($user_type != 1) {
                if (!empty($dataPagePer)) {

                } else {
                    //redirect(base_url() . 'home');
                }
            }
        } else {
            redirect(base_url() . 'hauth');
        }
    }

    function getMenuUser()
    {
        $action = $this->uri->segment(1);
        $id = $this->uri->segment(2);
        if (!empty($id)) {
            $id = '/' . $id;
        } else {
            $id = '';
        }
        $page = $action . $id;
        $user = $this->session->userdata('username');
        $log_id = $this->session->userdata('log_id');
        $user_type = $this->session->userdata('user_type');
        $data_field = array('per_value', );
        $data_sel = array(
            'per_user_id' => $log_id,
            'per_status' => 1,
            );
        $dataPagePer = $this->Mod_general->select('permission', $data_field, $data_sel);
        $data = array();
        $user_type = $this->session->userdata('user_type');
        if ($user_type != 1 && !empty($dataPagePer)) {
            $dataPage = array();
            $i = 0;
            foreach ($dataPagePer as $value) {
                $where_sel = array(
                    Tbl_title::type => 'nav_menu_item',
                    Tbl_title::status => 1,
                    Tbl_title::id => $value->per_value,
                    );
                $dataPage = $this->select(Tbl_title::tblname, '*', $where_sel);
                foreach ($dataPage as $value_a) {
                    $data[$i][Tbl_title::value] = $value_a->{Tbl_title::value};
                    $data[$i][Tbl_title::title] = $value_a->{Tbl_title::title};
                    $i++;
                }
            }
        }
        return $data;
    }

    public function getuser($field = '*', $where = '')
    {
        if (!empty($field)) {
            $this->select($field);
        } else {
            $this->select('username');
        }
        $this->from('login');
        $this->join('users', 'login.id = users.log_id');
        if (!empty($where)) {
            $this->where($where);
        }

        $query = $this->get();
        return $query->result();
    }

    function labelAlert($class, $text)
    {
        if (!empty($class) && !empty($text)) {
            echo '<span class="label label-' . $class . '">' . $text . '</span>';
        }
    }

    function build_tree_edit($parent_id = 0, $level = 0)
    {
        $has_childs = false;
        $result = mysql_query("SELECT * FROM (`title`) JOIN `cat_term_relationships` ON title.`id` = cat_term_relationships.`object_id` WHERE title.`mo_parent`='" .
            $parent_id . "' and title.`mo_type`='nav_menu_item' group by title.id");
        //display each child row
        $i = 0;
        $menu = '';
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                if ($has_childs === false) {
                    $has_childs = true;
                    $menu .= '<ol class="dd-list">';
                }
                $menu .= "<li class='dd-item' data-id='" . $row['id'] .
                    "'><div class='dd-handle'>" . $row['mo_title'] .
                    '<span class="pull-right" style="margin-right:35px;">' . $row[Tbl_title::value] .
                    "</span></div>";
                $menu .= $this->build_tree_edit($row['id'], $level + 1);
                $menu .= "<a class='btn btn-sm pull-right removelist' data='" . $row['id'] .
                    "'><i class='icol-cross'></i></a></li>";
            }
            if ($has_childs === true)
                $menu .= '</ol>';
            return $menu;
        }
    }

    function getvdo($continue, $vdo_title_d)
    {
        $this->load->library('html_dom');
        $log_id = $this->session->userdata('log_id');
        $data_post_sel = array(Tbl_meta::id => $continue, );
        $order = Tbl_meta::id . ' DESC';
        $get_data_link = $this->select(Tbl_meta::tblname, '', $data_post_sel, $order, '',
            1);
        try {
            foreach ($get_data_link as $value_link) {
                $getlink = $value_link->{Tbl_meta::value};
                $getID = $value_link->{Tbl_meta::id};
                $html1 = file_get_html($getlink);
                $i = 0;
                foreach ($html1->find('.blog-content .play-inner') as $article) {
                    $i++;
                    if (preg_match('/dailymotion.com/', $article)) {
                        $files = trim($article->find('#Playerholder iframe', 0)->src);
                        preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $files, $matches);
                        if (!empty($matches[1])) {
                            $dailyM = $matches[1];
                        } else {
                            $dailyM = $files;
                        }
                        $title = trim(@$article->find('.blog .blog-title h2 span', 0)->innertext);
                        $dataLogin = $this->add_meta($dailyM, '', $title, 'dailymotion', $vdo_title_d);
                    } else
                        if (preg_match('/docs.google/', $article)) {
                            $files = trim($article->find('#Playerholder iframe', 0)->src);
                            $g_array = explode('/', $files);
                            if (!empty($g_array[5])) {
                                $Gid = $g_array[5];
                            } else {
                                $Gid = $files;
                            }
                            $title = trim($html1->find('.blog .blog-title span', 0)->innertext);
                            $dataLogin = $this->add_meta($Gid, '', $title, 'docs.google', $vdo_title_d);
                        } else
                            if (preg_match('/vimeo.com/', $article)) {
                                $files = trim($article->find('#Playerholder iframe', 0)->src);
                                preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/",
                                    $files, $matches);
                                if (!empty($matches[5])) {
                                    $files = $matches[5];
                                } else {
                                    $files = $files;
                                }
                                //$files = str_replace("?start=1", "?start=0", $code);
                                $title = trim(@$article->find('.blog .blog-title h2 span', 0)->innertext);
                                $dataLogin = $this->add_meta($files, '', $title, 'vimeo', $vdo_title_d);
                            } else
                                if (preg_match('/youtube.com/', $article)) {
                                    $code = trim($article->find('script', 1)->innertext);
                                    preg_match("/v=([^&]+)/i", $code, $code_link);
                                    $title = trim(@$article->find('.blog .blog-title h2 span', 0)->innertext);
                                    $files = substr($code_link[1], 0, 11);
                                    $dataLogin = $this->add_meta($files, '', $title, 'yt', $vdo_title_d);
                                } else
                                    if (preg_match('/vid.me/', $article)) {
                                        $files = trim($article->find('#Playerholder iframe', 0)->src);
                                        $title = trim(@$article->find('.blog .blog-title h2 span', 0)->innertext);
                                        $dataLogin = $this->add_meta($files, '', $title, 'iframe', $vdo_title_d);
                                    }
                }
            }
            $delete = $this->delete(Tbl_meta::tblname, array(Tbl_meta::id => $getID));
            if ($delete) {
                $data_post_sel = array(
                    Tbl_meta::type => 'not_in_use',
                    Tbl_meta::user_id => $log_id,
                    );
                $get_data_next = $this->select(Tbl_meta::tblname, '', $data_post_sel, $order, '',
                    1);
                if (!empty($get_data_next)) {
                    foreach ($get_data_next as $value_next) {
                        return $value_next->{Tbl_meta::id};
                    }
                } else {
                    return false;
                }
            }
        }
        catch (exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function getkhdrama($continue, $vdo_title_d, $update)
    {
        $this->load->library('html_dom');
        $log_id = $this->session->userdata('log_id');
        $data_post_sel = array(Tbl_meta::id => $continue, );
        $order = Tbl_meta::id . ' DESC';
        $get_data_link = $this->select(Tbl_meta::tblname, '', $data_post_sel, $order, '',
            1);
        try {
            foreach ($get_data_link as $value_link) {
                $getlink = $value_link->{Tbl_meta::value};
                $getID = $value_link->{Tbl_meta::id};
                $html1 = file_get_html($getlink);
                $code_id = @$html1->find('.content .main center iframe', 0)->src;
                if (!empty($code_id)) {
                    $title = trim(@$html1->find('.content .main h3', 0)->innertext);
                    if (preg_match('/dailymotion.com/', $code_id)) {
                        preg_match('#http://www.dailymotion.com/embed/video/([A-Za-z0-9]+)#s', $code_id,
                            $matches);
                        if (!empty($matches[1])) {
                            $dailyM = $matches[1];
                        } else {
                            $dailyM = $files;
                        }
                        $dataLogin = $this->add_meta($dailyM, '', $title, 'dailymotion', $vdo_title_d);
                    } else
                        if (preg_match('/docs.google/', $code_id)) {
                            $g_array = explode('/', $code_id);
                            if (!empty($g_array[5])) {
                                $Gid = $g_array[5];
                            } else {
                                $Gid = $files;
                            }
                            $dataLogin = $this->add_meta($Gid, '', $title, 'docs.google', $vdo_title_d);
                        } else
                            if (preg_match('/vimeo.com/', $code_id)) {
                                preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/",
                                    $code_id, $matches);
                                if (!empty($matches[5])) {
                                    $files = $matches[5];
                                } else {
                                    $files = $files;
                                }
                                $dataLogin = $this->add_meta($files, '', $title, 'vimeo', $vdo_title_d);
                            } else
                                if (preg_match('/youtube.com/', $code_id)) {
                                    preg_match("/v=([^&]+)/i", $code_id, $code_link);
                                    $files = substr($code_link[1], 0, 11);
                                    $dataLogin = $this->add_meta($files, '', $title, 'yt', $vdo_title_d);
                                } else {
                                    $dataLogin = $this->add_meta($code_id, '', $title, 'iframe', $vdo_title_d);
                                }
                                if ($update == 1) {
                                    $title_arr = explode(' - part', $title);
                                    if (!empty($title_arr[0])) {
                                        $titl = $title_arr[0];
                                    } else {
                                        $titl = $title;
                                    }
                                    $where_title = array(
                                        Tbl_title::type => 'vdolist',
                                        Tbl_title::id => $vdo_title_d,
                                        );
                                    $data_title = array(Tbl_title::title => trim($titl), );
                                    $query_blog = $this->update(Tbl_title::tblname, $data_title, $where_title);
                                }
                }
            }
            $delete = $this->delete(Tbl_meta::tblname, array(Tbl_meta::id => $getID));
            if ($delete) {
                $data_post_sel = array(
                    Tbl_meta::type => 'not_in_use',
                    Tbl_meta::user_id => $log_id,
                    );
                $get_data_next = $this->select(Tbl_meta::tblname, '', $data_post_sel, $order, '',
                    1);
                if (!empty($get_data_next)) {
                    foreach ($get_data_next as $value_next) {
                        return $value_next->{Tbl_meta::id};
                    }
                } else {
                    return false;
                }
            }
        }
        catch (exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    function getuserPage($parent_id = 0, $level = 0)
    {
        $has_childs = false;
        $result = mysql_query("SELECT * FROM (`title`) JOIN `cat_term_relationships` ON title.`id` = cat_term_relationships.`object_id` WHERE title.`mo_type`='nav_menu_item' group by title.id");
        //display each child row
        $i = 0;
        $menu = '';
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                if ($has_childs === false) {
                    $has_childs = true;
                    $menu .= '<ul class="">';
                }
                $menu .= "<li class='listpage' data-id='" . $row['id'] .
                    "'><input name='pageid[]' class='checkforuser' type='checkbox' value='" . $row['mo_value'] .
                    "'/> " . str_repeat('--', $level) . $row['mo_title'];
                $menu .= $this->getuserPage($row['id'], $level + 1);
                $menu .= "</li>";
            }
            if ($has_childs === true) {
                $menu .= '</ul>';
            }
            return $menu;
        }
    }

    function generate_menu($parent)
    {
        $has_childs = false;
        global $menu_array;
        foreach ($menu_array as $key => $value) {
            if ($value['parent'] == $parent) {
                if ($has_childs === false) {
                    $has_childs = true;
                    echo '<ul>';
                }
                echo '<li><a href="#">' . $value['name'] . '</a>';
                generate_menu($key);
                echo '</li>';
            }
        }
        if ($has_childs === true)
            echo '</ul>';
    }

    function add_meta($value, $key, $title, $type = 'yt', $continue = '')
    {
        $log_id = $this->session->userdata('log_id');
        $vdo_type = 'vdolist';
        $data_sub = array(
            Tbl_meta::object_id => $continue,
            Tbl_meta::type => $vdo_type,
            Tbl_meta::key => trim($title) . ' - Part ' . $key,
            Tbl_meta::value => $value,
            Tbl_meta::name => $type,
            Tbl_meta::user_id => $log_id,
            );
        $dataLogin = $this->insert(Tbl_meta::tblname, $data_sub);
    }

    /* menu user */

    function menuuser($param)
    {
        $user = $this->session->userdata('username');
        $log_id = $this->session->userdata('log_id');
        $user_type = $this->session->userdata('user_type');
        $data_sel = array(
            'per_user_id' => $log_id,
            'per_status' => 1,
            'per_value' => $page,
            );
        $dataPage = $this->select('permission', '*', $data_sel);

        $this->db->select('*');
        $this->db->from('permission');
        $this->db->join(Tbl_title::tblname, 'title.id = blogs.id');
    }

    /* end menu user */

    /* url convert */

    function flash_encode($string)
    {
        $string = rawurlencode(utf8_encode($string));
        $string = str_replace("%C2%96", "-", $string);
        $string = str_replace("%C2%91", "%27", $string);
        $string = str_replace("%C2%92", "%27", $string);
        $string = str_replace("%C2%82", "%27", $string);
        $string = str_replace("%C2%93", "%22", $string);
        $string = str_replace("%C2%94", "%22", $string);
        $string = str_replace("%C2%84", "%22", $string);
        $string = str_replace("%C2%8B", "%C2%AB", $string);
        $string = str_replace("%C2%9B", "%C2%BB", $string);

        return $string;
    }

    /* end url convert */

    function blogcheck($current, $backto)
    {
        $Current_url = explode('?', $current);
        $Current_url = !empty($Current_url[1]) ? @$Current_url[1] : @$Current_url[0];
        $blogpassword = $this->session->userdata('user_id');
        if (empty($blogpassword)) {
            redirect($backto . '?backto=' . $Current_url);
        }
    }

    function paginations($config)
    {
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';
        return $config;
    }

    public function select2($table, $field = '*', $where = array(), $order = 0, $group =
        0, $limit = 0, $offset = 0)
    {
        $db2 = $this->load->database('second', true);
        $db2->select($field);
        $db2->from($table);
        if (!empty($where)) {
            $db2->where($where);
        }
        if (!empty($order)) {
            $db2->order_by($order);
        }
        if (!empty($group)) {
            $db2->group_by($group);
        }
        if (!empty($limit)) {
            $db2->limit($limit, $offset);
        }
        $query = $db2->get();
        return $query->result();
    }

    function insert2($table, $data = array())
    {
        $db2 = $this->load->database('second', true);
        $db2->simple_query('SET NAMES \'utf-8\'');
        $db2->insert($table, $data);
        return $db2->insert_id();
    }

    function update2($table, $data = array(), $where = array())
    {
        $db2 = $this->load->database('second', true);
        $db2->where($where);
        $db2->simple_query('SET NAMES \'utf-8\'');
        $ok = $db2->update($table, $data);
        return (($ok) ? true : false);
    }

    function delete2($table, $where = array())
    {
        $db2 = $this->load->database('second', true);
        $ok = $db2->delete($table, $where);
        return (($ok) ? true : false);
    }

    function join2($table, $tablejoin, $fields = '*', $where = null, $order = null,
        $group = null, $limit = null)
    {
        $db2 = $this->load->database('second', true);
        $db2->select($fields);
        $db2->from($table);
        if (is_array($tablejoin)) {
            foreach ($tablejoin as $value) {
                //$this->db->join('comments', 'comments.id = blogs.id');
                $db2->join($value['table'], $value['field1'] . '=' . $value['field2']);
            }
        }
        if (!empty($where)) {
            $db2->where($where);
        }
        if (!empty($order)) {
            $db2->order_by($order, "desc");
        }
        if (!empty($group)) {
            $db2->group_by($group);
        }
        if (!empty($limit)) {
            $db2->limit($limit);
        }
        $query = $db2->get();
        return $query->result();
    }

    function like2($table, $field = '*', $search = array(), $where = array())
    {
        $db2 = $this->load->database('second', true);
        $db2->select($field);
        $db2->from($table);
        $db2->like($search);
        if (!empty($where)) {
            $db2->where($where);
        }
        $query = $db2->get();
        return $query->result();
    }

    /* DB for Movies */

    public function select3($table, $field = '*', $where = array(), $order = 0, $group =
        0, $limit = 0, $offset = 0)
    {
        $db3 = $this->load->database('movies', true);
        $db3->select($field);
        $db3->from($table);
        if (!empty($where)) {
            $db3->where($where);
        }
        if (!empty($order)) {
            $db3->order_by($order);
        }
        if (!empty($group)) {
            $db3->group_by($group);
        }
        if (!empty($limit)) {
            $db3->limit($limit, $offset);
        }
        $query = $db3->get();
        return $query->result();
    }

    function insert3($table, $data = array())
    {
        $db3 = $this->load->database('movies', true);
        $db3->simple_query('SET NAMES \'utf-8\'');
        $db3->insert($table, $data);
        return $db3->insert_id();
    }

    function update3($table, $data = array(), $where = array())
    {
        $db3 = $this->load->database('movies', true);
        $db3->where($where);
        $db3->simple_query('SET NAMES \'utf-8\'');
        $ok = $db3->update($table, $data);
        return (($ok) ? true : false);
    }

    function delete3($table, $where = array())
    {
        $db3 = $this->load->database('movies', true);
        $ok = $db3->delete($table, $where);
        return (($ok) ? true : false);
    }

    function join3($table, $tablejoin, $fields = '*', $where = null, $order = null,
        $group = null, $limit = null)
    {
        $db3 = $this->load->database('movies', true);
        $db3->select($fields);
        $db3->from($table);
        if (is_array($tablejoin)) {
            foreach ($tablejoin as $value) {
                //$this->db->join('comments', 'comments.id = blogs.id');
                $db3->join($value['table'], $value['field1'] . '=' . $value['field2']);
            }
        }
        if (!empty($where)) {
            $db3->where($where);
        }
        if (!empty($order)) {
            $db3->order_by($order, "desc");
        }
        if (!empty($group)) {
            $db3->group_by($group);
        }
        if (!empty($limit)) {
            $db3->limit($limit);
        }
        $query = $db3->get();
        return $query->result();
    }

    function like3($table, $field = '*', $search = array(), $where = array())
    {
        $db3 = $this->load->database('movies', true);
        $db3->select($field);
        $db3->from($table);
        $db3->like($search);
        if (!empty($where)) {
            $db3->where($where);
        }
        $query = $db3->get();
        return $query->result();
    }

    /* end DB for Movies */

    //LABEL IN DROP STYLE

    function get_all_sub_caseTypeId($parent_id)
    {
        $table = Tbl_cat_term::TBL;
        $order = Tbl_cat_term::id;
        if (!empty($parent_id)) {
            $getJoin = $this->select2($table, '', array(Tbl_cat_term::id => $parent_id), $order);
        } else {
            $getJoin = $this->select2($table, '', array(), $order);
        }

        foreach ($getJoin as $rows) {
            $child_ids[$rows->{Tbl_cat_term::id}] = ($rows->{Tbl_cat_term::id});
            if ($this->has_child($rows->{Tbl_cat_term::id})) {
                $child_ids[$rows->{Tbl_cat_term::id}] = $this->get_all_sub_caseTypeId($rows->{
                    Tbl_cat_term::id});
            }
        }

        return $child_ids;
    }

    function has_child($parent_id)
    {
        $getJoin = $this->select2(Tbl_cat_term_taxonomy::TBL, '', array(Tbl_cat_term_taxonomy::
                parent => $parent_id));
        if (empty($getJoin)) {
            return false;
        }
        return true;
    }

    function build_tree_drop($parent_id = 0, $level = 0)
    {
        //run our query. by default starts at top of
        //the tree (with zero depth)
        $table = Tbl_cat_term_taxonomy::TBL;
        $tablejoin = array('0' => array(
                'table' => Tbl_cat_term::TBL,
                'field1' => Tbl_cat_term::TBL . '.' . Tbl_cat_term::id,
                'field2' => $table . '.' . Tbl_cat_term_taxonomy::term_id,
                ));
        $where_cat = array(Tbl_cat_term_taxonomy::TBL . '.' . Tbl_cat_term_taxonomy::
                parent => $parent_id);
        //$order = $table . '.' . Tbl_cat_term::id;
        $getJoin = $this->join($table, $tablejoin, '', $where_cat);
        //$result = mysql_query("SELECT * FROM cat_term_taxonomy INNER JOIN cat_term ON cat_term_taxonomy.term_id=cat_term.term_id WHERE parent='" . $parent_id . "'");
        $getcate = '';

        foreach ($getJoin as $row) {
            //indent as necessary and print name
            $getcate .= '<option value="' . $row->term_id . '" class="level-' . $row->
                parent . '">';
            $getcate .= str_repeat('&nbsp;&nbsp;&nbsp;', $level) . $row->name . "\n";
            $getcate .= '</option>';
            //we want to see all the children that belong to this
            //node… so we need to call *this* function again
            $getcate .= $this->build_tree_drop($row->term_id, $level + 1);
        }
        return $getcate;
    }
    function build_menu($parent_id = 0, $level = 0,$show_parent=0,$selected=0)
    {
        //run our query. by default starts at top of
        //the tree (with zero depth)
        $table = Tbl_cat_term_taxonomy::TBL;
        $tablejoin = array('0' => array(
                'table' => Tbl_cat_term::TBL,
                'field1' => Tbl_cat_term::TBL . '.' . Tbl_cat_term::id,
                'field2' => $table . '.' . Tbl_cat_term_taxonomy::term_id,
                ));
        if(!empty($show_parent)){
            $where_cat = array(Tbl_cat_term::TBL . '.' . Tbl_cat_term::slug => $show_parent,Tbl_cat_term::TBL . '.' . Tbl_cat_term::term_group=>'category');
        } else {
            $where_cat = array(Tbl_cat_term_taxonomy::TBL . '.' . Tbl_cat_term_taxonomy::parent => $parent_id,Tbl_cat_term::TBL . '.' . Tbl_cat_term::term_group=>'category');
        }
        //$order = $table . '.' . Tbl_cat_term::id;
        $getJoin = $this->join($table, $tablejoin, '', $where_cat,'cat_term.name ASC');
        //$result = mysql_query("SELECT * FROM cat_term_taxonomy INNER JOIN cat_term ON cat_term_taxonomy.term_id=cat_term.term_id WHERE parent='" . $parent_id . "'");
        $getcate = '';
        $has_childs = false;
        $i= 0;
        foreach ($getJoin as $row) {
            $i++;
            if ($row->parent == $parent_id) { 
            if ($has_childs === false) {
                $has_childs = true;
                if($level!=0) {
                    $getcate .= '<ul style="display:none" class="displaysubcat">';
                }
            }
            if(!empty($selected) && $selected ==$row->slug) {
                $active = 'active';
            } else {
                $active = '';
            }
            if($level!=0) {
                $getCurSlug = $this->select(Tbl_cat_term::TBL,'*',array(Tbl_cat_term::id=>$row->parent));
                if(!empty($getCurSlug)) {
                    $GetCSlug = $getCurSlug[0]->{Tbl_cat_term::slug};
                    $getLink = base_url(). $GetCSlug.'/index/'.$row->slug;
                    if($level+$i==2) {
                        $getcate .= '<li><a href="'. $getCurSlug[0]->{Tbl_cat_term::slug} . '" class="'.$row->parent.' level-'.$level.'">All '.$getCurSlug[0]->{Tbl_cat_term::name}.'</a></li>';
                    }  
                } else {
                    $getLink = base_url() . $row->slug;
                }
            } else {
                $getLink = base_url() . $row->slug;
            }
            //indent as necessary and print name
            //$getcate .= $MainCat;
            $getcate .= '<li class="'.$active.' in-cat-'.$row->slug.'"><a href="'. $getLink . '" class="'.$row->parent.' level-'.$level.'">'.$row->name.'</a>';
            $getcate .= $this->build_menu($row->term_id, $level + 1);
            $getcate .= '</li>';
            //we want to see all the children that belong to this
            //node… so we need to call *this* function again
            }
        }
        if ($has_childs === true) {
            if($level!=0) {
                $getcate .= '</ul>';
            }
        }     
        return $getcate;
    }
    //end LABEL IN DROP STYLE
    //LABEL IN LIST STYLE for edit
    function build_tree_cat($parent_id = 0, $level = 0,$per_page=0, $page=0)
    {
        //run our query. by default starts at top of
        //the tree (with zero depth)
        $table = Tbl_cat_term_taxonomy::TBL;
        $tablejoin = array('0' => array(
                'table' => Tbl_cat_term::TBL,
                'field1' => Tbl_cat_term::TBL . '.' . Tbl_cat_term::id,
                'field2' => $table . '.' . Tbl_cat_term_taxonomy::term_id,
                ));
        $where_cat = array(Tbl_cat_term_taxonomy::TBL . '.' . Tbl_cat_term_taxonomy::
                parent => $parent_id);
        //$order = $table . '.' . Tbl_cat_term::id;
        $getJoin = $this->join($table, $tablejoin, '', $where_cat,"cat_term.term_id DESC",'',$per_page,$page);
        //display each child row
        //"img_id DESC", '', $config['per_page'], $page
        $i = 0;
        $getcate = '';
        foreach ($getJoin as $row) {
            $i++;
            //indent as necessary and print name
            $getcate .= '<tr>';
            $getcate .= '<td align="center"><input type="checkbox" value="' . $row->{
                Tbl_cat_term::id} . '"  id="itemid" name="itemid[]"></td>';
            $getcate .= '<td class="popular-category" id="category-' . $row->{
                Tbl_cat_term_taxonomy::parent} . '">';
            $getcate .= str_repeat('&#8212; ', $level) . $row->{Tbl_cat_term::name} . "\n";
            $getcate .= '</td>';
            $getcate .= '<td><div class="btn-group"><button class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i>
                                                    <span class="caret"></span>
                                                </button><ul class="dropdown-menu">
                                                    <li>
                                                        <a href="' . base_url() .
                'upload/category?edit=' . $row->{Tbl_cat_term::id} .
                '"><i class="fa fa-pencil"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <a data-modal="true" data-text="Do you want to delete this TV?" data-type="confirm" data-class="error" data-layout="top" data-action="tv/category?delete=' .
                $row->{Tbl_cat_term::id} .
                '" class="btn-notification"><i class="fa fa-times"></i> Remove</a>
                                                    </li>
                                                </ul></div></td>';
            $getcate .= '</tr>';
            //we want to see all the children that belong to this
            //node… so we need to call *this* function again
            $getcate .= $this->build_tree_cat($row->{Tbl_cat_term::id}, $level + 1);
        }
        //echo '<ul class="categories">';
        //build_tree($parent_id=0,$level=0);
        //echo '</ul>';
        return $getcate;
    }

    function addHistory($vdo_id, $bloginCat = 'editaction')
    {
        $log_id = $this->session->userdata('log_id');
        $curdate = date('Y-m-d', time());
        $data_his_ch = array(
            Tbl_history::object_id => $vdo_id,
            Tbl_history::user_id => $log_id,
            Tbl_history::date . ' =' => $curdate,
            );
        $movie_post = $this->Mod_general->select(Tbl_history::Table, '', $data_his_ch);
        if (empty($movie_post)) {
            $Data_his = array(
                Tbl_history::object_id => $vdo_id,
                Tbl_history::type => $bloginCat,
                Tbl_history::user_id => $log_id,
                Tbl_history::date => date('Y-m-d', time()),
                );
            $hist_rec = $this->Mod_general->insert(Tbl_history::Table, $Data_his);
        }
    }

    function get_image()
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            $DataImage = array('img_object_id' => $user_id, 'img_obj_type' => 'user');
            $GetImage = $this->select('image', '*', $DataImage, 'img_id DESC');
            foreach ($GetImage as $image) {
                echo '<div class="photos_item" id="ITM' . $image->img_id . '">';
                echo '<span class="pointer" onclick="choosePhotoClick(' . $image->img_id . ',\'' .
                    $image->img_path . '\',\'' . $image->img_w . 'x' . $image->img_h . '\');">';
                echo '<img src="' . $image->img_path . '">';
                echo '</span>';
                echo '<div class="delPhoto" style="background:URL(\'' . base_url() .
                    'img/img/delete16.png\') top left no-repeat" onclick=\'choosePhotoDel("' . $image->
                    img_id . '","' . $image->img_path . '","' . $user_id . '");\'></div>';
                echo '</div>';
            }
        }
    }
    function watermark($photo_id)
    {
        $user_id = $this->session->userdata('user_id');
        if ($user_id) {
            $DataImage = array('img_id' => $photo_id);
            $GetImage = $this->select('image', '*', $DataImage);
            foreach ($GetImage as $image) {

            }
        }
    }

    function do_upload()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->helper('path');
        $directory = FCPATH;
        $base_path = set_realpath($directory);
        $config['upload_path'] = $ImagePath;
        $config['allowed_types'] = 'gif|jpg|png|bmp';
        $config['max_size'] = '2048';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('upload_form', $error);
        }
    }
    //end LABEL IN LIST STYLE for edit

    function deletePhoto($client, $user, $albumId, $photoId)
    {
        $photos = new Zend_Gdata_Photos($client);

        $photoQuery = new Zend_Gdata_Photos_PhotoQuery;
        $photoQuery->setUser($user);
        $photoQuery->setAlbumId($albumId);
        $photoQuery->setPhotoId($photoId);
        $photoQuery->setType('entry');

        $entry = $photos->getPhotoEntry($photoQuery);

        $photos->deletePhotoEntry($entry, true);
        return true;
        //outputAlbumFeed($client, $user, $albumId);
    }
    function addPhoto($client, $user, $albumId, $photo)
    {
        $photos = new Zend_Gdata_Photos($client);

        $fd = $photos->newMediaFileSource($photo["tmp_name"]);
        $fd->setContentType($photo["type"]);

        $entry = new Zend_Gdata_Photos_PhotoEntry();
        $entry->setMediaSource($fd);
        $entry->setTitle($photos->newTitle($photo["name"]));

        $albumQuery = new Zend_Gdata_Photos_AlbumQuery;
        $albumQuery->setUser($user);
        $albumQuery->setAlbumId($albumId);
        $albumEntry = $photos->getAlbumEntry($albumQuery);
        $result = $photos->insertPhotoEntry($entry, $albumEntry);
        if ($result) {
            $image = $result->getMediaGroup()->GetThumbnail();
            $getUrl = $image[0]->getUrl();
            $getGphotoId = $result->getGphotoId()->getText();
            $getTitle = $result->getTitle()->getText();
            $imgName = explode('.', $photo["name"]);
            $imgName = str_replace('_', ' ', $imgName[0]);
            $imgName = str_replace('-', ' ', $imgName);
            $data_result = array(
                'image' => $getUrl,
                'imageId' => $getGphotoId,
                'imageTitle' => $imgName,
                );
        } else {
            $data_result = array();
        }
        return $data_result;
    }
    function resize_image($url, $imgsize, $height='')
    {
        if (preg_match('/blogspot/', $url)) {
            //inital value
            $newsize = "w" . $imgsize;
            $newurl = "";
            //Get Segments
            $path = parse_url($url, PHP_URL_PATH);
            $segments = explode('/', rtrim($path, '/'));
            //Get URL Protocol and Domain
            $parsed_url = parse_url($url);
            $domain = $parsed_url['scheme'] . "://" . $parsed_url['host'];
            $newurl_segments = array(
                $domain . "/",
                $segments[1] . "/",
                $segments[2] . "/",
                $segments[3] . "/",
                $segments[4] . "/",
                $newsize . $height."/", //change this value
                $segments[6]);
            $newurl_segments_count = count($newurl_segments);
            for ($i = 0; $i < $newurl_segments_count; $i++) {
                $newurl = $newurl . $newurl_segments[$i];
            }
            return $newurl;
        } else
            if (preg_match('/googleusercontent/', $url)) {
                //inital value
                $newsize = "w" . $imgsize;
                $newurl = "";
                //Get Segments
                $path = parse_url($url, PHP_URL_PATH);
                $segments = explode('/', rtrim($path, '/'));
                //Get URL Protocol and Domain
                $parsed_url = parse_url($url);
                $domain = $parsed_url['scheme'] . "://" . $parsed_url['host'];
                $newurl_segments = array(
                    $domain . "/",
                    $segments[1] . "/",
                    $segments[2] . "/",
                    $segments[3] . "/",
                    $segments[4] . "/",
                    $newsize . $height."/", //change this value
                    $segments[6]);
                $newurl_segments_count = count($newurl_segments);
                for ($i = 0; $i < $newurl_segments_count; $i++) {
                    $newurl = $newurl . $newurl_segments[$i];
                }
                return $newurl;
            } else {
                return $url;
            }
    }
    function p_resize_image($url, $imgsize)
    {
        if (preg_match('/googleusercontent/', $url)) {
            //inital value
            $newsize = "s" . $imgsize;
            $newurl = "";
            //Get Segments
            $path = parse_url($url, PHP_URL_PATH);
            $segments = explode('/', rtrim($path, '/'));
            //Get URL Protocol and Domain
            $parsed_url = parse_url($url);
            $domain = $parsed_url['scheme'] . "://" . $parsed_url['host'];
            $newurl_segments = array(
                $domain . "/",
                $segments[1] . "/",
                $segments[2] . "/",
                $segments[3] . "/",
                $segments[4] . "/",
                $newsize . "/", //change this value
                $segments[6]);
            $newurl_segments_count = count($newurl_segments);
            for ($i = 0; $i < $newurl_segments_count; $i++) {
                $newurl = $newurl . $newurl_segments[$i];
            }
            return $newurl;
        } else {
            return $url;
        }
    }
    function text_only($str){ 
        $text = preg_match('/[a-z]+/i',$str); 
        if($text == true) {
            return $str;
        } else {
            return false;
        }
    }
}
