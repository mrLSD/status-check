<?php
/**
 * Extended var-dumper
 * @author	Evgeny Ukhanov
 */
class vd extends CVarDumper
{
	/**
	* Dump datas
	*/
	public static function d($var)
	{
		echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>';
		echo '<div id="debugger" style="display:none"><div style="width:958px; position:fixed; top:0; left:0; clear:boch; padding:20px; background:#fff; border:1px solid #412d15; position:relative; z-index:200000;">';
		echo '<font size="6">'.date("d-M-Y H:i:s").' Dump data:</font><br/>';
		CVarDumper::dump($var, 100, true);
		echo '<hr/>';
		echo '</div></div>';
		echo '<script>$("body").prepend( $("#debugger").html() );$("#debugger").remove(); </script>';
	}

	public static function dd($var)
	{
		self::d($var);
		die();
	}
}
