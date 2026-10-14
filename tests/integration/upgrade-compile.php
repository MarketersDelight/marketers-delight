<?php
/**
 * Test the update-request boundary without a database or filesystem writes.
 * Run: php tests/integration/upgrade-compile.php
 */
function get_template() { return 'marketers-delight'; }
function get_option($key) { return $GLOBALS['options'][$key] ?? false; }
function update_option($key,$value,$autoload=null) { $GLOBALS['options'][$key]=$value; }
function delete_option($key) { unset($GLOBALS['options'][$key]); }
class WP_Error {}
function is_wp_error($value) { return $value instanceof WP_Error; }
function md_compile() { $GLOBALS['compiles']++; return $GLOBALS['compile_result']; }
require dirname(__DIR__,2).'/api/enqueue.php';
$new_request=function(){return (new ReflectionClass('md_enqueue'))->newInstanceWithoutConstructor();};
$checks=0;
$check=function($condition,$message)use(&$checks){if(!$condition)throw new RuntimeException($message);$checks++;};
$GLOBALS['options']=array();$GLOBALS['compiles']=0;$GLOBALS['compile_result']=true;
$request=$new_request();
$request->compile_after_upgrade();
$check($GLOBALS['compiles']===0,'Do not compile without a pending upgrade.');
$request->compile_on_upgrade((object)array('result'=>array('destination_name'=>'other')),array('type'=>'theme','themes'=>array('other')));
$check(!get_option('md_compile_after_upgrade'),'Ignore unrelated theme updates.');
$request->compile_on_upgrade((object)array('result'=>new WP_Error),array('type'=>'theme','themes'=>array('marketers-delight')));
$check(!get_option('md_compile_after_upgrade'),'Ignore failed updates.');
$request->compile_on_upgrade((object)array('result'=>array('destination_name'=>'marketers-delight')),array('type'=>'theme'));
$check(get_option('md_compile_after_upgrade')===true && $GLOBALS['compiles']===0,'Schedule compilation without running the old compiler.');
$request->compile_after_upgrade();
$check($GLOBALS['compiles']===0,'Do not compile in the update request.');
$request=$new_request();$GLOBALS['compile_result']=new WP_Error;
$check(is_wp_error($request->compile_after_upgrade()) && get_option('md_compile_after_upgrade'),'Keep the pending rebuild after failure.');
$request=$new_request();$GLOBALS['compile_result']=true;
$check($request->compile_after_upgrade()===true && !get_option('md_compile_after_upgrade'),'Clear the pending rebuild after success.');
$count=$GLOBALS['compiles'];$new_request()->compile_after_upgrade();
$check($GLOBALS['compiles']===$count,'Do not rebuild again after success.');
echo "Passed $checks deferred compilation checks.\n";
