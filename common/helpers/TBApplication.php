<?php
namespace common\helpers;
class TBApplication
{	
	/**
	 * default display date format (friendly display)
	 * 
	 * @var string
	 */
	const DATE_FORMAT_DD_MM_YYYY_HI_MI = 'd M, Y H:i';
	
	const DATE_FORMAT_FRIENDLY_DATE = 'd M Y';
	const DATE_FORMAT_FRIENDLY_DATETIME = 'd M Y, h:i a';
	const DATE_FORMAT_FRIENDLY_TIME_12 = 'h:i a';
	const DATE_FORMAT_SQL_DATETIME = 'Y-m-d H:i:s';


        /**
	 * encode string to utf8 if not already encoded.
	 * 
	 * @param string $string
	 * @return string encoded string
	 */
	public static function encodeToUTF8($string)
	{
		return mb_check_encoding($string, 'UTF-8') ? $string : utf8_encode($string);
	}
	
	/**
	 * Turn a string into a safe string for friendly URL, regardless of language
	 * At the same time, ensure it doesn't mess up our url controller/action format
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function getURLEncodedString($string)
	{	
		// encode into UTF-8
		$string = self::encodeToUTF8($string);
		
		// replace space with dash '-'
		$string = str_replace(' ','-', $string);
		// only allow alphabets, numbers and dashes
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
		
		return urlencode($string);
	}
	
	public static function getSummary($str, $word_wrap = true, $max_length = 50)
	{
		return self::getShortName($str, $word_wrap, $max_length);
	}

	/**
	 * A function to shorten name, word-wrap is used
	 * 
	 * @param string $str
	 * @param boolean $word_wrap true for word-wrap, false for none. default is true
	 * @param number $max_length
	 * @return Ambigous <string, mixed>
	 */
	public static function getShortName($str, $word_wrap = true, $max_length = 50)
	{
		$result = $str;
		
		if (strlen($str) <= $max_length)
		{	
			$result = $str;
		}
		else if ($word_wrap)
		{
			// short title with word wrap
			$short_title_wr = preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $max_length));
			if (strlen($str) > strlen($short_title_wr))
			{
				$short_title_wr .= '...';
			}
			
			$result =  $short_title_wr;
		} else {
			$short_title = $str;
			// short title with no word wrap, word may be cut off
			if (strlen($str) > $max_length)
			{
				$short_title = mb_substr($str, 0, $max_length) . '...';
			} 
			
			$result =  $short_title;
		}
		
		return strip_tags($result);
	}
	
	public static function generateManualAjaxLink($label, $ajaxOptions, $htmlOptions)
	{
		if ($htmlOptions == null) $htmlOptions == array();
		$htmlOptions["onClick"] = " {". CHtml::ajax(
						$ajaxOptions,
						$htmlOptions) . "; event.stopPropagation(); return false; }";
		$htmlOptions["id"] = "ajax-id-" . uniqid();
		
		return CHtml::link(
			$label,
			"#",
			$htmlOptions);
	}
	
	/**
	 * @return integer	ID of subscription that is currently open
	 */
	public static function getCurrentlySelectedSubscription()
	{
		if (isset( Yii::app()->user->linx_app_selected_subscription ))
			return Yii::app()->user->linx_app_selected_subscription;
		
		return 0;
	}
	
	/**
	 * Set id of current subscription view
	 * 
	 * @param int $id
	 */
	public static function setCurrentlySelectedSubscription($id)
	{
		if (!isset( Yii::app()->user->linx_app_selected_subscription ))
			return false;
		
		// check if this user is master account
		$is_master = Account::model()->isMasterAccount($id);
		
		// or a member of this subscription
		$master_account_id = AccountSubscription::model()->getSubscriptionOwnerID($id);
		$is_member = AccountTeamMember::model()->isValidMember($master_account_id, Yii::app()->user->id);
		
		if ($is_master || $is_member){
			Yii::app()->user->linx_app_selected_subscription = $id;
			return true;
		}
		
		return false;
	}
	
	/**
	 * @param Controller $controller
	 * Go to home page, usually it's project index page
	 */
	public static function redirectToHome($controller)
	{
		$controller->redirect(array(self::getCurrentlySelectedSubscription().'/'));
	}
	
	public static function isWorkspace()
	{
		return isset($_GET['workspace']) && $_GET['workspace'] == true;
	}
	
	public static function isAjax()
	{
		return isset($_GET['ajax']) || isset($_POST['ajax']);
	}
	
	public static function mobileViewRequested()
	{
		return isset($_GET['app_mobile_view']) || isset($_POST['app_mobile_view']);
	}
	
	/**
	 * Application wrapper for render and renderPartial
	 * It'll not reload javascript that is already included in header
	 * 
	 * @param string $view
	 * @param array $data
	 * @param string $return
	 * @param string $processOutput
	 */
	public static function render($controller, $view, $data, $return = false, $processOutput = true)
	{	
                
		$default_view_path = '';
		if (isset($controller->default_view_path))
		{
			$default_view_path = $controller->default_view_path;
		}
		
		if (self::isWorkspace() || self::isAjax())
		{
			self::renderPartial($controller, $default_view_path . $view, $data, $return, $processOutput);
		} else {
			$controller->render($default_view_path . $view, $data, $return);
		}
	}
	
	
	public static function renderPartial($controller, $view, $data, $return = false, $processOutput = true)
	{
		// For jQuery core, Yii switches between the human-readable and minified
		// versions based on DEBUG status; so make sure to catch both of them
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery-ui-i18n.min.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.tooltip-1.2.6.min.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap.min.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
		
		Yii::app()->clientScript->scriptMap['bootstrap-editable.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap-editable.min.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.gridster.min.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap-tooltip.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.cleditor.min.js'] = false;
		Yii::app()->clientScript->scriptMap['fileuploader.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.ba-bbq.min.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap-datepicker.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.autosize.min'] = false;
                Yii::app()->clientScript->scriptMap['theblue.js'] = false;
	
		if ($return)
			return $controller->renderPartial($view, $data, $return, $processOutput);
		else
			$controller->renderPartial($view, $data, $return, $processOutput);
	}
	
	public static function renderPlain($controller, $data)
	{
		self::renderPartial($controller, '//layouts/plain_ajax_content', $data);
	}
	
	/**
	 * Application wrapper for CHtml::link
	 * This function render link with extra attributes that the Application may use, such as data-workspace
	 * 
	 * @param unknown $text
	 * @param string $url
	 * @param unknown $htmlOptions
	 */
	public static function workspaceLink($text, $url = '#', $htmlOptions = array())
	{
		$htmlOptions['data-workspace'] = '1';
		
		return CHtml::link(
			$text,
			$url, // Yii URL
			$htmlOptions
		);
	}
	
	/**
	 * 
	 * @param unknown $email
	 * @return mixed false if filter failed. Data if valid email
	 */
	public static function isValidEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	 * Format date according to input param
	 * 
	 * @param string $date SQL DATE FORMATE
	 * @param string $format
	 */
	public static function formatDisplayDate($date, $format = self::DATE_FORMAT_DD_MM_YYYY_HI_MI)
	{
		$time = strtotime($date);
		
		return date($format, $time);
	}
	
	/**
	 * This function creates a table of contents for an HTML document using 
	 * it's heading structure, adding ID's to the provided $content string where needed. 
	 * It returns an array with two keys, the Table of Contents, in HTML ul > li format, 
	 * and the content with the headings replaced. 
	 * 
	 * Useful for automatically creating table's of content for long articles.
	 * 
	 * @author Joost de Valk, http://www.westhost.com/contest/php/function/create-table-of-contents/124
	 * @param unknown $content
	 * @return multitype:string mixed array('toc' => $toc, 'content' => $content)
	 */
	public static function createTOC( $content ) {
		preg_match_all( '/<h([1-6])(.*)>([^<]+)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER );
	
		global $anchors;
	
		$anchors = array();
		$toc 	 = '<ul class="toc">'."\n";
		$i 		 = 0;
		$lvl	 = 0;
		$startlvl= 0;
		
		foreach ( $matches as $heading ) {
	
			if ($i == 0)
				$startlvl = $heading[1];
			$lvl 		= $heading[1];
	
			$ret = preg_match( '/id=[\'|"](.*)?[\'|"]/i', stripslashes($heading[2]), $anchor );
			if ( $ret && $anchor[1] != '' ) {
				$anchor = stripslashes( $anchor[1] );
				$add_id = false;
			} else {
				$anchor = preg_replace( '/\s+/', '-', preg_replace('/[^a-z\s]/', '', strtolower( $heading[3] ) ) );
				$add_id = true;
			}
	
			if ( !in_array( $anchor, $anchors ) ) {
				$anchors[] = $anchor;
			} else {
				$orig_anchor = $anchor;
				$i = 2;
				while ( in_array( $anchor, $anchors ) ) {
					$anchor = $orig_anchor.'-'.$i;
					$i++;
				}
				$anchors[] = $anchor;
			}
	
			if ( $add_id ) {
				$header_style = '';
				if ($lvl == $startlvl)
				{
					$header_style = 'border-bottom: 1px solid #DCDCDC';
				}
				$content = substr_replace( $content, 
						'<div style="display: table;  width: 100%;'. $header_style . '">
							<h'.$lvl.' id="'.$anchor.'"'.$heading[2].' style="float: left">'.$heading[3].'</h'.$lvl.'>'.
							'<span style="display: table-cell; vertical-align: middle; text-align: right"><a href="#wiki-toc-top">[toc]</a></span></div>', 
						strpos( $content, $heading[0] ), 
						strlen( $heading[0] ) );
			}
	
			$ret = preg_match( '/title=[\'|"](.*)?[\'|"]/i', stripslashes( $heading[2] ), $title );
			if ( $ret && $title[1] != '' )
				$title = stripslashes( $title[1] );
			else
				$title = $heading[3];
			$title 		= trim( strip_tags( $title ) );
	
			if ($i > 0) {
				if ($prevlvl < $lvl) {
					$toc .= "\n"."<ul>"."\n";
				} else if ($prevlvl > $lvl) {
					$toc .= '</li>'."\n";
					while ($prevlvl > $lvl) {
						$toc .= "</ul>"."\n".'</li>'."\n";
						$prevlvl--;
					}
				} else {
					$toc .= '</li>'."\n";
				}
			}
	
			$j = 0;
			$toc .= '<li><a href="#'.$anchor.'">'.$title.'</a>';
			$prevlvl = $lvl;
	
			$i++;
		}
	
		unset( $anchors );
	
		while ( $lvl > $startlvl ) {
			$toc .= "\n</ul>";
			$lvl--;
		}
	
		$toc .= '</li>'."\n";
		$toc .= '</ul>'."\n";
	
		// wrap toc in styled div
		$toc = '<a id="wiki-toc-top"><br/><div class="wiki-toc"><h4></a>Table of Contents</h4>' . $toc . '</div>';
		
		return array(
				'toc' => $toc,
				'content' => $content
		);
	}
	
	/**
	 * Original code, before updating to createTOC
	 * Do NOT user in PRODUCTION
	 * 
	 * @param unknown $content
	 * @return multitype:string mixed
	 */
	public static function create_toc_test( $content ) {
		preg_match_all( '/<h([1-6])(.*)>([^<]+)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER );
	
		global $anchors;
	
		$anchors = array();
		$toc 	 = '<ol class="toc">'."\n";
		$i 		 = 0;
	
		foreach ( $matches as $heading ) {
	
			if ($i == 0)
				$startlvl = $heading[1];
			$lvl 		= $heading[1];
	
			$ret = preg_match( '/id=[\'|"](.*)?[\'|"]/i', stripslashes($heading[2]), $anchor );
			if ( $ret && $anchor[1] != '' ) {
				$anchor = stripslashes( $anchor[1] );
				$add_id = false;
			} else {
				$anchor = preg_replace( '/\s+/', '-', preg_replace('/[^a-z\s]/', '', strtolower( $heading[3] ) ) );
				$add_id = true;
			}
	
			if ( !in_array( $anchor, $anchors ) ) {
				$anchors[] = $anchor;
			} else {
				$orig_anchor = $anchor;
				$i = 2;
				while ( in_array( $anchor, $anchors ) ) {
					$anchor = $orig_anchor.'-'.$i;
					$i++;
				}
				$anchors[] = $anchor;
			}
	
			if ( $add_id ) {
				$content = substr_replace( $content, '<h'.$lvl.' id="'.$anchor.'"'.$heading[2].'>'.$heading[3].'</h'.$lvl.'>', strpos( $content, $heading[0] ), strlen( $heading[0] ) );
			}
	
			$ret = preg_match( '/title=[\'|"](.*)?[\'|"]/i', stripslashes( $heading[2] ), $title );
			if ( $ret && $title[1] != '' )
				$title = stripslashes( $title[1] );
			else
				$title = $heading[3];
			$title 		= trim( strip_tags( $title ) );
	
			if ($i > 0) {
				if ($prevlvl < $lvl) {
					$toc .= "\n"."<ol>"."\n";
				} else if ($prevlvl > $lvl) {
					$toc .= '</li>'."\n";
					while ($prevlvl > $lvl) {
						$toc .= "</ol>"."\n".'</li>'."\n";
						$prevlvl--;
					}
				} else {
					$toc .= '</li>'."\n";
				}
			}
	
			$j = 0;
			$toc .= '<li><a href="#'.$anchor.'">'.$title.'</a>';
			$prevlvl = $lvl;
	
			$i++;
		}
	
		unset( $anchors );
	
		while ( $lvl > $startlvl ) {
			$toc .= "\n</ol>";
			$lvl--;
		}
	
		$toc .= '</li>'."\n";
		$toc .= '</ol>'."\n";
	
		return array(
				'toc' => $toc,
				'content' => $content
		);
	}
	
	public static function getDateTimeByUserTimeZone($date_string)
	{
		$tz = new DateTimeZone(Yii::app()->user->timezone);
		$date = new DateTime($date_string);
		$date->setTimeZone($tz);
		return $date;
	}
	
	public static function displayFriendlyDate($date_string, $format = self::DATE_FORMAT_FRIENDLY_DATE)
	{
		$date = self::getDateTimeByUserTimeZone($date_string);
		return $date->format($format);
	}
	
	public static function displayFriendlyDateTime($date_string, $format = self::DATE_FORMAT_FRIENDLY_DATETIME)
	{
		$date = self::getDateTimeByUserTimeZone($date_string);
		return $date->format($format);
	}
	
	public static function displayFriendlyTime($date_string, $format = self::DATE_FORMAT_FRIENDLY_TIME_12)
	{
		$date = self::getDateTimeByUserTimeZone($date_string);
		return $date->format($format);
	}
	
	/**
	 * 
	 * @param string $date_string
	 * @return string converted date string
	 */
	public static function convertDateToUserTimeZone($date_string, $format = 'Y-m-d')
	{
		$date = self::getDateTimeByUserTimeZone($date_string);
		return $date->format($format);
	}
	
	public static function convertDateToServerTimeZone($date_string, $format = 'Y-m-d')
	{
		$server_tz = date_default_timezone_get();
		if ($server_tz == '')
			$server_tz = ini_get('date.timezone');
		$tz = new DateTimeZone($server_tz);
		$date = new DateTime($date_string);
		$date->setTimeZone($tz);
		return $date->format($format);
	}
	
	/**
	 * For dropdown source
	 */
	public static function getTimeZoneListSource()
	{
		$results = array(''=>'Select');
		$timezone_identifiers = DateTimeZone::listIdentifiers();
		foreach ($timezone_identifiers as $tz) {
			$results[$tz] = $tz;
		}
		
		return $results;
	}
        
        public static function getFormatDay(){
            $model = Systems::model()->find('sys_parame="d_day"');
            return $model->sys_value;
        }
        
        public static function getFormatTime(){
            $model = Systems::model()->find('sys_parame="d_time"');
            return $model->sys_value;
        }
        
        public static function getFormatDate(){
            $model = Systems::model()->find("sys_parame='d_day'");
            $model_time = Systems::model()->find('sys_parame="d_time"');
            return $model->sys_value.' '.$model_time->sys_value;
            
            
        }
        
        public static function getDefaultLangue(){
            $model = Systems::model()->find('sys_parame="d_language"');
            return $model->sys_value;
        }
        
        public static function getDefaultEmail(){
            $model = Systems::model()->find('sys_parame="d_email"');
            return $model->sys_value;
        }
        
        public static function getTitlePage(){
            $model = Systems::model()->find('sys_parame="d_titlepage"');
            return $model->sys_value;
        }
        
        public static function getCurrency(){
            $model = Systems::model()->find('sys_parame="d_currency"');
            return $model->sys_value;
        }

        /**
     * Chuyển xâu ký tự có dấy thành ko dấu.
     * @param type $str
     * @return type
     */
    public static function removesign($str,$pace="-")
    {
        $coDau=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ"
        ,"ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ","ê","ù","à"," ");
        $khongDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","e","u","a",$pace);
        return str_replace($coDau,$khongDau,$str);
    }
    
    public static function ArrStatus(){
        return array('Ẩn','Hiện');
    }
    
    public static function listVariableContact()
    {
        $option_type_arr=array();
        $option_type_arr[0]['name']='$ten$';
        $option_type_arr[0]['description']='Tên';
        $option_type_arr[1]['name']='$tuoi$';
        $option_type_arr[1]['description']='Tuổi';
        $option_type_arr[2]['name']='$email$';
        $option_type_arr[2]['description']='Email';
        $option_type_arr[3]['name']='$dienthoai$';
        $option_type_arr[3]['description']='Số điện thoại';
        $option_type_arr[4]['name']='$gioitinh$';
        $option_type_arr[4]['description']='Giới tính';
        $option_type_arr[5]['name']='$diachi$';
        $option_type_arr[5]['description']='Địa chỉ';
        $option_type_arr[6]['name']='$congty$';
        $option_type_arr[6]['description']='Công ty';
        $option_type_arr[7]['name']='$ngaysinh$';
        $option_type_arr[7]['description']='Ngày sinh';
        return $option_type_arr;
    }
    
    /**
     * Chuyển các đầu số về 84
     * @param type $number
     * @return type
     */
    public static function convert84($number)
    {
        $number = trim($number);
        $index_1 = substr($number, 0,1);
        if($index_1 == 0)
        {
            $number = substr($number, 1);
        }
        $number_str2 = substr($number,0,2);
        if($number_str2!=84)
            $number = "84".$number;
        return $number;
    }
}
