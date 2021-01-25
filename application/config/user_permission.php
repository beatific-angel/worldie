<?php
/* 
 * contants and lists reused accross application
 */

//return [

    /*
     * User roles - the user roles ids are furthermore used in the user_roles_permissions array defined bellow
     */
	 
    $config['user_roles'] = [
        1 => 'Super Admin',
        2 => 'Admin',
	    3 => 'Editor',
        4 => 'Moderators',
	    5 => 'Members',
    ];
	
    /*
     * The user roles ids specified in each action(add, view, edit, delete) of each section, 
     * allow them to access the described action (has access to add, view, edit, delete the specified section)
     * 
     * user_roles_permissions[
     *    section => [
     *        action => [user_roles_ids]
     *    ]
     * ]
     */
	 
    $config['user_roles_permissions'] = [
        'users' => [
		    'admin_create' => [1,2,4],
            'index' => [1,2,3],
			'admin_user' => [1,2,3,4],
			'site_user' => [1,2,3],
            'edit_admin' => [1,2,3,4],
			'admin_delete' => [1,2,3,4],
        ],
		'category' => [
		    'create' => [1,2,3,4],
            'shop_category' => [1,2,3,4],
            'store_category' => [1,2,3,4],
			'art_category' => [1,2,3,4],
			'event_category' => [1,2,3,4],
			'media_category' => [1,2,3,4],
            'media_channel_category'=> [1,2,3,4],
            'art_category' => [1,2,3,4],
            'art_wall_category'=> [1,2,3,4],
			'meet_date_category' => [1,2,3,4],
			'change_status' => [1,2,3,4],
            'edit' => [1,2,3,4],
			'delete_category' => [1,2,3,4],
        ],
        'reportedcontent' => [
            'index' => [1,2,3,4],
            'reported_item' => [1,2,3,4],
            'edit' => [1],
            'delete' => [1],
            'change_status' => [1,2,3,4],
			'review' => [1,2,3,4],
        ],
		'sponseredcontent' => [
            'index' => [1,2,3,4],
            'sponsered_item' => [1,2,3,4],
            'edit' => [1],
            'delete' => [1],
        ],
        'advertisement' => [
            'index' => [1,2,3,4],
            'advertisement_list' => [1,2,3,4],
            'change_status' => [1,2,3,4],
            'delete_Advertisement' => [1],
        ],
        'allcontents' => [
            'post'  => [1,2,3,4],
            'media' => [1,2,3,4],
            'event' => [1,2,3,4],
            'wall_art' => [1,2,3,4],
            'advertisement_list' => [1,2,3,4],
            'change_status' => [1,2,3,4],
            'delete_Advertisement' => [1],
        ],
    ];
    
    /*
	 *
     * Permissions error messages
     */
	 
    $config['permissions_errors'] = [
        'section' => "You don't have permission to access this section!",
        'action' => "You don't have permission to do that!",
    ];
	
	 /*
	 *
     * Content that has been reported
     */
	 
$config['content_type'] = [
        1 => 'Post',
	    2 => 'Event',
        3 => 'Post_Comment',
        4 => 'Image_comment',
        5 => 'Event_Comment',
        6 => 'User_Profile',
        7 => 'Media_video',
        8 => 'Media_video_comment',
        9 => 'Media_Channel',
        10 => 'Art_Wall',
        11 => 'Wall_Art',
        12 => 'Wall_Art_Comment',
        13 => 'Advertisement',
        14 => 'Product',
        15 => 'Store',
        16 => 'Group',
        17 => 'Page',
        18 => 'Group_Post',
        19 => 'Page_Post',
        20 => 'Store_Product'
     ];
     
    $config['reporting_content'] = [
        1 => 'posts',
        2 => 'events',
        3 => 'post_comment',
        4 => 'image_comment',
        5 => 'event_comment',
        6 => 'users',
        7 => 'media_video',
        8 => 'media_video_comment',
        9 => 'media_channel',
        10 => 'art_wall',
        11 => 'wall_arts',
        12 => 'wall_arts_comment',
        13 => 'advertisement',
        // not made reporting content after this
        14 => 'store_products',
        15 => 'store_product_comment',
        16 => 'store',
        17 => 'groups',
        18 => 'group_comment',
        19 => 'group_posts',
        20 => 'group_post_comment',
    ];
    
     $config['ss_content_type'] = [
        'posts'          => 1,
        'events'         => 2,
        'media_video'    => 3,
        'wall_arts'      => 4,
        'store_products' => 5,
    ];
	 /*
	 *
     * Sponsered Content array
     */
	 
	$config['sponsered_content_type'] = [
       1 => 'Post',
	   2 => 'Advertisement',
	   3 => 'Image',
	   4 => 'Video',
	   5 => 'Video channel' 
    ];

    /*
     *
     * Pages array
     */
     $config['page_type'] = [
       1 => 'terms',
       
    ];


    $config['comment_type'] = [
        'comment'       => 1,
        'comment_reply' => 2,
        'reply_reply'   => 3,
    ];
    
//];

