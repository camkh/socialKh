<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Managecampaigns extends CI_Controller {

    protected $mod_general;
    public function __construct() {
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->library('dbtable');
        $this->load->theme('layout');
        $this->mod_general = new Mod_general();
        TIME_ZONE;
    }

    public function index() {
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('user_id');
        $user = $this->session->userdata('email');
        $provider_uid = $this->session->userdata('provider_uid');
        $provider = $this->session->userdata('provider');
        $this->load->theme('layout');
        $data['title'] = 'Admin Area :: Manage Campaigns';
        $data['addJsScript'] = array("$('#checkAll').click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
 $('#multidel').click(function () {
     if (!$('#itemid:checked').val()) {
            alert('please select one');
            return false;
    } else {
            return confirm('Do you want to delete all?');
    }
 });");
        //$backto = base_url() . 'post/blogpassword';
        //$query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $provider = str_replace('facebook', 'Facebook', $provider);
        $where_so = array(Tbl_posts::user => $log_id);
        $this->load->library('pagination');
        $per_page = (!empty($_GET['result'])) ? $_GET['result'] : 10;
        $config['base_url'] = base_url() . 'post/bloglist/';
        $count_blog = $this->Mod_general->select(Tbl_posts::tblName, '*', $where_so);
        $config['total_rows'] = count($count_blog);
        $config['per_page'] = $per_page;
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $query_blog = array();
        if (empty($filtername)) {
            $query_blog = $this->Mod_general->select(Tbl_posts::tblName, '*', $where_so,
                "p_id DESC", '', $config['per_page'], $page);
        }
        $i = 1;

        $data['socialList'] = $query_blog;

        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $data["total_rows"] = count($count_blog);
        $data["results"] = $query_blog;
        $data["links"] = $this->pagination->create_links();
        /* end get pagination */

        $log_id = $this->session->userdata('log_id');
        $user = $this->session->userdata('username');
        $this->load->view('managecampaigns/index', $data);
    }
    public function add() {
        $this->Mod_general->checkUser();
        $actions = $this->uri->segment(3);
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $log_id = $this->session->userdata('user_id');
        $this->Mod_general->checkUser();
        $user = $this->session->userdata('email');
        $provider_uid = $this->session->userdata('provider_uid');
        $provider = $this->session->userdata('provider');
        $this->load->theme('layout');
        $data['title'] = 'Admin Area :: Manage Campaigns';

        /*get post for each user*/
        $where_so = array(Tbl_posts::user => $log_id, Tbl_posts::id => $id);
        $dataPost = $this->Mod_general->select(Tbl_posts::tblName, '*', $where_so);
        $data['data'] = $dataPost;
        /* end get post for each user*/

        /*get User for each user*/
        $where_uGroup = array(Tbl_social::u_id => $log_id, Tbl_social::s_status => 1);
        $dataGroup = $this->Mod_general->select(Tbl_social::tblName, '*', $where_uGroup);
        $data['account'] = $dataGroup;
        /* end get User for each user*/

        $ajax = base_url() . 'managecampaigns/ajax?gid=';
        $data['js'] = array(
            'themes/layout/blueone/plugins/validation/jquery.validate.min.js',
            'themes/layout/blueone/plugins/pickadate/picker.js',
            'themes/layout/blueone/plugins/pickadate/picker.time.js',
            );
        $data['addJsScript'] = array("
        $(document).ready(function() {
            $('#togroup').click(function () {
            if($(this).is(\":checked\")) {
                var gid = $('#Groups').val();
                var a = gid.split(\"|\");
                if(a[0]&& a['1']=='Facebook') {
                    $('#groupWrapLoading').show();
                    $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=getgroup',
                        cache: false,
                        success: function(html)
                        {
                            $('#groupWrapLoading').hide();
                            $(\"#getAllGroups\").html(html);
                            $(\"#groupWrap\").show();
                        }
                    });
                     $('#showgroum').hide();
                } else {
                    $('#showgroum').show();
                }
            } else {
                $(\"#groupWrap\").hide();
            }
        });
        
        $('#towall').click(function () {
            if($(this).is(\":checked\")) {
                $(\"#groupWrap\").hide();
            }
        });
        $('#Groups').change(function () {
            if($(this).val()){
                $('#showgroum').hide();
                $('#togroup').prop('checked', false);
                $('#checkAll').prop('checked', false);
                $('#groupWrap').hide();
            } else {
                $('#showgroum').show();
            }
        });
        $('#checkAll').click(function() {
            $('.tgroup').not(this).prop('checked', this.checked);
         });
         
         $('#addGroups').click(function () {
            if (!$('.tgroup:checked').val()) {
                alert('please select one');
            } else {
                var checkbox_value = '';
                $(\".tgroup\").each(function () {
                    var ischecked = $(this).is(\":checked\");
                    if (ischecked) {
                        checkbox_value += $(this).val() + \"|\";
                    }
                });
                
                var gid = $('#Groups').val();
                var postID = $('#postID').val();
                $.ajax
                    ({
                        type: \"get\",
                        url: \"$ajax\"+gid+'&p=addgroup&g='+checkbox_value+'&pid='+postID,
                        cache: false,
                        success: function(html)
                        {
                            var success = generate('success');
                            setTimeout(function () {
                                $.noty.setText(success.options.id, html+' Groups has been added');
                            }, 1000);
                            setTimeout(function () {
                                $.noty.closeAll();
                            }, 4000);
                        }
                    });
            }
         });
 
         
         $(\"#datepicker\").datepicker({
              changeMonth: true,
              changeYear: true
            });
            
         $(\"#datepickerEnd\").datepicker({
              changeMonth: true,
              changeYear: true
            });
         $('#timepicker').pickatime();
         $('#timepickerEnd').pickatime();
         $.validator.addClassRules('required', {
            required: true
         });
         $('#validate').validate();
     });
    ");

        /* get form */
        if ($this->input->post('submit')) {
            $videotype = '';
            $title = $this->input->post('title');
            $thumb = $this->input->post('thumb');
            $message = $this->input->post('message');
            $caption = $this->input->post('caption');
            $link = $this->input->post('link');
            $accoung = $this->input->post('accoung');
            $postTo = $this->input->post('postto');
            $itemId = $this->input->post('itemid');
            $videoType = $this->input->post('videotype');
            $startDate = $this->input->post('startDate');
            $startTime = $this->input->post('startTime');
            $endDate = $this->input->post('endDate');
            $loopEvery = $this->input->post('loop');
            $looptype = $this->input->post('looptype');
            $loopOnDay = $this->input->post('loopDay');
            $itemGroups = $this->input->post('itemid');
            $postId = $this->input->post('postid');

            /*check account type */
            $s_acount = explode('|', $accoung);
            /*end check account type */

            /*data content*/
            $content = array(
                'name' => @$title,
                'message' => @$message,
                'caption' => @$caption,
                'link' => @$link,
                'picture' => @$thumb,
                );
            /*end data content*/

            /*data schedule*/
            $days = array();
            foreach ($loopOnDay as $dayLoop) {
                if (!empty($dayLoop)) {
                    $days[] = $dayLoop;
                }
            }
            $schedule = array(
                'start_date' => @$startDate,
                'start_time' => @$startTime,
                'end_date' => @$endDate,
                'end_time' => @$endDate,
                'loop' => @$looptype,
                'loop_every' => @$loopEvery,
                'loop_on' => @$days,
                );
            /*end data schedule*/

            $this->load->library('form_validation');
            $this->form_validation->set_rules('link', 'link', 'required');
            $this->form_validation->set_rules('thumb', 'thumb', 'required');
            $this->form_validation->set_rules('accoung', 'accoung', 'required');
            if ($this->form_validation->run() == true) {
                /*add data to post*/
                $dataPostInstert = array(
                    Tbl_posts::name => $title,
                    Tbl_posts::conent => json_encode($content),
                    Tbl_posts::p_date => strtotime(date('l jS \of F Y h:i:s A')),
                    Tbl_posts::schedule => json_encode($schedule),
                    Tbl_posts::user => $log_id,
                    Tbl_posts::post_to => $postTo,
                    Tbl_posts::type => @$s_acount[1],
                    );
                if (!empty($postId)) {
                    $AddToPost = $postId;
                    $this->Mod_general->update(Tbl_posts::tblName, $dataPostInstert, array(Tbl_posts::
                            id => $postId));
                } else {
                    $AddToPost = $this->Mod_general->insert(Tbl_posts::tblName, $dataPostInstert);
                }
                /*end add data to post*/

                /*add data to group of post*/
                //                if(!empty($itemGroups)) {
                //                    foreach($itemGroups as $key => $groups) {
                //                        if(!empty($groups)) {
                //                            $dataGoupInstert = array(
                //                                Tbl_share::post_id => $AddToPost,
                //                                Tbl_share::group_id => $groups,
                //                                Tbl_share::social_id => @$s_acount[0],
                //                                Tbl_share::option => json_encode($schedule),
                //                                Tbl_share::type => @$s_acount[1],
                //                            );
                //                            $AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                //                        }
                //                    }
                //                }
                /*end add data to group of post*/
            }
            redirect(base_url() . 'managecampaigns');
        }
        /*end form*/
        $this->load->view('managecampaigns/add', $data);
    }

    public function fromurl() {
        $data['title'] = 'Get from url';
        //$this->Mod_general->checkUser();
        //$backto = base_url() . 'post/blogpassword';
        //$query_blog = $this->Mod_general->blogcheck(current_url(), $backto);
        $log_id = $this->session->userdata('user_id');

        /* Sidebar */
        //$menuPermission = $this->Mod_general->getMenuUser();
        //$data['menuPermission'] = $menuPermission;
        /* form */
        if ($this->input->post('submit')) {
            $videotype = '';
            $this->load->library('form_validation');
            $this->form_validation->set_rules('blogid', 'blogid', 'required');
            if ($this->form_validation->run() == true) {
                $xmlurl = $this->input->post('blogid');
                $thumb = $this->input->post('imageid');
                $title = $this->input->post('title');
                $code = $this->get_from_url_id($xmlurl, $thumb);
                if (!empty($code)) {
                    $data_post_id = array(
                        Tbl_posts::name => $code['title'],
                        Tbl_posts::user => $log_id,
                        Tbl_posts::conent => json_encode($code),
                        );
                    $dataPostID = $this->Mod_general->insert(Tbl_posts::tblName, $data_post_id);
                    redirect(base_url() . 'managecampaigns/add?id=' . $dataPostID);
                }
            }
            die;
        }
        /* end form */

        /* show to view */

        $data['js'] = array('themes/layout/blueone/plugins/validation/jquery.validate.min.js', );
        $data['addJsScript'] = array("$(document).ready(function(){
                $.validator.addClassRules('required', {
                required: true
                });                
            });
            $('#validate').validate();
            ");
        $this->load->view('managecampaigns/fromurl', $data);

    }

    public function delete() {
        $actions = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        switch ($actions) {
            case "deletecampaigns":
                $this->Mod_general->delete(Tbl_posts::tblName, array(Tbl_posts::id => $id));
                redirect('managecampaigns');
                break;
        }
    }
    function get_from_url_id($url, $image_id = '') {
        $this->Mod_general->checkUser();
        $log_id = $this->session->userdata('user_id');
        /* Sidebar */
        if (!empty($url)) {
            $this->load->library('html_dom');
            $html = file_get_html($url);
            $title = @$html->find('.post-title a', 0)->innertext;
            $title1 = @$html->find('.post-title', 0)->innertext;
            if ($title) {
                $title = $html->find('.post-title a', 0)->innertext;
            } elseif ($title1) {
                $title = $html->find('.post-title', 0)->innertext;
            } else {
                $title = $html->find('title', 0)->innertext;
            }
            $postTitle = $title;
            $og_image = @$html->find('meta [property=og:image]', 0)->content;
            $image_src = @$html->find('link [rel=image_src]', 0)->href;
            if (!empty($image_src)) {
                $thumb = $image_src;
            } elseif (!empty($html->find('meta [property=og:image]', 0)->content)) {
                $thumb = $html->find('meta [property=og:image]', 0)->content;
            } else {
                $thumb = $image_id;
            }
            $thumb = $this->resize_image($thumb);
            $short_url = $this->get_bitly_short_url($url, BITLY_USERNAME, BITLY_API_KEY);
            $data = array(
                'image' => @$thumb,
                'title' => trim($title),
                'link' => $short_url,
                );
            if (!empty($data)) {
                return $data;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    function resize_image($url, $imgsize = 0) {
        if (preg_match('/blogspot/', $url)) {
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
        } else
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

    /* returns the shortened url */
    function get_bitly_short_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/shorten?login=' . $login . '&apiKey=' . $appkey .
            '&uri=' . urlencode($url) . '&format=' . $format;
        return $this->curl_get_result($connectURL);
    }

    /* returns expanded url */
    function get_bitly_long_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/expand?login=' . $login . '&apiKey=' . $appkey .
            '&shortUrl=' . urlencode($url) . '&format=' . $format;
        return $this->curl_get_result($connectURL);
    }

    /* returns a result form url */
    function curl_get_result($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /* returns a result form url */
    function ajax() {
        //getgroup
        $id = !empty($_GET['gid']) ? $_GET['gid'] : '';
        $page = !empty($_GET['p']) ? $_GET['p'] : '';
        $log_id = $this->session->userdata('user_id');
        $data = '';
        if ($log_id) {
            switch ($page) {
                case 'getgroup':
                    $where_uGroup = array(
                        Tbl_social_group::socail_id => $id,
                        Tbl_social_group::status => 1,
                        Tbl_social_group::type => 'groups');
                    $dataGroup = $this->Mod_general->select(Tbl_social_group::tblName, '*', $where_uGroup);
                    $i = 0;
                    foreach ($dataGroup as $gvalue) {
                        $i++;
                        $data .= '<label class="checkbox"><input type="checkbox" class="tgroup" name="itemid[]" value="' .
                            $gvalue->{Tbl_social_group::page_id} . '"/>' . $i . ' - ' . $gvalue->{
                            Tbl_social_group::name} . '</label>';
                    }
                    echo $data;
                    break;

                case 'addgroup':
                    $groups = !empty($_GET['g']) ? $_GET['g'] : '';
                    $pid = !empty($_GET['pid']) ? $_GET['pid'] : '';
                    if (!empty($groups)) {
                        $groupsArr = explode('|', $groups);
                        $s_value = explode('|', $id);
                        $groupCount = array();
                        foreach ($groupsArr as $group) {
                            $checkExist = $this->mod_general->select(Tbl_share::TblName, '*', array(
                                Tbl_share::group_id => $group,
                                Tbl_share::post_id => $pid,
                                Tbl_share::social_id => @$s_value[0]));
                            if (empty($checkExist) && !empty($s_value[0])) {
                                $dataGoupInstert = array(
                                    Tbl_share::post_id => $pid,
                                    Tbl_share::group_id => $group,
                                    Tbl_share::social_id => @$s_value[0],
                                    Tbl_share::type => @$s_value[1],
                                    );
                                $AddToGroup = $this->Mod_general->insert(Tbl_share::TblName, $dataGoupInstert);
                                array_push($groupCount, $group);
                            }

                        }
                        echo count($groupCount);
                    }
                    break;
            }
        }
    }

    public function schedules() {
        ob_start();
        $getPosts = $this->mod_general->select(Tbl_posts::tblName, '', array(Tbl_posts::status => 1));
        if (!empty($getPosts)) {
            foreach ($getPosts as $toPost) {
                $getTimes = json_decode($toPost->{Tbl_posts::schedule}, true);
                $postTo = $toPost->{Tbl_posts::post_to};
                $postProgress = $toPost->{Tbl_posts::progress};

                $currentTime = time();
                $start_date = $getTimes['start_date'];
                $start_time = $getTimes['start_time'];
                $loop = $getTimes['loop'];
                $time = strtotime($start_date . ' ' . $start_time);
                $newformat = date('Y-m-d H:i:s', $time);
                $date = strtotime($start_date);
                $newDate = date('Y-m-d', $date);
                $end_date = $getTimes['end_date'];
                $endDate = strtotime($end_date);

                if ($postTo == 'groups') {
                    /*get groups*/

                    /*end get groups*/
                } else if ($postTo == 'wall') {

                }
                
                if ($postProgress == 0) {
                    $this->mod_general->update(Tbl_posts::tblName, 
                        array(Tbl_posts::progress => 1),
                        array(Tbl_posts::id => $toPost->{Tbl_posts::id})
                    );
                } else {
                    
                }
            }
        }
        ob_flush();
die;
        ///////////////////////////
        $getSocial = $this->mod_general->select(Tbl_share::TblName, '', null, '',
            Tbl_share::social_id);
        foreach ($getSocial as $social) {
            /*get time schedules*/
            $getTime = $this->mod_general->select(Tbl_posts::tblName, '', array(Tbl_posts::
                    id => $social->{Tbl_share::post_id}, Tbl_posts::status => 1));
            if (!empty($getTime[0])) {
                $getTimes = json_decode($getTime[0]->{Tbl_posts::schedule}, true);

                $postTo = $getTime[0]->{Tbl_posts::post_to};
                $postProgress = $getTime[0]->{Tbl_posts::progress};

                $currentTime = time();
                $start_date = $getTimes['start_date'];
                $start_time = $getTimes['start_time'];
                $loop = $getTimes['loop'];
                $time = strtotime($start_date . ' ' . $start_time);
                $newformat = date('Y-m-d H:i:s', $time);
                $date = strtotime($start_date);
                $newDate = date('Y-m-d', $date);

                $end_date = $getTimes['end_date'];
                $endDate = strtotime($end_date);
                /*end get time schedules*/
                if ($postTo == 'groups') {
                    /*get groups*/
                    $getGroups = $this->mod_general->select(Tbl_share::TblName, '', array(Tbl_share::
                            social_id => $social->{Tbl_share::social_id}));
                    if (!empty($getGroups)) {
                        $i = 0;
                        $countTime = array();
                        foreach ($getGroups as $groups) {
                            $i++;

                            /*check post is in progress run in the first time*/
                            if ($postProgress == 0) {
                                $today = time();
                                if ($i % 5 == 0) {
                                    $push = 60;
                                    for ($j = 0; ($j < $push); $j++) {
                                        array_push($countTime, 1);
                                    }
                                }
                                $counts = count($countTime);
                                $dateNew = $time++ + (strtotime($today) + @$counts);
                                if (!empty($groups->{Tbl_share::group_id})) {
                                    $dataNew = array(
                                        Tbl_share_pro::datetime => $dateNew,
                                        Tbl_share_pro::status => 0,
                                        Tbl_share_pro::group_id => $groups->{Tbl_share::group_id},
                                        Tbl_share_pro::post_id => $groups->{Tbl_share::post_id},
                                        Tbl_share_pro::social_id => $groups->{Tbl_share::social_id},
                                        Tbl_share_pro::type => $groups->{Tbl_share::type},
                                        Tbl_share_pro::share_id => $groups->{Tbl_share::id},
                                        );
                                    $this->mod_general->insert(Tbl_share_pro::TblName, $dataNew);
                                }
                                $this->mod_general->update(Tbl_posts::tblName, array(Tbl_posts::progress => 1),
                                    array(Tbl_posts::id => $getTime[0]->{Tbl_posts::id}));
                                /*end check post is in progress run in the first time*/

                            } else {
                                if ($loop == 1) {
                                    /* get post if not the first time*/
                                    if (!empty($end_date) && $currentTime > $endDate) {
                                        echo 11111111111;
                                        /*if set end date*/
                                        $groupPostTime = $groups->{Tbl_share::date_post};
                                        /*end if set end date*/

                                    } else
                                        if (empty($end_date)) {

                                            /*loop post*/
                                        }
                                    /* end get post if not the first time*/
                                }
                            }
                        }
                    }
                    //var_dump($getGroups);
                    /*end get groups*/
                } else
                    if ($postTo == 'wall') {

                    }

            }
        }
        ob_flush();
        die;
    }

    public function socailpost() {
        $postProgress = $this->mod_general->select(Tbl_share_pro::TblName);
        $today = time();
        if (!empty($postProgress)) {
            $i = 0;
            $countTime = array();
            foreach ($postProgress as $setPost) {
                if ($setPost->{Tbl_share_pro::status} == 0) {
                    $i++;
                    $datePost = $setPost->{Tbl_share_pro::datetime};
                    $endTime = $datePost + 14;
                    $getAccessToken = $this->mod_general->select(Tbl_social::tblName, '*', array(Tbl_social::
                            s_id => $setPost->{Tbl_share_pro::social_id}));
                    $getPostData = $this->mod_general->select(Tbl_posts::tblName, '*', array(Tbl_posts::
                            id => $setPost->{Tbl_share_pro::post_id}));
                    if (!empty($getAccessToken) && !empty($getPostData) && $getAccessToken[0]->{
                        Tbl_social::s_type} == 'Facebook' && $setPost->{Tbl_share_pro::type} ==
                        'Facebook') {
                        $postFB = $this->postToFacebook($getPostData, $getAccessToken, $setPost->{
                            Tbl_share_pro::group_id});
                        if (!empty($postFB['id'])) {
                            $splitId = explode("_", $postFB['id']);
                            if (!empty($splitId[1]))
                                $this->mod_general->update(Tbl_share_pro::TblName, array(
                                    Tbl_share_pro::post_time => time(),
                                    Tbl_share_pro::status => 1,
                                    Tbl_share_pro::id_posted_group => $splitId[1]), array(Tbl_share_pro::id => $setPost->{
                                        Tbl_share_pro::id}));
                        } elseif (!empty($postFB['error'])) {
                            $this->mod_general->update(Tbl_share_pro::TblName, array(Tbl_share_pro::
                                    post_time => time(), Tbl_share_pro::status => 2), array(Tbl_share_pro::id => $setPost->{
                                    Tbl_share_pro::id}));
                            //error_log(print_r($postFB['error'], true));
                        }
                        if ($i % 5 == 0) {
                            sleep(10);
                        } else {
                            sleep(3);
                        }
                    }

                }
                //echo ' time post: '.$datePost .' current date: '. $today . ' endtime: ' .$endTime .'<br/>';
                //                if($today >= $datePost && $today <= $endTime) {
                //                    echo 'the time is up :' . date('Y-m-d H:i:s',$datePost);
                //                    echo '<br/>';
                //                }
                /*delete the preveous post */
                $deleteOn = strtotime("last month");
                $postOn = $setPost->{Tbl_share_pro::post_time};
                if ($deleteOn > $postOn) {
                    $this->mod_general->delete(Tbl_share_pro::TblName, array(Tbl_share_pro::id => $setPost->{
                            Tbl_share_pro::id}));
                }
                /*end delete the preveous post */
            }
        }
        die;
    }

    /*post to facebook api*/
    public function postToFacebook($getPostData, $getAccessToken, $group) {
        $DataArr = json_decode($getPostData[0]->{Tbl_posts::conent}, true);
        $ValueArr = array('access_token' => $getAccessToken[0]->s_access_token);
        $dataArrs = array_merge($DataArr, $ValueArr);

        $this->load->library('HybridAuthLib');
        $provider = ($this->uri->segment(3)) ? $this->uri->segment(3) : $getAccessToken[0]->{
            Tbl_social::s_type};
        try {
            if ($this->hybridauthlib->providerEnabled($provider)) {
                $service = $this->hybridauthlib->authenticates($provider);
                $facebook = new Facebook(array(
                    'appId' => $service->config['keys']['id'],
                    'secret' => $service->config['keys']['secret'],
                    'cookie' => true,
                    ));
                //                $getAccessToken = $this->mod_general->select(Tbl_social::tblName);
                //                $access_token = $getAccessToken[1]->s_access_token;
                //                $post =  array(
                //                    'access_token' => $access_token,
                //                    'message' => $getPostData[0]->{Tbl_posts::conent},
                //                    'name' =>$getPostData[0]->{Tbl_posts::name},
                //                    'link' =>$getPostData[0]->{Tbl_posts::modify},
                //                    'caption' =>'How to compare car insurance quotes to get the cheapest deal',
                //                    'picture' =>'https://lh6.googleusercontent.com/-CmaOJMcoRqs/VSh-LvE70OI/AAAAAAAAKMg/5QI9bRuufpc/w800/_epLGtneZ_1421754324.jpg',
                //                );

                //and make the request
                $res = $facebook->api('/' . $group . '/feed', 'POST', $dataArrs);
                if ($res) {
                    return $res;
                }

            }
        }
        catch (exception $e) {

        }
    }
    /*end post to facebook api*/
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
