<?php

require_once("Home.php"); // including home controller

/**
* class admin_config
* @category controller
*/
class Admin_config extends Home
{
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login_page', 'location');
        }

        if ($this->session->userdata('user_type')!= 'Admin') {
            redirect('home/login_page', 'location');
        }

        $this->important_feature();
        $this->periodic_check();
    }

    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    public function index()
    {
        $this->configuration();
    }

    /**
    * load config form method
    * @access public
    * @return void
    */
    public function configuration()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
           redirect('home/login_page', 'location');
        }
        
        $data['body'] = "admin/config/edit_config";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('general settings');
        $this->_viewcontroller($data);
    }

    /**
    * method to edit config
    * @access public
    * @return void
    */
    public function edit_config()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('institute_name',       '<b>'.$this->lang->line("company name").'</b>',             'trim');
            $this->form_validation->set_rules('institute_address',    '<b>'.$this->lang->line("company address").'</b>',          'trim');
            $this->form_validation->set_rules('institute_email',      '<b>'.$this->lang->line("company email").'</b>',            'trim|required');
            $this->form_validation->set_rules('institute_mobile',     '<b>'.$this->lang->line("company phone/ mobile").'</b>',    'trim');
            $this->form_validation->set_rules('time_zone',            '<b>'.$this->lang->line("time zone").'</b>',                'trim');
            $this->form_validation->set_rules('slogan',               '<b>'.$this->lang->line("slogan").'</b>',                 'trim');

            $this->form_validation->set_rules('language',             '<b>'.$this->lang->line("language").'</b>',                 'trim');

            $this->form_validation->set_rules('product_name',         '<b>'.$this->lang->line("product name").'</b>',                 'trim');
            $this->form_validation->set_rules('product_short_name',   '<b>'.$this->lang->line("product short name").'</b>',                 'trim');
            $this->form_validation->set_rules('backup_mode',   '<b>'.$this->lang->line("Backup Mode").'</b>',                 'trim');
            $this->form_validation->set_rules('number_of_message_to_be_sent_in_try',   '<b>'.$this->lang->line("number of message send per cron job").'</b>',                 'trim');
            $this->form_validation->set_rules('update_report_after_time',   '<b>'.$this->lang->line("message sending report update frequency").'</b>',                 'trim');
            $this->form_validation->set_rules('theme',   '<b>'.$this->lang->line("theme").'</b>',                 'trim');

            $this->form_validation->set_rules('auto_reply_delay_time',   '<b>'.$this->lang->line("delay used in auto-reply (seconds)").'</b>',                 'trim');
            $this->form_validation->set_rules('auto_reply_campaign_live_duration',   '<b>'.$this->lang->line("auto-reply campaign live duration (days)").'</b>',                 'trim');
            $this->form_validation->set_rules('master_password',   '<b>'.$this->lang->line("Master Password").'</b>',                 'trim');
            $this->form_validation->set_rules('email_sending_option',  '<b>'.$this->lang->line("Email sending option").'</b>','trim');
            $this->form_validation->set_rules('auto_reply_campaign_per_cron_job',  '<b>'.$this->lang->line("number of autoreply campaign processed per cron job").'</b>','trim');
            $this->form_validation->set_rules('number_of_old_comment_reply',  '<b>'.$this->lang->line("how much old comment that system will reply?").'</b>','trim');
            $this->form_validation->set_rules('autoreply_renew_access',  '<b>'.$this->lang->line("Give autoreply renew access to users").'</b>','trim');
            $this->form_validation->set_rules('developer_access',  '<b>'.$this->lang->line("Want to use developer's facebook app?").'</b>','trim');
            $this->form_validation->set_rules('force_https',  '<b>'.$this->lang->line("Force HTTPS").'</b>','trim');
            $this->form_validation->set_rules('enable_support',  '<b>'.$this->lang->line("Enable Suppordesk").'</b>','trim');
            $this->form_validation->set_rules('enable_signup_form',  '<b>'.$this->lang->line("Enable Signup Form").'</b>','trim');

            $this->form_validation->set_rules('facebook_poster_image_upload_limit',  '<b>'.$this->lang->line("Facebook poster image upload limit").'</b>','trim');
            $this->form_validation->set_rules('facebook_poster_video_upload_limit',  '<b>'.$this->lang->line("Facebook poster video upload limit").'</b>','trim');
            $this->form_validation->set_rules('autoreply_image_upload_limit',  '<b>'.$this->lang->line("Autoreply section image upload limit").'</b>','trim');
            $this->form_validation->set_rules('autoreply_video_upload_limit',  '<b>'.$this->lang->line("Autoreply section video upload limit").'</b>','trim');
            $this->form_validation->set_rules('comboposter_image_upload_limit',  '<b>'.$this->lang->line("Comboposter section image upload limit").'</b>','trim');
            $this->form_validation->set_rules('comboposter_video_upload_limit',  '<b>'.$this->lang->line("Comboposter section video upload limit").'</b>','trim');
            $this->form_validation->set_rules('vidcaster_image_upload_limit',  '<b>'.$this->lang->line("VidcasterLive section image upload limit").'</b>','trim');
            $this->form_validation->set_rules('vidcaster_video_upload_limit',  '<b>'.$this->lang->line("VidcasterLive section video upload limit").'</b>','trim');
            $this->form_validation->set_rules('messengerbot_image_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section image upload limit").'</b>','trim');
            $this->form_validation->set_rules('messengerbot_video_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section video upload limit").'</b>','trim');
            $this->form_validation->set_rules('messengerbot_audio_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section audio upload limit").'</b>','trim');
            $this->form_validation->set_rules('messengerbot_file_upload_limit',  '<b>'.$this->lang->line("Messenger Bot section file upload limit").'</b>','trim');
            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) {
                return $this->configuration();
            } else {
                // assign
                $institute_name=addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address=addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_email=addslashes(strip_tags($this->input->post('institute_email', true)));
                $institute_mobile=addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $time_zone=addslashes(strip_tags($this->input->post('time_zone', true)));
                $language=addslashes(strip_tags($this->input->post('language', true)));
                $slogan=addslashes(strip_tags($this->input->post('slogan', true)));
                $product_name=addslashes(strip_tags($this->input->post('product_name', true)));
                $product_short_name=addslashes(strip_tags($this->input->post('product_short_name', true)));
                $backup_mode=addslashes(strip_tags($this->input->post('backup_mode', true)));
                $number_of_message_to_be_sent_in_try=addslashes(strip_tags($this->input->post('number_of_message_to_be_sent_in_try', true)));
                $update_report_after_time=addslashes(strip_tags($this->input->post('update_report_after_time', true)));
                $theme=addslashes(strip_tags($this->input->post('theme', true)));

                $auto_reply_delay_time=addslashes(strip_tags($this->input->post('auto_reply_delay_time', true)));
                $auto_reply_campaign_live_duration=addslashes(strip_tags($this->input->post('auto_reply_campaign_live_duration', true)));
                
                
                $master_password=addslashes(strip_tags($this->input->post('master_password', true)));


                $email_sending_option=addslashes(strip_tags($this->input->post('email_sending_option', true)));
                $auto_reply_campaign_per_cron_job=addslashes(strip_tags($this->input->post('auto_reply_campaign_per_cron_job', true)));
                $number_of_old_comment_reply=addslashes(strip_tags($this->input->post('number_of_old_comment_reply', true)));
                $autoreply_renew_access=addslashes(strip_tags($this->input->post('autoreply_renew_access', true)));
                $developer_access=addslashes(strip_tags($this->input->post('developer_access', true)));
                $force_https=addslashes(strip_tags($this->input->post('force_https', true)));
                $enable_support=addslashes(strip_tags($this->input->post('enable_support', true)));
                $enable_signup_form=addslashes(strip_tags($this->input->post('enable_signup_form', true)));

                $facebook_poster_image_upload_limit=addslashes(strip_tags($this->input->post('facebook_poster_image_upload_limit', true)));
                $facebook_poster_video_upload_limit=addslashes(strip_tags($this->input->post('facebook_poster_video_upload_limit', true)));
                $autoreply_image_upload_limit=addslashes(strip_tags($this->input->post('autoreply_image_upload_limit', true)));
                $autoreply_video_upload_limit=addslashes(strip_tags($this->input->post('autoreply_video_upload_limit', true)));
                $comboposter_image_upload_limit=addslashes(strip_tags($this->input->post('comboposter_image_upload_limit', true)));
                $comboposter_video_upload_limit=addslashes(strip_tags($this->input->post('comboposter_video_upload_limit', true)));
                $vidcaster_image_upload_limit=addslashes(strip_tags($this->input->post('vidcaster_image_upload_limit', true)));
                $vidcaster_video_upload_limit=addslashes(strip_tags($this->input->post('vidcaster_video_upload_limit', true)));
                $messengerbot_image_upload_limit=addslashes(strip_tags($this->input->post('messengerbot_image_upload_limit', true)));
                $messengerbot_video_upload_limit=addslashes(strip_tags($this->input->post('messengerbot_video_upload_limit', true)));
                $messengerbot_audio_upload_limit=addslashes(strip_tags($this->input->post('messengerbot_audio_upload_limit', true)));
                $messengerbot_file_upload_limit=addslashes(strip_tags($this->input->post('messengerbot_file_upload_limit', true)));

                $base_path=realpath(APPPATH . '../assets/images');

                $this->load->library('upload');

                if ($_FILES['logo']['size'] != 0) {
                    $photo = "logo.png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '200',
                        'max_width' => '600',
                        'max_height' => '300'
                        );
                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logo_error', $this->upload->display_errors());
                        return $this->configuration();
                    }
                }

                if ($_FILES['favicon']['size'] != 0) {
                    $photo = "favicon.png";
                    $config2 = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '50',
                        'max_width' => '32',
                        'max_height' => '32'
                        );
                    $this->upload->initialize($config2);
                    $this->load->library('upload', $config2);

                    if (!$this->upload->do_upload('favicon')) {
                        $this->session->set_userdata('favicon_error', $this->upload->display_errors());
                        return $this->configuration();  
                    }
                }

                // writing application/config/my_config
                $app_my_config_data = "<?php ";
                $app_my_config_data.= "\n\$config['default_page_url'] = '".$this->config->item('default_page_url')."';\n";
                $app_my_config_data.= "\$config['product_version'] = '".$this->config->item('product_version')."';\n\n";
                $app_my_config_data.= "\$config['institute_address1'] = '$institute_name';\n";
                $app_my_config_data.= "\$config['institute_address2'] = '$institute_address';\n";
                $app_my_config_data.= "\$config['institute_email'] = '$institute_email';\n";
                $app_my_config_data.= "\$config['institute_mobile'] = '$institute_mobile';\n\n";
                $app_my_config_data.= "\$config['slogan'] = '$slogan';\n";
                $app_my_config_data.= "\$config['product_name'] = '$product_name';\n";
                $app_my_config_data.= "\$config['product_short_name'] = '$product_short_name';\n\n";
                $app_my_config_data.= "\$config['developed_by'] = '".$this->config->item('developed_by')."';\n";
                $app_my_config_data.= "\$config['developed_by_href'] = '".$this->config->item('developed_by_href')."';\n";
                $app_my_config_data.= "\$config['developed_by_title'] = '".$this->config->item('developed_by_title')."';\n";
                $app_my_config_data.= "\$config['developed_by_prefix'] = '".$this->config->item('developed_by_prefix')."' ;\n";
                $app_my_config_data.= "\$config['support_email'] = '".$this->config->item('support_email')."' ;\n";
                $app_my_config_data.= "\$config['support_mobile'] = '".$this->config->item('support_mobile')."' ;\n";                
                $app_my_config_data.= "\$config['time_zone'] = '$time_zone';\n";                
                $app_my_config_data.= "\$config['language'] = '$language';\n";
                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";
                if($number_of_message_to_be_sent_in_try!="")
                $app_my_config_data.= "\$config['number_of_message_to_be_sent_in_try'] = $number_of_message_to_be_sent_in_try;\n";
                if($update_report_after_time!="")
                $app_my_config_data.= "\$config['update_report_after_time'] = $update_report_after_time;\n";
                $app_my_config_data.= "\$config['theme'] = '".$theme."';\n";
                
                $app_my_config_data.= "\$config['auto_reply_delay_time'] = '".$auto_reply_delay_time."';\n";
                $app_my_config_data.= "\$config['auto_reply_campaign_live_duration'] = '".$auto_reply_campaign_live_duration."';\n";

                if($master_password=='******')
                $app_my_config_data.= "\$config['master_password'] = '".$this->config->item("master_password")."';\n";
                else if($master_password=='')
                $app_my_config_data.= "\$config['master_password'] = '';\n";
                else $app_my_config_data.= "\$config['master_password'] = '".md5($master_password)."';\n";
           
                $app_my_config_data.= "\$config['email_sending_option'] = '".$email_sending_option."';\n";

                $app_my_config_data.= "\$config['auto_reply_campaign_per_cron_job'] = ".$auto_reply_campaign_per_cron_job.";\n";
                $app_my_config_data.= "\$config['number_of_old_comment_reply'] = ".$number_of_old_comment_reply.";\n";
                $app_my_config_data.= "\$config['autoreply_renew_access'] = '".$autoreply_renew_access."';\n";
                if($developer_access == 'yes') $developer_access = '1'; else $developer_access = '0';
                $app_my_config_data.= "\$config['developer_access'] = '".$developer_access."';\n";

                $app_my_config_data.= "\$config['force_https'] = '".$force_https."';\n";
                $app_my_config_data.= "\$config['enable_support'] = '".$enable_support."';\n";
                $app_my_config_data.= "\$config['enable_signup_form'] = '".$enable_signup_form."';\n";

                $app_my_config_data.= "\$config['facebook_poster_image_upload_limit'] = ".$facebook_poster_image_upload_limit.";\n";
                $app_my_config_data.= "\$config['facebook_poster_video_upload_limit'] = ".$facebook_poster_video_upload_limit.";\n";

                if($this->basic->is_exist("add_ons",array("project_id"=>2)))
                {                	
	                $app_my_config_data.= "\$config['autoreply_image_upload_limit'] = ".$autoreply_image_upload_limit.";\n";
	                $app_my_config_data.= "\$config['autoreply_video_upload_limit'] = ".$autoreply_video_upload_limit.";\n";
                }

                if($this->basic->is_exist("add_ons",array("project_id"=>20)))
                {                	
	                $app_my_config_data.= "\$config['comboposter_image_upload_limit'] = ".$comboposter_image_upload_limit.";\n";
	                $app_my_config_data.= "\$config['comboposter_video_upload_limit'] = ".$comboposter_video_upload_limit.";\n";
                }

                if($this->basic->is_exist("add_ons",array("project_id"=>21)))
                {                	
	                $app_my_config_data.= "\$config['vidcaster_image_upload_limit'] = ".$vidcaster_image_upload_limit.";\n";
	                $app_my_config_data.= "\$config['vidcaster_video_upload_limit'] = ".$vidcaster_video_upload_limit.";\n";
                }

                if($this->basic->is_exist("add_ons",array("project_id"=>3)))
                {
	                $app_my_config_data.= "\$config['messengerbot_image_upload_limit'] = ".$messengerbot_image_upload_limit.";\n";
	                $app_my_config_data.= "\$config['messengerbot_video_upload_limit'] = ".$messengerbot_video_upload_limit.";\n";
	                $app_my_config_data.= "\$config['messengerbot_audio_upload_limit'] = ".$messengerbot_audio_upload_limit.";\n";
	                $app_my_config_data.= "\$config['messengerbot_file_upload_limit'] = ".$messengerbot_file_upload_limit.";\n";                	
                }

                file_put_contents(APPPATH.'config/my_config.php', $app_my_config_data, LOCK_EX);                  //writting  application/config/my_config
                
                if($backup_mode == 'yes') $mode_to_write = 1;
                else $mode_to_write = 0;   
                // mostofa
                $admin_info = $this->basic->get_data("users",array("where"=>array('user_type'=>'Admin')),array('id'));
                $admin_ids = array();
                foreach($admin_info as $value)
                {
                    array_push($admin_ids, $value['id']);
                }

                $admin_app_info = $this->basic->get_data("facebook_rx_config",array("where_in"=>array("user_id"=>$admin_ids)),array("id"));
                $admin_app_ids = array();
                foreach($admin_app_info as $apps)
                {
                    array_push($admin_app_ids, $apps['id']);
                }

                if($mode_to_write == 1)
                {
                    if(!empty($admin_app_ids))
                    {
                        $this->db->where_in('facebook_rx_config_id', $admin_app_ids);
                        $this->db->where_not_in('user_id', $admin_ids);
                        $this->db->update("facebook_rx_fb_user_info",array("need_to_delete"=>"1"));
                    }
                }
                else
                {
                    if(!empty($admin_app_ids))
                    {
                        $this->db->where_in('facebook_rx_config_id', $admin_app_ids);
                        $this->db->where_not_in('user_id', $admin_ids);
                        $this->db->update("facebook_rx_fb_user_info",array("need_to_delete"=>"0"));
                    }
                }
                // mostofa

                // Messenger Bot
                if($this->basic->is_exist("modules",$where=array('id'=>200)))
                {                    
                    $admin_app_info = $this->basic->get_data("messenger_bot_config",array("where_in"=>array("user_id"=>$admin_ids)),array("id"));
                    $admin_app_ids = array();
                    foreach($admin_app_info as $apps)
                    {
                        array_push($admin_app_ids, $apps['id']);
                    }

                    if($mode_to_write == 1)
                    {
                        if(!empty($admin_app_ids))
                        {
                            $this->db->where_in('messenger_bot_config_id', $admin_app_ids);
                            $this->db->where_not_in('user_id', $admin_ids);
                            $this->db->update("messenger_bot_user_info",array("need_to_delete"=>"1"));
                        }
                    }
                    else
                    {
                        if(!empty($admin_app_ids))
                        {
                            $this->db->where_in('messenger_bot_config_id', $admin_app_ids);
                            $this->db->where_not_in('user_id', $admin_ids);
                            $this->db->update("messenger_bot_user_info",array("need_to_delete"=>"0"));
                        }
                    }
                }
                // Messenger Bot

                $app_package_config_data = "<?php ";
                $app_package_config_data.= "\n\$config['backup_mode'] = '$mode_to_write';\n";
                file_put_contents(APPPATH.'config/package_config.php', $app_package_config_data, LOCK_EX); 
                $this->session->unset_userdata("selected_language");

              
                $this->session->set_flashdata('success_message', 1);
                redirect('admin_config/configuration', 'location');
            }
        }
    }

    public function analytics_config()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
           redirect('home/login_page', 'location');
        }
        
        $data['body'] = "admin/config/analytics_config";
        $data['page_title'] = $this->lang->line('analytics settings');
        $this->_viewcontroller($data);
    }


    public function analytics_config_action()
    {
        $this->load->helper('file');
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata("user_type")!="Admin"){
            redirect('home/access_forbidden', 'location');
        } 
        
        $pixel_code = $this->input->post('pixel_code');
        $google_code = $this->input->post('google_code');

        file_put_contents(APPPATH.'views/include/fb_px.php', $pixel_code, LOCK_EX);
        file_put_contents(APPPATH.'views/include/google_code.php', $google_code, LOCK_EX);

        $this->session->set_flashdata('success_message', 1);
        redirect('admin_config/analytics_config','location');
    }


    public function purchase_code_configuration()
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin'){
            redirect('home/login_page', 'location');
        }
        $data['body'] = "admin/config/edit_purchase_code_config";
        $data['time_zone'] = $this->_time_zone_list();
        $data['page_title'] = $this->lang->line('purchase code settings');
        $this->_viewcontroller($data);
    }

    public function edit_purchase_code_config()
    {   
       
    }


// ======================= Frontend settings ===============================

    public function frontend_configuration()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
           redirect('home/login_page', 'location');
        }
        
        $data['body'] = "admin/config/frontend_config";
        $data['time_zone'] = $this->_time_zone_list();        
        $data['language_info'] = $this->_language_list();
        $data['page_title'] = $this->lang->line('front-end settings');
        $this->_viewcontroller($data);
    }


    public function frontend_configuration_action()
    {        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') { redirect('home/access_forbidden', 'location'); }

        if ($_POST) 
        {
            $post=$_POST;
            foreach ($post as $key => $value) 
            {
                $$key = addslashes(strip_tags($this->input->post($key,TRUE)));
            }


            //review section
            $customer_review = array();
            $total_item      = $this->config->item('customer_review');

            $review_string = "array".'('."\n";

            for($i = 1; $i <= count($total_item); $i++) {
                $j = $i-1;
                $var1 = "reviewer".$i;
                $var2 = "designation".$i;
                $var3 = "pic".$i;
                $var4 = "description".$i;

                $customer_review[$j][$var1] = $$var1;
                $customer_review[$j][$var2] = $$var2;
                $customer_review[$j][$var3] = $$var3;
                $customer_review[$j][$var4] = $$var4;

                $review_string.= "   "."'{$j}'=> array(\n"."       "."'".$$var1."',\n"."       "."'".$$var2."',\n"."       "."'".$$var3."',\n"."       "."'".$$var4."',\n"."    "."),\n";
            }

            $review_string.=")";

            // video section
            $custom_video = array();
            $total_video  = $this->config->item('custom_video');

            $video_string = "array".'('."\n";

            for($i = 1; $i <= count($total_video); $i++) {

                $j = $i-1;
                $var1 = "thumbnail".$i;
                $var2 = "title".$i;
                $var3 = "video_url".$i;

                $custom_video[$j][$var1] = $$var1;
                $custom_video[$j][$var2] = $$var2;
                $custom_video[$j][$var3] = $$var3;

                $video_string.= "   "."'{$j}'=>array(\n"."     "."'".$$var1."',\n"."     "."'".$$var2."',\n"."     "."'".$$var3."',\n"."   "."),\n"; 
            }

            $video_string.= "\n)";

            
            // writing application/config/my_config
            $app_frontend_config_data = "<?php ";
            $app_frontend_config_data.= "\n\$config['theme_front'] = '".$theme_front."';\n";
            $app_frontend_config_data.= "\$config['display_landing_page'] = '".$display_landing_page."';\n";
            $app_frontend_config_data.= "\$config['facebook'] = '$facebook_link';\n";
            $app_frontend_config_data.= "\$config['twitter'] = '$twitter_link';\n";
            $app_frontend_config_data.= "\$config['linkedin'] = '$linkedin_link';\n";
            $app_frontend_config_data.= "\$config['youtube'] = '$youtube_link';\n";
            $app_frontend_config_data.= "\$config['display_review_block'] = '$display_review_block';\n";
            $app_frontend_config_data.= "\$config['display_video_block'] = '$display_video_block';\n";
            $app_frontend_config_data.= "\$config['promo_video'] = '$promo_video';\n";
            $app_frontend_config_data.= "\$config['customer_review_video'] = '$customer_review_video';\n";
            $app_frontend_config_data.= "\$config['customer_review'] = ".$review_string.";\n";
            $app_frontend_config_data.= "\n\$config['custom_video'] = ".$video_string.";\n";

            file_put_contents(APPPATH.'config/frontend_config.php', $app_frontend_config_data, LOCK_EX);
            $this->session->set_flashdata('success_message', 1);
            redirect('admin_config/frontend_configuration', 'location');


            




        }
    }








    
}
