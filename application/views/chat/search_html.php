 <?php if(isset($messages) && $messages) : ?>
        <div class="row">                                   
            <?php
                $users = array();
                $lastChatUserId;
                foreach($messages as $item) : 
                    if(!isset($users['first'])){
                        $users['first'] = $item->user_id;
                    } else if(!isset($users['second'])) {
                        $users['second'] = $item->user_id;
                    }
                    if(empty($lastChatUserId)) :
            ?>
                        <div class="col-md-12 <?php echo $item->user_id == $users['first'] ? '' : 'text-right' ?>"  style="margin-bottom: 15px;">
                            <strong class="muname"><?php echo ucwords($item->uname); ?></strong>
                            <div class="messageBox">
                                <div class="row">
                                    <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                    <div class="col-md-12 text-right mtime"><?php echo $item->mtime; ?></div>
                                </div>
                            </div>
                            <div class="mdelete-div">
                                <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                            </div>
                            <div class="clearfix"></div>
                    <?php $lastChatUserId = $item->user_id;
                        elseif($lastChatUserId == $item->user_id):  ?>
                            <?php if($item->user_id == $users['first']) : ?>
                                <div class="messageBox">
                                    <div class="row">
                                        <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                        <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                    </div>
                                </div>
                                <div class="mdelete-div">
                                    <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                </div>
                            <?php else : ?>
                                <div class="mdelete-div">
                                    <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                </div>
                                <div class="messageBox">
                                    <div class="row">
                                        <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                        <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="clearfix"></div>
                    <?php  elseif($lastChatUserId != $item->user_id): ?>
                        </div>
                        <div class="col-md-12 <?php echo $item->user_id == $users['first'] ? '' : 'text-right' ?>"  style="margin-bottom: 15px;">
                            <strong class="muname"><?php echo ucwords($item->uname); ?></strong><br/>
                            <?php if($item->user_id == $users['first']) : ?>
                                <div class="messageBox">
                                    <div class="row">
                                        <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                        <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                    </div>
                                </div>
                                <div class="mdelete-div">
                                    <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                </div>
                            <?php else : ?>
                                <div class="mdelete-div">
                                    <a href="javascript:void(0)" class="btn btn-danger mdelete-icon delete_msg" data-id="<?php echo $item->id; ?>" data-chat_id="<?php echo $item->chat_id; ?>"><i class="fa fa-trash"></i></a>
                                </div>
                                <div class="messageBox">
                                    <div class="row">
                                        <div class="col-md-12"><span style="word-break: break-all;"><?php echo $item->msg; ?></span></div>
                                        <div class="col-md-12 mtime <?php echo $item->user_id == $users['first'] ? 'text-right' : 'text-left' ?>"><?php echo $item->mtime; ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="clearfix"></div>   
                    <?php $lastChatUserId = $item->user_id;
                    endif; ?>
            <?php endforeach;?>
        </div>
    </div>
<?php endif; ?>