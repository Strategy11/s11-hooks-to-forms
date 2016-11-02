<?php
/*
Plugin Name: Get all hooks into a form
Author: Strategy11
*/

function s11_hooks_to_forms( $file = 'formidable/classes' ) {
	$hooks = s11_htf_maybe_get_hooks( $file );
	foreach ( $hooks as $hook_group ) {
		//echo count( $hook_group ) .' found'; var_dump($hook_group);
		foreach ( $hook_group as $hook => $detail ) {
			s11_htf_loop( $hook, $detail );
		}
	}
}

function s11_htf_maybe_get_hooks( $file ) {
	$key = 's11_hooks' . sanitize_title( $file );
	$hooks = get_transient( $key );
	if ( ! $hooks ) {
		$hooks = s11_htf_get_hooks( $file );
		set_transient( $key, $hooks, 60 * 60 * 3 );
	}

	return $hooks;
}

function s11_htf_get_hooks( $file ) {
	require( dirname(dirname(__FILE__)) . '/wp-hook-documentor/class.wp-hook-documentor.php' );
	$file = dirname(dirname(__FILE__)) . '/' . $file;
	$hooks = new wp_hook_documentor( $file, 'name', 'php', 'return' );
	return $hooks->get_output();
}

function s11_htf_loop( $hook, $detail ) {
	if ( strpos( $hook, 'frm') === false ) {
		return;
	}

	$this_hook = s11_htf_prepare_data( $hook, $detail );
	s11_htf_create_entry( $this_hook );
}

function s11_htf_prepare_data( $hook, $detail ) {
	$hook = trim( $hook, "'" );
	$this_hook = array(
		'hook' => $hook,
		'type' => $detail['type'],
		'description' => s11_htf_hook_description( $detail ),
		'signature' => $detail['signature'],
		'since' => s11_htf_hook_since( $detail ),
		//'params' => s11_htf_hook_params( $detail ),
		'dynamic' => ( strpos( $hook, '.' ) || strpos( $hook, '{' ) ),
	);
	return $this_hook;
}

function s11_htf_hook_description( $detail ) {
	$desc = '';
	if ( $detail['parsed_comment'] ) {
		$desc = implode( ' ', $detail['parsed_comment']['description'] );
	}
	return $desc;
}

function s11_htf_hook_since( $detail ) {
	$desc = '';
	if ( $detail['parsed_comment'] && isset( $detail['parsed_comment']['since'] ) ) {
		$desc = implode( ' ', $detail['parsed_comment']['since'] );
	}
	return $desc;
}

function s11_htf_hook_params( $detail ) {
	var_dump( $detail['params'] );
	return '';
}

function s11_htf_create_entry( $hook ) {
	$entry_exists = FrmEntry::get_id_by_key( $hook['hook'] );
	if ( $entry_exists ) {
		return;
	}

	$form_id = 963;
	FrmEntry::create( array(
	  'form_id' => $form_id,
	  'item_key' => $hook['hook'],
	  'item_meta' => array(
	    41368 => $hook['hook'],
	    41369 => $hook['description'],
		41370 => $hook['since'],
		41371 => $hook['type'],
		41372 => $hook['signature'],
		41373 => $hook['dynamic'],
	  ),
	) );
}
