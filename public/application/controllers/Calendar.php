<?php

require_once("Home.php"); // loading home controller

class calendar extends Home
{

    public $user_id;

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
        if($this->session->userdata('user_type') != 'Admin' && !in_array(76,$this->module_access))
        redirect('home/login_page', 'location');
        $this->user_id=$this->session->userdata('user_id');

        if($this->session->userdata("facebook_rx_fb_user_info")==0)
        redirect('facebook_rx_account_import/index','refresh');

        $this->load->library("fb_rx_login");
        // $this->important_feature();
        // $this->member_validity();
    }



    public function index()
    {
      $this->full_calendar();
    }

    public function full_calendar()
    {
         /**
         * Bulk Message Campaign
         * @var array
         */
         $select =array("facebook_ex_conversation_campaign.id","facebook_ex_conversation_campaign.posting_status","facebook_ex_conversation_campaign.schedule_time","facebook_ex_conversation_campaign.campaign_type","facebook_ex_conversation_campaign.time_zone","facebook_ex_conversation_campaign.campaign_name","facebook_ex_conversation_campaign.total_thread","facebook_ex_conversation_campaign.added_at");
         $table1 = $this->basic->get_data('facebook_ex_conversation_campaign',array('where'=>array('user_id'=>$this->user_id)),$select=$select);
        // $data['info'] = $this->basic->get_data('facebook_ex_conversation_campaign',$where='',$select=$select,$join='',$limit='', $start='',$order_by='');

        /**
         * facebook poster text,image,link,video
         * @var array
         */
        $select = array("facebook_rx_auto_post.id","facebook_rx_auto_post.post_type","facebook_rx_auto_post.schedule_time","facebook_rx_auto_post.time_zone","facebook_rx_auto_post.posting_status","facebook_rx_auto_post.campaign_name","facebook_rx_auto_post.page_or_group_or_user_name","facebook_rx_auto_post.last_updated_at");
        $table2 = $this->basic->get_data('facebook_rx_auto_post',array('where'=>array('user_id'=>$this->user_id)),$select=$select);


        /**
         *Facebook CTA poster
         * @var array
         */
        $select= array("facebook_rx_cta_post.id","facebook_rx_cta_post.posting_status","facebook_rx_cta_post.time_zone","facebook_rx_cta_post.schedule_time","facebook_rx_cta_post.campaign_name","facebook_rx_cta_post.last_updated_at");
        $table3= $this->basic->get_data('facebook_rx_cta_post',array('where'=>array('user_id'=>$this->user_id)),$select=$select);
        /**
         *Facebook poster slider
         * @var array
         */
        $select = array("facebook_rx_slider_post.id","facebook_rx_slider_post.post_type","facebook_rx_slider_post.posting_status","facebook_rx_slider_post.schedule_time","facebook_rx_slider_post.time_zone","facebook_rx_slider_post.campaign_name","facebook_rx_slider_post.page_or_group_or_user_name","facebook_rx_slider_post.last_updated_at");
        $table4= $this->basic->get_data('facebook_rx_slider_post',array('where'=>array('user_id'=>$this->user_id)),$select=$select);


        $data['info'] = array_merge($table1,$table2,$table3,$table4);
        // echo "<pre>";
        // print_r($data['info']);
        // exit;

        foreach ($data['info'] as $key => $value) {
                    

                if( $value['schedule_time']!='0000-00-00 00:00:00')
                    $data['data'][$key]['start'] = $value['schedule_time']; 
                else{
                        if(isset($value['added_at']))
                            $data['data'][$key]['start'] = $value['added_at'];
                        elseif(isset($value['last_updated_at']))
                             $data['data'][$key]['start'] = $value['last_updated_at'];
                }
                     

                $posting_status = $value['posting_status'];
                $time_zone      = $value['time_zone'];

                $c_type = '';
                $edit_url = '';
                if(isset($value['campaign_type']) == "page-wise" || isset($value['campaign_type'])=="group-wise" || isset($value['campaign_type'])=="lead-wise" )
                    $c_type = $this->lang->line('bulk');

                else if(isset($value['post_type']) && $value['post_type'] == "text_submit")
                {   $c_type = $this->lang->line('text');
                    $edit_url = site_url('ultrapost/text_image_link_video_edit_auto_post/'.$value['id']);
                }
                else if(isset($value['post_type']) && $value['post_type'] == "link_submit")
                {    $c_type = $this->lang->line('link');
                     $edit_url = site_url('ultrapost/text_image_link_video_edit_auto_post/'.$value['id']);
                }
                else if(isset($value['post_type']) && $value['post_type']== "image_submit")
                {   
                    $c_type = $this->lang->line('image');
                    $edit_url = site_url('ultrapost/text_image_link_video_edit_auto_post/'.$value['id']);
                }
                else if(isset($value['post_type']) && $value['post_type'] == "video_submit")
                {
                    $c_type = $this->lang->line('video'); 
                    $edit_url = site_url('ultrapost/text_image_link_video_edit_auto_post/'.$value['id']);  
                }    
                else if(isset($value['post_type']) && $value['post_type'] == "carousel_post")
                {
                    $c_type = $this->lang->line('carousel');
                    $edit_url = site_url('ultrapost/edit_carousel_slider/'.$value['id']);  
                }    
                else if(isset($value['post_type']) && $value['post_type'] == "slider_post")
                {
                   $c_type = $this->lang->line('video slide');  
                   $edit_url = site_url('ultrapost/edit_carousel_slider/'.$value['id']); 
                }   
                else
                {
                    $c_type = $this->lang->line('cta');
                    $edit_url = site_url('ultrapost/edit_cta_post/'.$value['id']); 
                    
                }

                
                if(isset($value['campaign_type']) && $value['campaign_type']== "page-wise")
                    $edit_url = site_url('facebook_ex_campaign/edit_multipage_campaign/'.$value['id']);
                else if(isset($value['campaign_type']) && $value['campaign_type']== "group-wise")
                    $edit_url = site_url('facebook_ex_campaign/edit_multigroup_campaign/'.$value['id']);                
                else if(isset($value['campaign_type']) && $value['campaign_type']== "lead-wise")
                    $edit_url = site_url('facebook_ex_campaign/edit_custom_campaign/'.$value['id']);

                if( $posting_status == '2'){

                    $data['data'][$key]['title'] = $c_type." ".$this->lang->line("completed");
                    $data['data'][$key]['color'] = "#4CAF50";
                    if(isset($value['total_thread']))
                    {
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("number to send") ." : " .$value['total_thread'];
                    }
                    else if (isset($value['page_or_group_or_user_name'])){
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("page name") ." : " .$value['page_or_group_or_user_name'];
                    }
                    
                } 
                  
                else if( $posting_status == '1') 
                {
                    $data['data'][$key]['title'] = $c_type." ".$this->lang->line("processing");
                    $data['data'][$key]['color'] = "#ffc107";
                    if(isset($value['total_thread'])){
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("number to send") ." : " .$value['total_thread'];
                    }
                    else if(isset($value['page_or_group_or_user_name'])) {
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("page name") ." : " .$value['page_or_group_or_user_name'];
                    }
                   
                }
                
                else if( $posting_status == '3') 
                {
                    $data['data'][$key]['title'] = $c_type." ".$this->lang->line("stopped");
                    $data['data'][$key]['color'] = "#dc3545";
                    if(isset($value['total_thread'])){
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("number to send") ." : " .$value['total_thread'];
                    }
                    else if(isset($value['page_or_group_or_user_name'])){
                        $data['data'][$key]['description'] = $this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("completed"). " <br> ".$this->lang->line("page") ." : " .$value['page_or_group_or_user_name'];
                    }
                   
                }

                else 
                {
                    $data['data'][$key]['title'] = $c_type." ".$this->lang->line("pending");
                    $data['data'][$key]['description'] =$this->lang->line('campaign name')." : ".$value['campaign_name']." <br> ".$this->lang->line("type")." : ".$c_type ." <br> ".$this->lang->line('posting status')." : ".$this->lang->line("pending"). " <br> " .$this->lang->line("you can edit the campaign");
                    $data['data'][$key]['url']=$edit_url;
                    $data['data'][$key]['color'] = "#007bff";
                }

                // if($posting_status!='0' || $time_zone == "") 
                //     $data['data'][$key]['description'] = $this->lang->line("only pending campaigns are editable");



        }


            $data['body'] = "calendar/full_calendar";
            $data['page_title'] = $this->lang->line("activity calendar");
            $this->_viewcontroller($data);


    }

}
