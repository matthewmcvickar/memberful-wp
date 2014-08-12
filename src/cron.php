<?php

if ( ! wp_next_scheduled( 'memberful_wp_cron_sync' ) ) {
	  wp_schedule_event( time(), 'twicedaily', 'memberful_wp_cron_sync' );
}

add_action( 'memberful_wp_cron_sync', 'memberful_wp_cron_sync_users' );
add_action( 'memberful_wp_cron_sync', 'memberful_wp_cron_sync_entities' );

function memberful_wp_cron_sync_users() {
	set_time_limit( 0 );

	$members_to_sync = Memberful_User_Mapping_Repository::fetch_ids_of_members_that_need_syncing();
	$mapper = new Memberful_User_Map();

	echo "<pre>library=memberful_wp fn=memberful_wp_cron_sync_users at=start members=".count($members_to_sync)."\n</pre>";

	foreach ( $members_to_sync as $member_id ) {

		memberful_wp_sync_member_from_memberful( $member_id );

		sleep(1);
	}

	echo "<pre>library=memberful_wp method=memberful_wp_cron_sync_users at=finish\n</pre>";
}

function memberful_wp_cron_sync_entities() {
	set_time_limit( 0 );

	echo "<pre>library=memberful_wp method=memberful_wp_cron_sync_entities at=start\n</pre>";

	memberful_wp_sync_downloads();
	memberful_wp_sync_subscription_plans();

	echo "<pre>library=memberful_wp method=memberful_wp_cron_sync_entities at=finish\n</pre>";
}
