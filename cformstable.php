<?php
/*
Plugin Name: Unofficial cforms II table display
Description: Replaces a shortcode such as [cformstable form='nameofyourform'] with a table of data or a count of information collected via the excellent cformsII 
Version: 1.1.3
Author: Martin Tod
Author URI: http://www.martintod.org.uk
License: GPL2
*/
/*  Copyright 2014 Martin Tod email : martin@martintod.org.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Replaces a Wordpress shortcode such as [cformstable form='nameofyourform'] with a table of data or a count of information collected via the excellent cformsII 
 *
 * @package WordPress
 * @since 3.3.1
 *
 */

function cformstable_display( $atts, $content=null, $code="" ) {
	// $atts    ::= form,from,to,sort,limit,sortdir,vars
/**
	Possible values for get_cforms_entries()
	$form :: [text]	text string (regexp pattern), e.g. the form name - input as 'form'
	$from, $to :: [date]	DATETIME string (format: Y-m-d H:i:s). Date & time defining the target period, e.g. 2008-09-17 15:00:00
	$sort :: [text]	'form', 'id', 'date', 'ip' or 'email' or any other form input field, e.g. 'Your Name'
	$limit :: [number]	limiting the number of results, '' (empty or false) = no limits!
	$sortdir :: [text]	asc, desc
	
	Added by me:
	$display :: [text] 'number' , 'table' (default 'number');
	$vars :: [text] list of variable names separated by commas e.g. Name,Date,Comment
*/
	extract( shortcode_atts( array(
			'form' => FALSE,
			'from' => FALSE,
			'to' => FALSE,
			'sort' => TRUE,
			'limit' => FALSE,
			'deduped' => TRUE,
			'adjust' => 0,
			'sortdir' => 'desc',
			'display' => 'number',
			'vars' => FALSE,
			'cols' => FALSE
		), $atts ) );
	if ( function_exists('get_cforms_entries') ) {
		$returnstring = '';
		$cformsdata = get_cforms_entries( $form, $from, $to, $sort, $limit ,$sortdir );
		if ( trim(strtolower($display)) == 'table' ) {
			if(!empty($cformsdata)) {
				if( $form == FALSE ) {
					# If no form is set as a variable, show a list of cForms II forms
					$cformslist=array();
					foreach($cformsdata as $e) {
						$cformslist[] = $e['form'];
					}
					$cformscount = array_count_values($cformslist);
					ksort($cformscount);
					if(current_user_can('publish_posts') or current_user_can('publish_pages')){
						$returnstring .= "<p><em><strong>This text only appears to users able to publish pages or posts.</strong> To display a table of cformsII data, please include a form name in the shortcode, e.g. </em><code>[cformstable display='table' form='".current($cformslist)."']</code><em>.  You currently have the choice of the following forms in your database:</em></p>";
					}
					$returnstring .= "\n<table><thead><tr><th>Current forms</th><th align='right'>Number</th></tr></thead><tbody>\n";
					foreach($cformscount as $e => $v) {
						$returnstring .= "    <tr><td>".$e."</td><td align='right'>".number_format($v)."</td></tr>\n";
					};
					$returnstring .= "</tbody></table>\n";
				} elseif ( $vars == FALSE && $cols == FALSE ) {
					# If no variables is set as column headings, show a list of possible variables
					if(current_user_can('publish_pages') or current_user_can('publish_posts')){
						$returnstring .= "<p><em><strong>This text only appears to users able to publish pages or posts.</strong> To display a complete table of cformsII data, please include variable names in the shortcode, e.g. </em><code>[cformstable display='table' form='".$form."' vars='Name,Email']</code><em> or use the 'cols' command </em><code>[cformstable display='table' form='".$form."' cols='1,3,4']</code><em>.  You currently have the choice of the following variables in your database:</em></p>";
					}
					$cformskeys = cformstable_create_ordered_list_from_array($form);
					if(WP_DEBUG):
						echo "<!-- ";print_r($cformsdata);echo " -->";
					endif;
					$returnstring .= "\n<table><thead><tr><th align='right'>#</th><th>Current variables</th></tr></thead><tbody>\n";
					$i = 0;
					foreach($cformskeys as $v => $e) {
						$returnstring .= "   <tr><td align='right'>$v</td><td>".wp_kses_post(stripslashes($e))."</tr></td>\n";
						$i++;
					};
					$returnstring .= "</tbody></table>\n";
				} else {
					if(WP_DEBUG):
						echo "<!-- ";print_r($cformsdata);echo " -->";
					endif;
					$cformstable = array();
					if($vars):
						$headers = explode(",",$vars);
					elseif($cols):
						$colnums = explode(",",$cols);
						$headers = array();
						$cformskeys = cformstable_create_ordered_list_from_array($form);
						foreach($colnums as $col):
							if(strpos($col,'|')>0):
								$splitc = explode('|',$col);
								$cname = $splitc[1];
								$cvalue = intval($splitc[0]);
							else:
								$cvalue = intval($col);
								$cname = '';
							endif;
							if($cvalue>-1):
								$header = stripslashes($cformskeys[$cvalue]);
							endif;
							if($cname!=''):
								$header .= '|'.$cname;
							endif;
							$headers[$cvalue] = $header;
						endforeach;	
					endif;
					if( $sort === true || $sort == 1 ):
						if($sortdir == 'desc'):
							krsort($cformsdata);
						else:
							ksort($cformsdata);
						endif;
					endif;
					foreach($cformsdata as $e) {
						$cformsrow = array();
						unset($cformduplicatecheck);
						foreach($headers as $h) {
							if(strpos($h,'|')>0):
								$splith = explode('|',$h);
								$hname = $splith[1];
								$hvalue = $splith[0];
							else:
								$hname = $h;
								$hvalue = $h;
							endif;							
							if(isset($e['data'][addslashes($hvalue)])):
								$cformsrow[$hname] = $e['data'][addslashes($hvalue)];
							elseif(isset($e['data'][$hvalue])):
								$cformsrow[$hname] = $e['data'][$hvalue];
							else:
								$cformsrow[$hname] = '&nbsp;';
							endif;
							$cformduplicatecheck .= $e['data'][$hvalue];
						}
						if(!empty($cformduplicatecheck)) {
							$cformstable[$cformduplicatecheck] = $cformsrow;
						}
					}
					if(!empty($cformstable)) {
						$returnstring .= "\n<table><thead><tr>";
						foreach($headers as $h) {
							if(strpos($h,'|')>0):
								$splith = explode('|',$h);
								$hname = $splith[1];
								$hvalue = $splith[0];
							else:
								$hname = esc_attr($h);
								$hvalue = $h;
							endif;							
							$returnstring .= "\n    <th>$hname</th>";
						}
						$returnstring .= "\n</tr></thead>\n<tbody>";
						foreach($cformstable as $r) {
							$returnstring .= "\n    <tr>";
							foreach($r as $d) {
								if(empty($d)):
									$d = '&nbsp;';
								endif;
								$returnstring .= "<td>".wp_kses_post($d)."</td>";
							}
							$returnstring .= "</tr>";
						}
						$returnstring .= "\n</tbody></table>";
					} elseif(current_user_can('publish_pages') or current_user_can('publish_posts')){
						$returnstring .= "<p><em><strong>This text only appears to users able to publish pages or posts.</strong>  There is no CFormsII data that matches the values in your shortcode. Try starting with </em><code>[cformstable display='table' form='$form']</code><em> to see a list of possible variables you can include.</em></p>";
					}
				}
			} else {
				if(current_user_can('publish_posts') or current_user_can('publish_pages')){
					$returnstring .= "<p><em><strong>This text only appears to users able to publish pages or posts.</strong>  There is no CFormsII data that matches the values in your shortcode. Try starting with </em><code>[cformstable display='table']</code><em> to see a list of possible forms you can report from.</em></p>";
				}
				$returnstring .= "<!-- There is no CFormsII data that matches the values in your shortcode -->";
			}
		} else {
			# Show a form count
			# Just count unique IP address and email combinations (to reduce multiple entries)
			unset($cformsips);
			if(!empty($cformsdata)) {
				foreach( $cformsdata as $e ) {
					$cformsips[] = $e[ip].":".trim(strtolower($e[email]));
				}
				$cformsips = array_unique($cformsips);
				if($deduped != FALSE):
					$returnstring .= (number_format(count($cformsips)+round($adjust)));
				else:
					$returnstring .= (number_format(count($cformsdata)+round($adjust)));
				endif;
				$returnstring .= "<!-- Note: duped count ".(number_format(count($cformsdata)+round($adjust)));
				$returnstring .= " - deduped count: ".(number_format(count($cformsips)+round($adjust)))." -->";
			} else {
				$returnstring .= "0";
			}
		}
	} else {
		# If the CFormsII plugin is not installed
		if(current_user_can('publish_pages') or current_user_can('publish_posts')){
			$returnstring .= "<p><em><strong>This text only appears to users able to publish pages or posts.</strong> This shortcode needs the <a href='http://www.deliciousdays.com/cforms-plugin/'>CFormsII</a> plug-in to be installed and activated</em></p>";
		}
	}
	return $returnstring;
}

// cformstable_create_ordered_list_from_array() is designed to deal with the problem that CFormsII can add or change extra variables as go through its values... 
// but the number-based "lookup" with earlier versions of the plug-in is based on an alphabetical sort of the variable names
// So it takes the variables from the first entry and puts them in alphabetical order (not ideal - but it's how the earlier plugins worked) and then goes through looking
// to see if other variables have been added - sorts them - and tacks them on the end

function cformstable_create_ordered_list_from_array($form) {
	$data = get_cforms_entries( $form , FALSE, FALSE, TRUE, FALSE, 'desc' );
	$keydata=array();
	foreach($data as $vars) {
		$extra_key_data = array_diff_key($vars['data'],$keydata);
		ksort($extra_key_data);
		$keydata = array_merge($keydata,$extra_key_data);
	}
	$keys = array_keys($keydata);
	return($keys);
}
add_shortcode( 'cformstable', 'cformstable_display' );
?>