<?php

namespace Lib;

C(array(

	'KSES_ALLOWED_GLOBAL_ATTR'=>array(
		'style' => true,
		'title' => true,

		),
	
	'KSES_ALLOWED_PROTOCOL' => array(
		'http',
		'https',
		'ftp',
		'ftps',
		'mailto',
		/*'news',
		'irc',
		'gopher',
		'nntp',
		'feed',
		'telnet',
		'mms',
		'rtsp',
		'svn',
		'tel',
		'fax',
		'xmpp'*/
		),

	'KSES_ALLOWED_HTML' => array(
		'a' => array(
			'href' => true,
			'rel' => true,
			'rev' => true,
			'name' => true,
			'target' => true,
		),
		
		'b' => array(),
		'blockquote' => array(
			'cite' => true,
			'lang' => true,
			'xml:lang' => true,
		),
		'br' => array(),
		'code' => array(),
		'del' => array(
			'datetime' => true,
		),
		'dd' => array(),
		/*'div' => array(
			'align' => true,
			'dir' => true,
			'lang' => true,
			'xml:lang' => true,
		),*/
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'font' => array(
			'color' => true,
			'face' => true,
			'size' => true,
		),
		
		'h1' => array(
			'align' => true,
		),
		'h2' => array(
			'align' => true,
		),
		'h3' => array(
			'align' => true,
		),
		'h4' => array(
			'align' => true,
		),
		'h5' => array(
			'align' => true,
		),
		'h6' => array(
			'align' => true,
		),
		
		'hr' => array(
			'align' => true,
			'noshade' => true,
			'size' => true,
			'width' => true,
		),
		'hide'=>array(),
		'i' => array(),
		'img' => array(
			'alt' => true,
			'align' => true,
			'border' => true,
			'height' => true,
			'hspace' => true,
			'longdesc' => true,
			'vspace' => true,
			'src' => true,
			'usemap' => true,
			'width' => true,
		),
		//下滑线
		'ins' => array(
			'datetime' => true,
			'cite' => true,
		),
		'kbd' => array(),
		'label' => array(),
		
		'li' => array(
			'align' => true,
			'value' => true,
		),
		
		'mark' => array(),
		
		
		'p' => array(
			'align' => true,
			'dir' => true,
			'lang' => true,
			'xml:lang' => true,
		),
		'pre' => array(
			'width' => true,
		),
		//加入引号
		'q' => array(
			'cite' => true,
		),
		//删除线
		's' => array(),
		
		'span' => array(
			'dir' => true,
			'align' => true,
			'lang' => true,
			'xml:lang' => true,
		),
		
		'small' => array(),
		'strike' => array(),
		'strong' => array(),
		'sub' => array(),
		'summary' => array(
			'align' => true,
			'dir' => true,
			'lang' => true,
			'xml:lang' => true,
		),
		'sup' => array(),
		'table' => array(
			'align' => true,
			'bgcolor' => true,
			'border' => true,
			'cellpadding' => true,
			'cellspacing' => true,
			'dir' => true,
			'rules' => true,
			'summary' => true,
			'width' => true,
		),
		'tbody' => array(
			'align' => true,
			'char' => true,
			'charoff' => true,
			'valign' => true,
		),
		'td' => array(
			'abbr' => true,
			'align' => true,
			'axis' => true,
			'bgcolor' => true,
			'char' => true,
			'charoff' => true,
			'colspan' => true,
			'dir' => true,
			'headers' => true,
			'height' => true,
			'nowrap' => true,
			'rowspan' => true,
			'scope' => true,
			'valign' => true,
			'width' => true,
		),
		/*'textarea' => array(
			'cols' => true,
			'rows' => true,
			'disabled' => true,
			'name' => true,
			'readonly' => true,
		),*/
		'tfoot' => array(
			'align' => true,
			'char' => true,
			'charoff' => true,
			'valign' => true,
		),
		'th' => array(
			'abbr' => true,
			'align' => true,
			'axis' => true,
			'bgcolor' => true,
			'char' => true,
			'charoff' => true,
			'colspan' => true,
			'headers' => true,
			'height' => true,
			'nowrap' => true,
			'rowspan' => true,
			'scope' => true,
			'valign' => true,
			'width' => true,
		),
		'thead' => array(
			'align' => true,
			'char' => true,
			'charoff' => true,
			'valign' => true,
		),
		
		'tr' => array(
			'align' => true,
			'bgcolor' => true,
			'char' => true,
			'charoff' => true,
			'valign' => true,
		),
	
		'tt' => array(),
		'u' => array(),
		'ul' => array(
			'type' => true,
		),
		'ol' => array(
			'start' => true,
			'type' => true,
			'reversed' => true,
		),
		
		'video' => array(
			'autoplay' => true,
			'controls' => true,
			'height' => true,
			'loop' => true,
			'muted' => true,
			'poster' => true,
			'preload' => true,
			'src' => true,
			'width' => true,
		),
		'video' => array(
			'autoplay' => true,
			'controls' => true,
			'height' => true,
			'loop' => true,
			'muted' => true,
			'poster' => true,
			'preload' => true,
			'src' => true,
			'width' => true,
		),
		'audio' => array(
			'controls' => true,
			'height' => true,
			'loop' => true,
			'muted' => true,
			'poster' => true,
			'preload' => true,
			'src' => true,
			'width' => true,
		),
		'embed'=>array(
			'src'=>true,
			'autostart'=>true,
			'width'=>true,
			'height'=>true,
		)
	),

));

class Kses
{
	
	public $allowed_protocols;
	public $allowed_html;
	public $allowed_global_attr;
	
	public function __construct()
	{
		/**
		 *	You could add protocols such as ftp, new, gopher, mailto, irc, etc.
		 *
		 *	The base values the original kses provided were:
		 *		'http', 'https', 'ftp', 'news', 'nntp', 'telnet', 'gopher', 'mailto'
		 */
		//$this->allowed_protocols = array('http', 'ftp', 'mailto');
		//$this->allowed_html      = array();

		$this->allowed_protocols =  C('KSES_ALLOWED_PROTOCOL');
		$this->allowed_html = C('KSES_ALLOWED_HTML');
		$global_attr = C('KSES_ALLOWED_GLOBAL_ATTR');
		if(is_array($global_attr) && count($global_attr) > 0){
			$this->allowed_global_attr = $global_attr;
			$this->allowed_html = array_map(array(__CLASS__,'add_global_attr'), $this->allowed_html);
		}
	}

	private function add_global_attr($value){
		if ($value === true)
			$value = array();
		if (is_array($value))
			return array_merge($value, $this->allowed_global_attr);
		return $value;
	}


	public function Parse($string = "")
	{
		if (get_magic_quotes_gpc())
		{
			$string = stripslashes($string);
		}
		$string = $this->removeNulls($string);
		//	Remove JavaScript entities from early Netscape 4 versions
		$string = preg_replace('%&\s*\{[^}]*(\}\s*;?|$)%', '', $string);

		$string = $this->normalizeEntities($string);

		$string = $this->filterKsesTextHook($string);
		$string = str_replace('"/>','">',$string);
		$string = preg_replace_callback('%(<' . '[^>]*' . '(>|$)' . '|>)%',		
		array( &$this, 'stripTags')
		, $string);
		
		return $string;
	}
	 public function stripTags($string)
	{
		
		$string = $string[1];
		$string = preg_replace('%\\\\"%', '"', $string);

		if (substr($string, 0, 1) != '<')
		{
			# It matched a ">" character
			return '&gt;';
		}

		if (!preg_match('%^<\s*(/\s*)?([a-zA-Z0-9]+)([^>]*)>?$%', $string, $matches))
		{
			# It's seriously malformed
			return '';
		}

		$slash    = trim($matches[1]);
		$elem     = $matches[2];
		$attrlist = $matches[3];

		if (
			!isset($this->allowed_html[strtolower($elem)]) ||
			!is_array($this->allowed_html[strtolower($elem)]))
		{
			#	Found an HTML element not in the white list
			return '';
		}

		if ($slash != '')
		{
			return "<$slash$elem>";
		}
		# No attributes are allowed for closing elements

		return $this->stripAttributes("$slash$elem", $attrlist);
	}
	
	public function AddProtocols()
	{
		$c_args = func_num_args();
		if($c_args != 1)
		{
			trigger_error("Kses::AddProtocols() did not receive an argument.", E_USER_WARNING);
			return false;
		}

		$protocol_data = func_get_arg(0);

		if(is_array($protocol_data) && count($protocol_data) > 0)
		{
			foreach($protocol_data as $protocol)
			{
				$this->AddProtocol($protocol);
			}
			return true;
		}
		elseif(is_string($protocol_data))
		{
			$this->AddProtocol($protocol_data);
			return true;
		}
		else
		{
			trigger_error("Kses::AddProtocols() did not receive a string or an array.", E_USER_WARNING);
			return false;
		}
	}

	public function Protocols()
	{
		$c_args = func_num_args();
		if($c_args != 1)
		{
			trigger_error("Kses::Protocols() did not receive an argument.", E_USER_WARNING);
			return false;
		}

		return $this->AddProtocols(func_get_arg(0));
	}

	
	public function AddProtocol($protocol = "")
	{
		if(!is_string($protocol))
		{
			trigger_error("Kses::AddProtocol() requires a string.", E_USER_WARNING);
			return false;
		}

		// Remove any inadvertent ':' at the end of the protocol.
		if(substr($protocol, strlen($protocol) - 1, 1) == ":")
		{
			$protocol = substr($protocol, 0, strlen($protocol) - 1);
		}

		$protocol = strtolower(trim($protocol));
		if($protocol == "")
		{
			trigger_error("Kses::AddProtocol() tried to add an empty/NULL protocol.", E_USER_WARNING);
			return false;
		}

		//	prevent duplicate protocols from being added.
		if(!in_array($protocol, $this->allowed_protocols))
		{
			array_push($this->allowed_protocols, $protocol);
			sort($this->allowed_protocols);
		}
		return true;
	}

	public function RemoveProtocol($protocol = "")
	{
		if(!is_string($protocol))
		{
			trigger_error("Kses::RemoveProtocol() requires a string.", E_USER_WARNING);
			return false;
		}

		// Remove any inadvertent ':' at the end of the protocol.
		if(substr($protocol, strlen($protocol) - 1, 1) == ":")
		{
			$protocol = substr($protocol, 0, strlen($protocol) - 1);
		}

		$protocol = strtolower(trim($protocol));
		if($protocol == "")
		{
			trigger_error("Kses::RemoveProtocol() tried to remove an empty/NULL protocol.", E_USER_WARNING);
			return false;
		}

		//	Ensures that the protocol exists before removing it.
		if(in_array($protocol, $this->allowed_protocols))
		{
			$this->allowed_protocols = array_diff($this->allowed_protocols, array($protocol));
			sort($this->allowed_protocols);
		}

		return true;
	}

	
	public function RemoveProtocols()
	{
		$c_args = func_num_args();
		if($c_args != 1)
		{
			return false;
		}

		$protocol_data = func_get_arg(0);

		if(is_array($protocol_data) && count($protocol_data) > 0)
		{
			foreach($protocol_data as $protocol)
			{
				$this->RemoveProtocol($protocol);
			}
		}
		elseif(is_string($protocol_data))
		{
			$this->RemoveProtocol($protocol_data);
			return true;
		}
		else
		{
			trigger_error("Kses::RemoveProtocols() did not receive a string or an array.", E_USER_WARNING);
			return false;
		}
	}

	public function SetProtocols()
	{
		$c_args = func_num_args();
		if($c_args != 1)
		{
			trigger_error("Kses::SetProtocols() did not receive an argument.", E_USER_WARNING);
			return false;
		}

		$protocol_data = func_get_arg(0);

		if(is_array($protocol_data) && count($protocol_data) > 0)
		{
			$this->allowed_protocols = array();
			foreach($protocol_data as $protocol)
			{
				$this->AddProtocol($protocol);
			}
			return true;
		}
		elseif(is_string($protocol_data))
		{
			$this->allowed_protocols = array();
			$this->AddProtocol($protocol_data);
			return true;
		}
		else
		{
			trigger_error("Kses::SetProtocols() did not receive a string or an array.", E_USER_WARNING);
			return false;
		}
	}

	
	public function DumpProtocols()
	{
		return $this->allowed_protocols;
	}

	
	public function DumpElements()
	{
		return $this->allowed_html;
	}


	public function AddHTML($tag = "", $attribs = array())
	{
		if(!is_string($tag))
		{
			trigger_error("Kses::AddHTML() requires the tag to be a string", E_USER_WARNING);
			return false;
		}

		$tag = strtolower(trim($tag));
		if($tag == "")
		{
			trigger_error("Kses::AddHTML() tried to add an empty/NULL tag", E_USER_WARNING);
			return false;
		}

		if(!is_array($attribs))
		{
			trigger_error("Kses::AddHTML() requires an array (even an empty one) of attributes for '$tag'", E_USER_WARNING);
			return false;
		}

		$new_attribs = array();
		if(is_array($attribs) && count($attribs) > 0)
		{
			foreach($attribs as $idx1 => $val1)
			{
				$new_idx1 = strtolower($idx1);
				$new_val1 = $attribs[$idx1];

				if(is_array($new_val1) && count($attribs) > 0)
				{
					$tmp_val = array();
					foreach($new_val1 as $idx2 => $val2)
					{
						$new_idx2 = strtolower($idx2);
						$tmp_val[$new_idx2] = $val2;
					}
					$new_val1 = $tmp_val;
				}

				$new_attribs[$new_idx1] = $new_val1;
			}
		}

		$this->allowed_html[$tag] = $new_attribs;
		return true;
	}

	/**
	 *	This method removes any NULL characters in $string.
	 *
	 *	@access private
	 *	@param string $string
	 *	@return string String without any NULL/chr(173)
	 *	@since PHP4 OOP 0.0.1
	 */
	private function removeNulls($string)
	{
		$string = preg_replace('/\0+/', '', $string);
		$string = preg_replace('/(\\\\0)+/', '', $string);
		return $string;
	}

	/**
	 *	Normalizes HTML entities
	 *
	 *	This function normalizes HTML entities. It will convert "AT&T" to the correct
	 *	"AT&amp;T", "&#00058;" to "&#58;", "&#XYZZY;" to "&amp;#XYZZY;" and so on.
	 *
	 *	@access private
	 *	@param string $string
	 *	@return string String with normalized entities
	 *	@since PHP4 OOP 0.0.1
	 */
	private function normalizeEntities($string)
	{
		# Disarm all entities by converting & to &amp;
		$string = str_replace('&', '&amp;', $string);

		#	TODO: Change back (Keep?) the allowed entities in our entity white list

		#	Keeps entities that start with [A-Za-z]
		$string = preg_replace(
			'/&amp;([A-Za-z][A-Za-z0-9]{0,19});/',
			'&\\1;',
			$string
		);

		#	Change numeric entities to valid 16 bit values

		$string = preg_replace_callback(
			'/&amp;#0*([0-9]{1,5});/',
			array( &$this, 'normalizeEntities16bit'),
			$string
		);

		#	Change &XHHHHHHH (Hex digits) to 16 bit hex values
		$string = preg_replace(
			'/&amp;#([Xx])0*(([0-9A-Fa-f]{2}){1,2});/',
			'&#\\1\\2;',
			$string
		);

		return $string;
	}
	

	public function normalizeEntities16bit($i)
	{
		$i = $i[1];
	  return (($i > 65535) ? "&amp;#$i;" : "&#$i;");
	}

	private function filterKsesTextHook($string)
	{
	  return $string;
	}

	private function _hook($string)
	{
		return $this->filterKsesTextHook($string);
	}

	private function makeArrayKeysLowerCase($in_array)
	{
		$out_array = array();

		if(is_array($in_array) && count($in_array) > 0)
		{
			foreach ($in_array as $in_key => $in_val)
			{
				$out_key = strtolower($in_key);
				$out_array[$out_key] = array();

				if(is_array($in_val) && count($in_val) > 0)
				{
					foreach ($in_val as $in_key2 => $in_val2)
					{
						$out_key2 = strtolower($in_key2);
						$out_array[$out_key][$out_key2] = $in_val2;
					}
				}
			}
		}

		return $out_array;
	}


	
	private function stripAttributes($element, $attr)
	{
		# Is there a closing XHTML slash at the end of the attributes?
		$xhtml_slash = '';
		if (preg_match('%\s/\s*$%', $attr))
		{
			$xhtml_slash = ' /';
		}

		# Are any attributes allowed at all for this element?
		if (
			!isset($this->allowed_html[strtolower($element)]) ||
			count($this->allowed_html[strtolower($element)]) == 0
		)
		{
			return "<$element$xhtml_slash>";
		}

		# Split it
		$attrarr = $this->combAttributes($attr);

		# Go through $attrarr, and save the allowed attributes for this element
		# in $attr2
		$attr2 = '';
		if(is_array($attrarr) && count($attrarr) > 0)
		{
			foreach ($attrarr as $arreach)
			{
				if(!isset($this->allowed_html[strtolower($element)][strtolower($arreach['name'])]))
				{
					continue;
				}

				$current = $this->allowed_html[strtolower($element)][strtolower($arreach['name'])];

				if (strtolower( $arreach['name'] ) == 'style')
				{
					
					$orig_value = $arreach['value'];
					$value = $this->safecss_filter_attr($orig_value);

					if (empty($value))
						continue;

					$arreach['value'] = $value;
					$arreach['whole'] = str_replace( $orig_value, $value, $arreach['whole'] );
				}


				if (!is_array($current))
				{
					# there are no checks
					$attr2 .= ' '.$arreach['whole'];
				}
				else
				{
					# there are some checks
					$ok = true;
					if(is_array($current) && count($current) > 0)
					{
						foreach ($current as $currkey => $currval)
						{
							if (!$this->checkAttributeValue($arreach['value'], $arreach['vless'], $currkey, $currval))
							{
								$ok = false;
								break;
							}
						}
					}

					if ($ok)
					{
						# it passed them
						$attr2 .= ' '.$arreach['whole'];
					}
				}
			}
		}

		# Remove any "<" or ">" characters
		$attr2 = preg_replace('/[<>]/', '', $attr2);
		return "<$element$attr2$xhtml_slash>";
	}

	
	private function combAttributes($attr)
	{
		$attrarr  = array();
		$mode     = 0;
		$attrname = '';

		# Loop through the whole attribute list

		while (strlen($attr) != 0)
		{
			# Was the last operation successful?
			$working = 0;

			switch ($mode)
			{
				case 0:	# attribute name, href for instance
					if (preg_match('/^([-a-zA-Z]+)/', $attr, $match))
					{
						$attrname = $match[1];
						$working = $mode = 1;
						$attr = preg_replace('/^[-a-zA-Z]+/', '', $attr);
					}
					break;
				case 1:	# equals sign or valueless ("selected")
					if (preg_match('/^\s*=\s*/', $attr)) # equals sign
					{
						$working = 1;
						$mode    = 2;
						$attr    = preg_replace('/^\s*=\s*/', '', $attr);
						break;
					}
					if (preg_match('/^\s+/', $attr)) # valueless
					{
						$working   = 1;
						$mode      = 0;
						$attrarr[] = array(
							'name'  => $attrname,
							'value' => '',
							'whole' => $attrname,
							'vless' => 'y'
						);
						$attr      = preg_replace('/^\s+/', '', $attr);
					}
					break;
				case 2: # attribute value, a URL after href= for instance
					if (preg_match('/^"([^"]*)"(\s+|$)/', $attr, $match)) # "value"
					{
						$thisval   = $this->removeBadProtocols($match[1]);
						$attrarr[] = array(
							'name'  => $attrname,
							'value' => $thisval,
							'whole' => $attrname . '="' . $thisval . '"',
							'vless' => 'n'
						);
						$working   = 1;
						$mode      = 0;
						$attr      = preg_replace('/^"[^"]*"(\s+|$)/', '', $attr);
						break;
					}
					if (preg_match("/^'([^']*)'(\s+|$)/", $attr, $match)) # 'value'
					{
						$thisval   = $this->removeBadProtocols($match[1]);
						$attrarr[] = array(
							'name'  => $attrname,
							'value' => $thisval,
							'whole' => "$attrname='$thisval'",
							'vless' => 'n'
						);
						$working   = 1;
						$mode      = 0;
						$attr      = preg_replace("/^'[^']*'(\s+|$)/", '', $attr);
						break;
					}
					if (preg_match("%^([^\s\"']+)(\s+|$)%", $attr, $match)) # value
					{
						$thisval   = $this->removeBadProtocols($match[1]);
						$attrarr[] = array(
							'name'  => $attrname,
							'value' => $thisval,
							'whole' => $attrname . '="' . $thisval . '"',
							'vless' => 'n'
						);
						# We add quotes to conform to W3C's HTML spec.
						$working   = 1;
						$mode      = 0;
						$attr      = preg_replace("%^[^\s\"']+(\s+|$)%", '', $attr);
					}
					break;
			}

			if ($working == 0) # not well formed, remove and try again
			{
				$attr = preg_replace('/^("[^"]*("|$)|\'[^\']*(\'|$)|\S)*\s*/', '', $attr);
				$mode = 0;
			}
		}

		# special case, for when the attribute list ends with a valueless
		# attribute like "selected"
		if ($mode == 1)
		{
			$attrarr[] = array(
				'name'  => $attrname,
				'value' => '',
				'whole' => $attrname,
				'vless' => 'y'
			);
		}

		return $attrarr;
	}

	
	private function removeBadProtocols($string)
	{
		$string  = $this->RemoveNulls($string);
		$string = preg_replace('/\xad+/', '', $string); # deals with Opera "feature"
		$string2 = $string . 'a';

		while ($string != $string2)
		{
			$string2 = $string;
			$string  =  preg_replace_callback(
								'/^((&[^;]*;|[\sA-Za-z0-9])*)'.
								'(:|&#58;|&#[Xx]3[Aa];)\s*/',
								array( &$this, 'filterProtocols'),
								$string
							);
		}

		return $string;
	}

	
	public function filterProtocols($string)
	{
		$string = $string[1];
		$string = $this->decodeEntities($string);
		$string = preg_replace('/\s/', '', $string);
		$string = $this->removeNulls($string);
		$string = preg_replace('/\xad+/', '', $string); # deals with Opera "feature"
		$string = strtolower($string);

		if(is_array($this->allowed_protocols) && count($this->allowed_protocols) > 0)
		{
			foreach ($this->allowed_protocols as $one_protocol)
			{
				if (strtolower($one_protocol) == $string)
				{
					return "$string:";
				}
			}
		}

		return '';
	}


	private function safecss_filter_attr($css){
		$css = $this->removeNulls($css);
		$css = str_replace(array("\n","\r","\t"), '', $css);

		if ( preg_match( '%[\\(&=}]|/\*%', $css ) ) // remove any inline css containing \ ( & } = or comments
			return '';

		$css_array = explode( ';', trim( $css ) );
		$allowed_attr = array( 'text-align', 'margin', 'color', 'float',
		'border', 'background', 'background-color', 'border-bottom', 'border-bottom-color',
		'border-bottom-style', 'border-bottom-width', 'border-collapse', 'border-color', 'border-left',
		'border-left-color', 'border-left-style', 'border-left-width', 'border-right', 'border-right-color',
		'border-right-style', 'border-right-width', 'border-spacing', 'border-style', 'border-top',
		'border-top-color', 'border-top-style', 'border-top-width', 'border-width', 'caption-side',
		'clear', 'cursor', 'direction', 'font', 'font-family', 'font-size', 'font-style',
		'font-variant', 'font-weight', 'height', 'letter-spacing', 'line-height', 'margin-bottom',
		'margin-left', 'margin-right', 'margin-top', 'overflow', 'padding', 'padding-bottom',
		'padding-left', 'padding-right', 'padding-top', 'text-decoration', 'text-indent', 'vertical-align',
		'width' );

		if ( empty($allowed_attr) )
			return $css;

		$css = '';
		foreach ( $css_array as $css_item ) {
			if ( $css_item == '' )
				continue;
			$css_item = trim( $css_item );
			$found = false;
			if ( strpos( $css_item, ':' ) === false ) {
				$found = true;
			} else {
				$parts = explode( ':', $css_item );
				if ( in_array( trim( $parts[0] ), $allowed_attr ) )
					$found = true;
			}
			if ( $found ) {
				if( $css != '' )
					$css .= ';';
				$css .= $css_item;
			}
		}

		return $css;
	}


	private function checkAttributeValue($value, $vless, $checkname, $checkvalue)
	{
		$ok = true;
		$check_attribute_method_name  = 'checkAttributeValue' . ucfirst(strtolower($checkname));
		if(method_exists($this, $check_attribute_method_name))
		{
			$ok = $this->$check_attribute_method_name($value, $checkvalue, $vless);
		}

		return $ok;
	}

	private function checkAttributeValueMaxlen($value, $checkvalue)
	{
		if (strlen($value) > intval($checkvalue))
		{
			return false;
		}
		return true;
	}

	private function checkAttributeValueMinlen($value, $checkvalue)
	{
		if (strlen($value) < intval($checkvalue))
		{
			return false;
		}
		return true;
	}


	private function checkAttributeValueMaxval($value, $checkvalue)
	{
		if (!preg_match('/^\s{0,6}[0-9]{1,6}\s{0,6}$/', $value))
		{
			return false;
		}
		if (intval($value) > intval($checkvalue))
		{
			return false;
		}
		return true;
	}

	private function checkAttributeValueMinval($value, $checkvalue)
	{
		if (!preg_match('/^\s{0,6}[0-9]{1,6}\s{0,6}$/', $value))
		{
			return false;
		}
		if (intval($value) < ($checkvalue))
		{
			return false;
		}
		return true;
	}

	
	private function checkAttributeValueValueless($value, $checkvalue, $vless)
	{
		if (strtolower($checkvalue) != $vless)
		{
			return false;
		}
		return true;
	}

	
	private function decodeEntities($string)
	{
		$string = preg_replace_callback('/&#([0-9]+);/', function($match){return chr($match[1]); }, $string);
		$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', function($match){return chr(hexdec($match[1])); }, $string);
		return $string;
	}

	public function Version()
	{
		return 'PHP5 OOP 1.0.2';
	}
}