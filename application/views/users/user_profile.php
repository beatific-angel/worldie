<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('elements/header'); ?>

<!-- page content -->
<?php $base_url_front = $this->config->item('base_url_front'); ?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>User Profile Information</h3>
            </div>
        </div>

        <div class="clearfix"></div>
        <!--Display Any Error or Success Message-->
            <?php 
                if($this->session->flashdata('success')){
                    $msg['msg'] = $this->session->flashdata('success');
                    $this->load->view('flash_messages/success', $msg);
                }else if($this->session->flashdata('error')){
                    $msg['msg'] = $this->session->flashdata('error');
                    $this->load->view('flash_messages/error', $msg);
                } 
            ?>
            <div class="ajax_msg"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="col-md-3"><h2><a href="javascript:void(0);" class="view_profile_item" item_type="profile_image" item_id="<?php echo $user_id; ?>">Profile Photos</a></h2></div>
                        <div class="col-md-3"><h2><a href="javascript:void(0);" class="view_profile_item" item_type="banner_image" item_id="<?php echo $user_id; ?>">Banners</a></h2></div>
                        <div class="col-md-3"><h2><a href="javascript:void(0);" class="view_profile_item" item_type="photo" item_id="<?php echo $user_id; ?>">Photos</a></h2></div>
                        <div class="col-md-3"><h2><a href="javascript:void(0);" class="view_profile_item" item_type="video" item_id="<?php echo $user_id; ?>">Videos</a></h2></div>
                    </div>
                    <div class="x_panel">
                        <form action="<?php echo base_url().'users/deleteUserData'; ?>" name="gform" id="gform" method="post">
                            <input type="hidden" name="type" value="groups">
                            <input type="hidden" name="userId" value="<?php echo $user_id; ?>">
                            <div class="row x_title">
                                <div class="col-md-10"><h2>Groups</h2></div>
                                <div class="col-md-2"><button class="btn btn-danger" type="submit">Delete Selected Group</button></div>
                            </div>
                            <?php if($groups) : 
                                    foreach($groups as $key => $item) :
                                        $isAdmin = $item->createdBy == $item->userId ? ' (Admin)' : '';
                                        $value = $item->guId.'_'.$item->id;
                                        echo '<p class="col-md-4"><input type="checkbox" name="groups[]" value="'.$value.'" class="gcheck"> '.$item->name.$isAdmin.'</p>';
                                    endforeach;
                                else :
                                    echo '<p>No Groups Available.</p>';
                            endif; ?>
                        </form>
                    </div>
                    <div class="x_panel">
                        <form action="<?php echo base_url().'users/deleteUserData'; ?>" name="pform" id="pform" method="post">
                            <input type="hidden" name="type" value="pages">
                            <input type="hidden" name="userId" value="<?php echo $user_id; ?>">
                            <div class="row x_title">
                                <div class="col-md-10"><h2>Pages</h2></div>
                                <div class="col-md-2"><button class="btn btn-danger" type="submit">Delete Selected Page</button></div>
                            </div>
                            <?php if($pages) : 
                                    foreach($pages as $item) :
                                        $isAdmin = $item->createdBy == $item->userId ? ' (Admin)' : '';
                                        $value = $item->puId.'_'.$item->id;
                                        echo '<p class="col-md-4"><input type="checkbox" name="pages[]" value="'.$value.'" class="pcheck"> '.$item->name.$isAdmin.'</p>';
                                    endforeach;
                                else :
                                    echo '<p>No Pages Available.</p>';
                            endif; ?>
                        </form>
                    </div>
                    <div class="x_panel">
                        <form action="<?php echo base_url().'users/deleteUserData'; ?>" name="cform" id="cform" method="post">
                            <input type="hidden" name="type" value="channels">
                            <input type="hidden" name="userId" value="<?php echo $user_id; ?>">
                            <div class="row x_title">
                                <div class="col-md-10"><h2>Channels (Click on channel to view detail)</h2></div>
                                <div class="col-md-2"><button class="btn btn-danger" type="submit">Delete Selected Channel</button></div>
                            </div>
                            <?php if($channels) : 
                                    foreach($channels as $item) :
                                        $isAdmin = $item->createdBy == $user_id ? ' (Admin)' : '';

                                        $url = $base_url_front.'media/channel/'.$item->id;
                                        $value = $item->muId.'_'.$item->id;
                                        echo '<p class="col-md-4"><a href="'.$url.'" target="_blank"><input type="checkbox" name="channels[]" value="'.$value.'" class="ccheck"> '.$item->name.$isAdmin.'</a></p>';
                                    endforeach;
                                else :
                                    echo '<p>No Channels Available.</p>';
                            endif; ?>
                        </form>
                    </div>
                    <div class="x_panel">
                        <form action="<?php echo base_url().'users/deleteUserData'; ?>" name="wform" id="wform" method="post">
                            <input type="hidden" name="type" value="walls">
                            <input type="hidden" name="userId" value="<?php echo $user_id; ?>">
                            <div class="row x_title">
                                <div class="col-md-10"><h2>Walls (Click on wall to view detail)</h2></div>
                                <div class="col-md-2"><button class="btn btn-danger" type="submit">Delete Selected Wall</button></div>
                            </div>
                            <?php if($walls) : 
                                    foreach($walls as $item) :
                                        $isAdmin = isset($item->auId) ? ''  : ' (Admin)';
                                        $value = $item->auId.'_'.$item->id;
                                        echo '<p class="col-md-4"><input type="checkbox" name="walls[]" value="'.$value.'" class="wcheck"> <a href="javascript:void(0);" class="view_profile_item" item_type="wall" item_id="'.$item->id.'">'.$item->name.$isAdmin.'</a></p>';
                                    endforeach;
                                else :
                                    echo '<p>No Walls Available.</p>';
                            endif; ?>
                        </form>
                    </div>
                    <div class="x_panel">
                        <form action="<?php echo base_url().'users/deleteUserData'; ?>" name="sform" id="sform" method="post">
                            <input type="hidden" name="type" value="stores">
                            <input type="hidden" name="userId" value="<?php echo $user_id; ?>">
                            <div class="row x_title">
                                <div class="col-md-10"><h2>Stores</h2></div>
                                <div class="col-md-2"><button class="btn btn-danger" type="submit">Delete Selected Store</button></div>
                            </div>
                            <?php if($stores) : 
                                    foreach($stores as $item) :
                                        $isAdmin = isset($item->suId) ? ''  : ' (Admin)';
                                        $value = $item->suId.'_'.$item->id;
                                        echo '<p class="col-md-4"><input type="checkbox" name="stores[]" value="'.$value.'" class="scheck"> '.$item->name.$isAdmin.'</p>';
                                    endforeach;
                                else :
                                    echo '<p>No Stores Available.</p>';
                            endif; ?>
                        </form>
                    </div>
                </div>        
            </div>
    </div>
</div>
<!-- /page content -->
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('submit', '#gform', function(e){
        if (!$('.gcheck').is(':checked')) {
            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">×</span></button>'+
                    '<strong>Please select atleast one group.</strong> </div>';
            $('.ajax_msg').html(msg);  
            //prevent the default form submit if it is not checked
            e.preventDefault();
        }
      });
    $(document).on('submit', '#pform', function(e){
        if (!$('.pcheck').is(':checked')) {
            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">×</span></button>'+
                    '<strong>Please select atleast one page.</strong> </div>';
            $('.ajax_msg').html(msg);  
            //prevent the default form submit if it is not checked
            e.preventDefault();
        }
      });

    $(document).on('submit', '#cform', function(e){
        if (!$('.ccheck').is(':checked')) {
            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">×</span></button>'+
                    '<strong>Please select atleast one channel.</strong> </div>';
            $('.ajax_msg').html(msg);  
            //prevent the default form submit if it is not checked
            e.preventDefault();
        }
      });

      $(document).on('submit', '#wform', function(e){
        if (!$('.wcheck').is(':checked')) {
            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">×</span></button>'+
                    '<strong>Please select atleast one wall.</strong> </div>';
            $('.ajax_msg').html(msg);  
            //prevent the default form submit if it is not checked
            e.preventDefault();
        }
      });

    $(document).on('submit', '#sform', function(e){
        if (!$('.scheck').is(':checked')) {
            msg = '<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">×</span></button>'+
                    '<strong>Please select atleast one store.</strong> </div>';
            $('.ajax_msg').html(msg);  
            //prevent the default form submit if it is not checked
            e.preventDefault();
        }
      });
    $('body').on('click', '.view_profile_item', function () {
		//$('.view_reported').click(function(){
            var item_type = $(this).attr('item_type');
            var item_id   = $(this).attr('item_id');
            if(item_type != '' && item_id != ''){
                    $.facebox($.get('<?php echo base_url();?>users/item_view?item_type='+item_type+'&item_id='+item_id,
                    function(data) { 
                            $.facebox(data);
                            })
                    )
            }
    });
    jQuery(document).keydown(function (event) {
        if (event.keyCode == 27) {
            event.preventDefault();
        }
    });
    $(document).bind('close.facebox', function() {
        location.reload();
    });
//    $(document).bind('close.facebox', function() {
//        alert('test');
//        location.reload();
////        var videoList = document.getElementsByTagName("video");
////        for (var i = 0; i < videoList.length; i++) {
////            videoList[i].pause();
////        }
//    });
});
</script>
<?php $this->load->view('elements/footer'); ?>
