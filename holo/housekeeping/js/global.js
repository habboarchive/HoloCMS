//------------------------------------------
// Invision Power Board v2.0
// Global JS File
// (c) 2003 Invision Power Services, Inc.
//
// http://www.invisionboard.com
//------------------------------------------

//==========================================
// Set up
//==========================================

var input_red      = 'input-warn';
var input_green    = 'input-ok';
var input_ok_box   = 'input-ok-content';
var input_warn_box = 'input-warn-content';

var img_blank      = 'blank.gif';
var img_tick       = 'aff_tick.gif';
var img_cross      = 'aff_cross.gif';

var uagent    = navigator.userAgent.toLowerCase();
// Konqueror works best when treated as Safari, especially for attachment manager
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") || (uagent.indexOf('konqueror') != -1) || (uagent.indexOf('khtml') != -1) );
var is_opera  = (uagent.indexOf('opera') != -1);
var is_webtv  = (uagent.indexOf('webtv') != -1);
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );
var is_ie7    = ( (is_ie) && (uagent.indexOf("msie 7.") != -1) );
var is_moz    = (navigator.product == 'Gecko');
var is_ns     = ( (uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) );
var is_ns4    = ( (is_ns) && (parseInt(navigator.appVersion) == 4) );
//var is_kon    = (uagent.indexOf('konqueror') != -1);

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var is_mac    = ( (uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var ua_vers   = parseInt(navigator.appVersion);

var ipb_pages_shown = 0;
var ipb_pages_array = new Array();

var ipb_skin_url = ipb_skin_url ? ipb_skin_url : ipb_var_image_url;

/*-------------------------------------------------------------------------*/
// Check check checkity box
/*-------------------------------------------------------------------------*/

function gbl_check_search_box()
{
	try
	{
		var _cb = document.getElementById( 'gbl-search-checkbox' );
		var _fd = document.getElementById( 'gbl-search-forums' );
	
		if ( _cb.checked && ipb_input_f )
		{
			_fd.value = ipb_input_f;
		}
		else
		{
			_fd.value = 'all';
		}
	}
	catch(error)
	{
	}
};

/*-------------------------------------------------------------------------*/
// Display in-line messages...
/*-------------------------------------------------------------------------*/

function show_inline_messages()
{
	var _string  = window.location.toString();
	var _msg_box = null;
	
	if ( _string.indexOf( '?___msg=' ) != -1 || _string.indexOf( ';___msg=' ) != -1 || _string.indexOf( '&___msg=' ) != -1 )
	{
		// Is this a frame? Move it to the parent...
		try
		{
			if ( parent.document.getElementById( 'ipd-msg-text' ) )
			{
				_msg_box = parent.document.getElementById( 'ipd-msg-text' );
			}
			else
			{
				_msg_box = document.getElementById( 'ipd-msg-text' );
			}
		}
		catch( error )
		{
			alert( error );
		}
		
		var message = _string.replace( /^.*[\?;&]___msg=(.+?)(&.*$|$)/, "$1" );
		message     = unescape( message );
		
		if ( message_pop_up_lang[ message ] )
		{
			try
			{
				_msg_box.innerHTML = message_pop_up_lang[ message ];

				centerdiv         = new center_div();
				centerdiv.divname = 'ipd-msg-wrapper';
				centerdiv.move_div();
				
				var _this_to = setTimeout("hide_inline_messages_instant()",2000);
			}
			catch( anerror)
			{
				alert( message_pop_up_lang[ message ] );
			}
		}
	}
};

function show_inline_messages_instant( msg )
{
	_msg_box 		   = document.getElementById( 'ipd-msg-text' );
	_msg_box.innerHTML = message_pop_up_lang[ msg ];

	centerdiv          = new center_div();
	centerdiv.divname  = 'ipd-msg-wrapper';
	centerdiv.move_div();
	
	var _this_to = setTimeout("hide_inline_messages_instant()",2000);
};

function hide_inline_messages_instant()
{
	try
	{
		document.getElementById( 'ipd-msg-wrapper' ).style.display = 'none';
		parent.document.getElementById( 'ipd-msg-wrapper' ).style.display = 'none';
	}
	catch(acold)
	{
	}
};

/*-------------------------------------------------------------------------*/
// Generate new iframe include
/*-------------------------------------------------------------------------*/

function iframe_include()
{
	this.iframe_id			   = null;
	this.iframe_obj            = null;
	this.iframe_add_to_div     = null;
	this.iframe_add_to_div_obj = null;
	this.iframe_main_wrapper   = null;
	this.iframe_classname      = 'GBL-component-iframe';
	this.ok_to_go              = 1;
	this.iframe_height         = 300;
	this.ajax                  = '';
};

iframe_include.prototype.init = function()
{
	try
	{
		this.iframe_add_to_div_obj = document.getElementById( this.iframe_add_to_div );
	}
	catch( error )
	{
		this.ok_to_go = 0;
	}
};

iframe_include.prototype.include = function( url )
{
	//-----------------------------------------
	// Check
	//-----------------------------------------
	
	if ( ! this.ok_to_go )
	{ 
		return false;
	}
	
	//-----------------------------------------
	// INIT
	//-----------------------------------------
	
	var iheight = parseInt( this.iframe_add_to_div_obj.style.height );
	var iwidth  = parseInt( this.iframe_add_to_div_obj.style.width );
	
	//-----------------------------------------
	// Generate iFrame box
	//-----------------------------------------
	
	if ( this.iframe_obj )
	{
		this.iframe_add_to_div_obj.removeChild( this.iframe_obj );
	}
	
	this.iframe_obj = document.createElement( 'IFRAME' );
	
	this.iframe_obj.src	               = url;
	this.iframe_obj.id                 = this.iframe_id;
	this.iframe_obj.name			   = this.iframe_id;
	this.iframe_obj.scrolling          = 'no';
	this.iframe_obj.frameBorder        = 'no';
	this.iframe_obj.border             = '0';
	this.iframe_obj.className          = this.iframe_classname;
	this.iframe_obj.style.width        = iwidth  ? iwidth + 'px'  : '100%';
	this.iframe_obj.style.height       = iheight ? iheight - 5 + 'px' : this.iframe_height + 'px';
	this.iframe_obj.style.overflow     = 'hidden';
	this.iframe_obj.style.padding      = '0px';
	this.iframe_obj.style.margin       = '0px';
	
	// Ajax object
	this.ajax = new ajax_request();
	
	// Fix up padding
	this.iframe_add_to_div_obj.style.padding = '0px';
	this.iframe_add_to_div_obj.style.margin  = '0px';
	
	if( is_ie && !is_ie7 )
	{
		this.iframe_add_to_div_obj.style.paddingLeft = '6px';
		this.iframe_add_to_div_obj.style.paddingRight = '6px';
	}
	
	// Add environmentals..
	this.iframe_obj.iframe_loaded      = 0;
	this.iframe_obj.iframe_init        = 0;
	this.iframe_obj._this              = this;
	
	// Attach iFrame inside our DIV
	this.iframe_add_to_div_obj.style.overflow = '';
	this.iframe_add_to_div_obj.appendChild( this.iframe_obj );
	
	this.ajax.show_loading( ajax_load_msg );
	
	// Add handler
	if ( is_ie )
	{
		this.iframe_obj.allowTransparency  = true;
		this.iframe_obj.onreadystatechange = this.iframe_on_load_ie;
	}
	else
	{
		this.iframe_obj.onload = this.iframe_onload;
	}
};

/**
* Each time the window is loaded, close
* any open messages
*/
iframe_include.prototype.iframe_onload = function( e )
{
	//-----------------------------------------
	// First load?
	//-----------------------------------------
	
	//window.frames[ this.id ].document
	
	var _document= this._this.iframe_obj.contentDocument;
	
	if ( is_safari )
	{
		_document = window.frames[ this.id ].document;
	}
	
	if ( ! this.iframe_init )
	{
		this.iframe_init   = 1;
		this.iframe_loaded = 1;
		
		
		_document.onmousedown = menu_action_close;
	}
	else
	{
		this.iframe_loaded = 1;
		
		_document.onmousedown = menu_action_close;
	}
	
	this._this.ajax.hide_loading();
	
	//-----------------------------------------
	// Attempt to fix up padding issues
	//-----------------------------------------
	
	try
	{
		_document.getElementsByTagName( 'body' )[0].style.padding = '0px';
		_document.getElementsByTagName( 'body' )[0].style.margin  = '0px';
	}
	catch(error)
	{
	}
	
	//-----------------------------------------
	// Resize... 
	//-----------------------------------------
	
	var _new_height = parseInt( _document.getElementById( this._this.iframe_main_wrapper ).offsetHeight );
	
	if ( _new_height > 0 )
	{
		if ( is_safari )
		{
			_new_height += 3;
		}
		
		this._this.iframe_obj.style.height            = _new_height + "px";
		this._this.iframe_add_to_div_obj.style.height = _new_height + "px";
	}
	
	//-----------------------------------------
	// Fix up style sheets
	//-----------------------------------------
	
	var style      = document.getElementsByTagName( 'style' );
	var _new_style = '';
	
	for( i in style )
	{
		_new_style += "\n" + style[i].innerHTML;
	}
	
	try
	{
		_document.getElementsByTagName( 'style' )[0].innerHTML = _new_style;
	}
	catch(error)
	{
	}
};

/**
* IE only calls onload once... So subsequent loads
* don't do anything.. hence the readystate change
*/
iframe_include.prototype.iframe_on_load_ie = function( e )
{
	//-----------------------------------------
	// First load?
	//-----------------------------------------
	
	if ( this.readyState == 'complete' )
	{
		var _document = '';
		
		if ( this._this.iframe_obj.contentWindow )
		{
			_document = this._this.iframe_obj.contentWindow.document;
		}
		else if ( this._this.iframe_obj.document )
		{
			_document = this._this.iframe_obj.document;
		}
		else
		{
			_document = window.frames[ this.id ].document;
		}
		
		if ( ! this.iframe_init )
		{
			this.iframe_init   = 1;
			this.iframe_loaded = 1;
			_document.onmousedown = menu_action_close;
		}
		else
		{
			this.iframe_loaded = 1;
			_document.onmousedown = menu_action_close;
		}
		
		//-----------------------------------------
		// Fix up style sheets
		//-----------------------------------------
		
		var style      = document.getElementsByTagName( 'style' );
		var _new_style = '';

		for( i in style )
		{
			if ( style[i].innerHTML )
			{
				_new_style += "\n" + style[i].innerHTML;
			}
		}
		
		var _urls = _new_style.match( /@import\s+?url\(\s+?['"](.+?)['"]\s+?\);/ig );
		
		if ( _urls && _urls.length )
		{
			for( i = 0 ; i <= _urls.length ; i++ )
			{
				if ( typeof( _urls[i] ) != 'undefined' )
				{
					_urls[i] = _urls[i].replace( /@import\s+?url\(\s+?['"](.+?)['"]\s+?\);/ig, "$1" );
				
					if ( typeof( _urls[i] ) != 'undefined' )
					{
						_document.createStyleSheet( _urls[i] );
					}
				}
			}
		}
		
		this._this.ajax.hide_loading();
		
		//-----------------------------------------
		// Attempt to fix up padding issues
		//-----------------------------------------

		try
		{
			_document.getElementsByTagName( 'body' )[0].style.padding = '0px';
			_document.getElementsByTagName( 'body' )[0].style.margin  = '0px';
		}
		catch(error)
		{
		}
		
		//-----------------------------------------
		// Resize... 
		//-----------------------------------------

		var _new_height = parseInt( _document.getElementById( this._this.iframe_main_wrapper ).offsetHeight );
		var _new_width  = parseInt( _document.getElementById( this._this.iframe_main_wrapper ).offsetWidth );
		
		if ( _new_height > 0 )
		{
			this._this.iframe_obj.style.height            = _new_height + "px";
			this._this.iframe_add_to_div_obj.style.height = _new_height + "px";
		}
		
		if ( _new_width > 0 )
		{
			this._this.iframe_obj.style.width            = _new_width + "px";
			this._this.iframe_add_to_div_obj.style.width = _new_width + "px";
		}
	}
};

/*-------------------------------------------------------------------------*/
// Fix IE PNG images
/*-------------------------------------------------------------------------*/

function ie_fix_png()
{
	if ( is_ie )
	{
		document.onreadystatechange = ie_fix_png_do;
	}
}

function ie_fix_png_do()
{
	if ( document.readyState == 'complete' )
	{
		var pos     = navigator.userAgent.indexOf("MSIE ");
		var version = navigator.userAgent.substring(pos + 5);
		var blanky  = ipb_skin_url + "/blank.gif";
		var _sw     = screen.width * ( parseInt( ipsclass.settings['resize_percent'] ) / 100 );
		
		if (pos == -1)
		{
			return false;
		}
	
		if ( ! ((version.indexOf("5.5") == 0) || (version.indexOf("6") == 0)) && (navigator.platform == ("Win32")) )
		{
			return;
		}
	
		var images = document.getElementsByTagName( 'IMG' );
		var _len   = images.length;
		
		if ( _len )
		{
			for ( var i = 0 ; i < _len ; i++ )
			{
				if ( images[i].src.match( /\.png$/ ) )
				{
					var element = images[i];
					var _width  = 0;
					var _height = 0;
					var _src    = 0;
					
					element._width   = element._width ? parseInt( element._width ) : 0;
					element._resized = parseInt( element._resized );
					
					if ( ! element.style.width )
					{
						_width = element.width;
					}

					if ( ! element.style.height )
					{
						_height = element.height;
					}
				
					_src        = element.src;
					
					//-----------------------------------------
					// Prevent PNG clash with topic overwrite
					//-----------------------------------------
					
					if ( _width < _sw && ! element._resized && element._width < _sw )
					{
						element.src = blanky;
						
						if ( _width )
						{
							element.style.width  = _width+"px";
						}
						if ( _height )
						{
							element.style.height = _height+"px";
						}

						element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + _src + "',sizingMethod='scale')";
					}
					else
					{
						//alert( 'Skipped: ' + images[i].src );
					}
				}
			}
		}
	}
}

/*-------------------------------------------------------------------------*/
// Add onload event
/*-------------------------------------------------------------------------*/

function add_onload_event( func )
{
	var oldonload = window.onload;

	if (typeof window.onload != 'function')
	{
    	window.onload = func;

  	}
  	else
  	{
    	window.onload = function()
		{
      		if ( oldonload )
      		{
        		oldonload();
      		};

      		func();
    	};
  	}
}		

/*-------------------------------------------------------------------------*/
// Add shadow to an ID
/*-------------------------------------------------------------------------*/

function add_shadow( wrapname, divname )
{
	var divobj  = document.getElementById( divname );
	var wrapobj = document.getElementById( wrapname );
	
	//----------------------------------
	// Transform the DIV
	//----------------------------------
	
	if ( is_ie )
	{
		wrapobj.className      = 'shadow-ie';
		wrapobj.style.width    = divobj.offsetWidth  + 1 + 'px';
		wrapobj.style.height   = divobj.offsetHeight + 1 + 'px';
	}
	else
	{
		wrapobj.className      = 'shadow-moz';
		wrapobj.style.width    = divobj.offsetWidth  + 0 + 'px';
		wrapobj.style.height   = divobj.offsetHeight + 0 + 'px';
	}
}

/*-------------------------------------------------------------------------*/
// DST Auto correction
/*-------------------------------------------------------------------------*/

function global_dst_check( tzo, dst )
{
	var thisoffset = tzo + dst;
	var dstoffset  = new Date().getTimezoneOffset() / 60;
	var dstset     = 0;
	var url        = ipb_var_base_url + 'act=xmlout&do=dst-autocorrection&md5check=' + ipb_md5_check;
	
	if ( Math.abs( thisoffset + dstoffset ) == 1 )
	{
		try
		{
			//----------------------------------
			// Fancy first...
			//----------------------------------
			
			xml_dst_set( url + '&xml=1' );
			dstset = 1;
		}
		catch(e)
		{
			dstset = 0;
		}
		
		//----------------------------------
		// No fancy?
		//----------------------------------
		
		if ( dstset == 0 )
		{
			window.location = url;
		}
	}
}

/*-------------------------------------------------------------------------*/
// Get cookie
/*-------------------------------------------------------------------------*/

function my_getcookie( name )
{
	return ipsclass.my_getcookie( name );
}

/*-------------------------------------------------------------------------*/
// Set cookie
/*-------------------------------------------------------------------------*/

function my_setcookie( name, value, sticky )
{
	return ipsclass.my_setcookie( name, value, sticky );
}

/*-------------------------------------------------------------------------*/
// Lang replace
/*-------------------------------------------------------------------------*/

function lang_build_string()
{
	if ( ! arguments.length || ! arguments )
	{
		return;
	}
	
	var string = arguments[0];
	
	for( var i = 1 ; i < arguments.length ; i++ )
	{
		var match  = new RegExp('<%' + i + '>', 'gi');
		string = string.replace( match, arguments[i] );
	}
	
	return string;
}

/*-------------------------------------------------------------------------*/
// Pop up friends window
/*-------------------------------------------------------------------------*/

function friends_pop( extra_url )
{
	ipb_var_base_url = ipb_var_base_url.replace( '&amp;', '&' );
	
	if ( extra_url )
	{
		extra_url = extra_url.replace( '&amp;', '&' );
	}
	else
	{
		extra_url = '';
	}
		
	ipsclass.pop_up_window( ipb_var_base_url + 'act=profile&CODE=friends_list_popup' + extra_url, 450, 400, 'Friends' );
}

/*-------------------------------------------------------------------------*/
// Pop up MyAssistant window
/*-------------------------------------------------------------------------*/

function buddy_pop()
{
	var not_loaded_yet = 0;
	
	if ( use_enhanced_js )
	{
		try
		{
			xml_myassistant_init();
			not_loaded_yet = 1;
		}
		catch( e )
		{
			//alert(e);
			not_loaded_yet = 0;
		}
	}
	
	if ( ! not_loaded_yet )
	{
		ipb_var_base_url = ipb_var_base_url.replace( '&amp;', '&' );
		window.open( ipb_var_base_url + 'act=buddy','BrowserBuddy','width=250,height=500,resizable=yes,scrollbars=yes');
	}
}

/*-------------------------------------------------------------------------*/
// Multi Page jumps
/*-------------------------------------------------------------------------*/

function check_enter( pages_id, e )
{
	var keypress = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	
	if( keypress == 13 )
	{
		do_multi_page_jump( pages_id );
	}
}

function do_multi_page_jump( pages_id )
{
	var pages       = 1;
	var cur_st      = ipb_var_st;
	var cur_page    = 1;
	var total_posts = ipb_pages_array[ pages_id ][2];
	var per_page    = ipb_pages_array[ pages_id ][1];
	var url_bit     = ipb_pages_array[ pages_id ][0];
	var userPage    = parseInt( document.getElementById( 'st-'+pages_id ).value );
	
	var st_type		= document.getElementById( 'st-type-'+pages_id ).value;
	
	st_type			= st_type ? st_type : 'st';

	//-----------------------------------
	// Fix up URL BIT
	//-----------------------------------
	
	url_bit = url_bit.replace( new RegExp( "&amp;", "g" ) , '&' );
	
	//-----------------------------------
	// Work out pages
	//-----------------------------------
	
	if ( total_posts % per_page == 0 )
	{
		pages = total_posts / per_page;
	}
	else
	{
		pages = Math.ceil( total_posts / per_page );
	}
	
	if ( cur_st > 0 )
	{
		cur_page = cur_st / per_page; cur_page = cur_page -1;
	}

	if ( userPage > 0  )
	{
		if ( userPage < 1 )     {    userPage = 1;  }
		if ( userPage > pages ) { userPage = pages; }
		if ( userPage == 1 )    {     start = 0;    }
		else { start = (userPage - 1) * per_page; }
		
		if ( start )
		{
			window.location = url_bit + "&" + st_type + "=" + start;
		}
		else
		{
			window.location = url_bit;
		}
		
		return false;
	}
}

/*-------------------------------------------------------------------------*/
// Hide / Unhide menu elements
/*-------------------------------------------------------------------------*/

function pages_st_focus( pages_id )
{
	document.getElementById( 'st-'+pages_id ).focus();
}

/*-------------------------------------------------------------------------*/
// Hide / Unhide menu elements
/*-------------------------------------------------------------------------*/

function ShowHide(id1, id2)
{
	if (id1 != '') toggleview(id1);
	if (id2 != '') toggleview(id2);
}
	
/*-------------------------------------------------------------------------*/
// Get element by id
/*-------------------------------------------------------------------------*/

function my_getbyid(id)
{
	itm = null;
	
	if (document.getElementById)
	{
		itm = document.getElementById(id);
	}
	else if (document.all)
	{
		itm = document.all[id];
	}
	else if (document.layers)
	{
		itm = document.layers[id];
	}
	
	return itm;
}

/*-------------------------------------------------------------------------*/
// Show/hide toggle
/*-------------------------------------------------------------------------*/

function toggleview(id)
{
	if ( ! id ) return;
	
	if ( itm = my_getbyid(id) )
	{
		if (itm.style.display == "none")
		{
			my_show_div(itm);
		}
		else
		{
			my_hide_div(itm);
		}
	}
}

/*-------------------------------------------------------------------------*/
// Set DIV ID to hide
/*-------------------------------------------------------------------------*/

function my_hide_div(itm)
{
	if ( ! itm ) return;
	
	itm.style.display = "none";
}

/*-------------------------------------------------------------------------*/
// Set DIV ID to show
/*-------------------------------------------------------------------------*/

function my_show_div(itm)
{
	if ( ! itm ) return;
	
	itm.style.display = "";
}

/*-------------------------------------------------------------------------*/
// Change cell colour
/*-------------------------------------------------------------------------*/

function change_cell_color( id, cl )
{
	itm = my_getbyid(id);
	
	if ( itm )
	{
		itm.className = cl;
	}
}

/*-------------------------------------------------------------------------*/
// Toggle category
/*-------------------------------------------------------------------------*/

function togglecategory( fid, add )
{
	saved = new Array();
	clean = new Array();

	//-----------------------------------
	// Get any saved info
	//-----------------------------------
	
	if ( tmp = ipsclass.my_getcookie('collapseprefs') )
	{
		saved = tmp.split(",");
	}
	
	//-----------------------------------
	// Remove bit if exists
	//-----------------------------------
	
	for( i = 0 ; i < saved.length; i++ )
	{
		if ( saved[i] != fid && saved[i] != "" )
		{
			clean[clean.length] = saved[i];
		}
	}
	
	//-----------------------------------
	// Add?
	//-----------------------------------
	
	if ( add )
	{
		clean[ clean.length ] = fid;
		my_show_div( my_getbyid( 'fc_'+fid  ) );
		my_hide_div( my_getbyid( 'fo_'+fid  ) );
	}
	else
	{
		my_show_div( my_getbyid( 'fo_'+fid  ) );
		my_hide_div( my_getbyid( 'fc_'+fid  ) );
	}
	
	ipsclass.my_setcookie( 'collapseprefs', clean.join(','), 1 );
}

/*-------------------------------------------------------------------------*/
// locationjump
/*-------------------------------------------------------------------------*/

function locationjump(url)
{
	window.location = ipb_var_base_url + url;
}

/*-------------------------------------------------------------------------*/
// CHOOSE SKIN
/*-------------------------------------------------------------------------*/

function chooseskin(obj)
{
	choosebox = obj.options[obj.selectedIndex].value;
	extravars = '';
	
	if ( choosebox != -1 && ! isNaN( choosebox ) )
	{
		if ( document.skinselectorbox.skinurlbits.value )
		{
			extravars = '&' + document.skinselectorbox.skinurlbits.value;
			
			//----------------------------------
			// Strip out old skin change stuff
			// setskin=1&skinid=2
			//----------------------------------
			
			extravars = extravars.replace( /setskin=\d{1,}/g, ''  );
			extravars = extravars.replace( /skinid=\d{1,}/g , ''  );
			extravars = extravars.replace( /cal_id=&/g, ''  );
			extravars = extravars.replace( /&{1,}/g         , '&' );
			extravars = extravars.replace( /s=&/g           , ''  );
		}
		
		locationjump( 'setskin=1&skinid=' + choosebox + extravars );
	}
}

/*-------------------------------------------------------------------------*/
// CHOOSE LANG
/*-------------------------------------------------------------------------*/

function chooselang(obj)
{
	choosebox = obj.options[obj.selectedIndex].value;
	extravars = '';
	
	if ( document.langselectorbox.langurlbits.value )
	{
		extravars = '&' + document.langselectorbox.langurlbits.value;

		//----------------------------------
		// Strip out old skin change stuff
		// setskin=1&skinid=2
		//----------------------------------
			
		extravars = extravars.replace( /setlanguage=\d{1,}/g, ''  );
		extravars = extravars.replace( /cal_id=&/g, ''  );
		extravars = extravars.replace( /langid=\w{1,}/g , ''  );
		extravars = extravars.replace( /&{1,}/g         , '&' );
		extravars = extravars.replace( /s=&/g           , ''  );
	}
	
	locationjump( 'setlanguage=1&langid=' + choosebox + extravars );
}

/*-------------------------------------------------------------------------*/
// pop up window
/*-------------------------------------------------------------------------*/

function PopUp(url, name, width,height,center,resize,scroll,posleft,postop)
{
	showx = "";
	showy = "";
	
	if (posleft != 0) { X = posleft }
	if (postop  != 0) { Y = postop  }
	
	if (!scroll) { scroll = 1 }
	if (!resize) { resize = 1 }
	
	if ((parseInt (navigator.appVersion) >= 4 ) && (center))
	{
		X = (screen.width  - width ) / 2;
		Y = (screen.height - height) / 2;
	}
	
	if ( X > 0 )
	{
		showx = ',left='+X;
	}
	
	if ( Y > 0 )
	{
		showy = ',top='+Y;
	}
	
	if (scroll != 0) { scroll = 1 }
	
	var Win = window.open( url, name, 'width='+width+',height='+height+ showx + showy + ',resizable='+resize+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no');
}

/*-------------------------------------------------------------------------*/
// Array: Get stack size
/*-------------------------------------------------------------------------*/

function stacksize(thearray)
{
	for (i = 0 ; i < thearray.length; i++ )
	{
		if ( (thearray[i] == "") || (thearray[i] == null) || (thearray == 'undefined') )
		{
			return i;
		}
	}
	
	return thearray.length;
}

/*-------------------------------------------------------------------------*/
// Array: Push stack
/*-------------------------------------------------------------------------*/

function pushstack(thearray, newval)
{
	arraysize = stacksize(thearray);
	thearray[arraysize] = newval;
}

/*-------------------------------------------------------------------------*/
// Array: Pop stack
/*-------------------------------------------------------------------------*/

function popstack(thearray)
{
	arraysize = stacksize(thearray);
	theval = thearray[arraysize - 1];
	delete thearray[arraysize - 1];
	return theval;
}

/*-------------------------------------------------------------------------*/
// Converts "safe" innerHTML back to normal template
/*-------------------------------------------------------------------------*/

function innerhtml_template_to_html( t )
{
	t = t.replace( /&lt;%(\d+?)&gt;/ig, "<%$1>" );
	t = t.replace( /%3C%(\d+?)%3E/ig  , "<%$1>" );
	return t;
}

/*-------------------------------------------------------------------------*/
// Global freeze events
/*-------------------------------------------------------------------------*/

function global_cancel_bubble(obj, extra)
{
	if ( ! obj || is_ie)
	{
		if ( extra )
		{
			window.event.returnValue = false;
		}
		
		window.event.cancelBubble = true;
		
		return window.event;
	}
	else
	{
		obj.stopPropagation();
		
		if ( extra )
		{
			obj.preventDefault();
		}
		
		return obj;
	}
}

/*-------------------------------------------------------------------------*/
// Get left posititon of object
/*-------------------------------------------------------------------------*/

function _get_obj_leftpos(obj)
{
	var left = obj.offsetLeft;
	
	while( (obj = obj.offsetParent) != null )
	{
		left += obj.offsetLeft;
	}
	
	return left;
}

/*-------------------------------------------------------------------------*/
// Get top position of object
/*-------------------------------------------------------------------------*/

function _get_obj_toppos(obj)
{
	var top = obj.offsetTop;
	
	while( (obj = obj.offsetParent) != null )
	{
		top += obj.offsetTop;
	}
	
	return top;
}

/*-------------------------------------------------------------------------*/
// Center a div on the screen
/*-------------------------------------------------------------------------*/

function center_div()
{
	this.divname = '';
	this.divobj  = '';
	this.shimobj = '';
}

/*-------------------------------------------------------------------------*/
// Main run function
/*-------------------------------------------------------------------------*/

center_div.prototype.move_div = function()
{
	try
	{
		if ( parent.document.getElementById( this.divname ) )
		{
			this._document = parent.document;
			this._window   = parent.window;
		}
	}
	catch(e)
	{
		return;
	}
	
	this.divobj = this._document.getElementById( this.divname );
	
	//----------------------------------
	// Figure width and height
	//----------------------------------
	
	var my_width  = 0;
	var my_height = 0;
	
	if ( typeof( this._window.innerWidth ) == 'number' )
	{
		//----------------------------------
		// Non IE
		//----------------------------------
	  
		my_width  = this._window.innerWidth;
		my_height = this._window.innerHeight;
	}
	else if ( this._document.documentElement && ( this._document.documentElement.clientWidth || this._document.documentElement.clientHeight ) )
	{
		//----------------------------------
		// IE 6+
		//----------------------------------
		
		my_width  = this._document.documentElement.clientWidth;
		my_height = this._document.documentElement.clientHeight;
	}
	else if ( this._document.body && ( this._document.body.clientWidth || this._document.body.clientHeight ) )
	{
		//----------------------------------
		// Old IE
		//----------------------------------
		
		my_width  = this._document.body.clientWidth;
		my_height = this._document.body.clientHeight;
	}
	
	//----------------------------------
	// Show (but behind the zIndex...
	//----------------------------------
	
	this.divobj.style.position = 'absolute';
	this.divobj.style.display  = 'block';
	this.divobj.style.zIndex   = -1;
	
	if ( is_ie )
	{
		var layer_html      = this.divobj.innerHTML;
		var full_html 		= "<iframe id='" + this.divname + "-shim' src='" + ipb_var_image_url + "/iframe.html' class='iframshim' scrolling='no' frameborder='0' style='position:absolute; top:0px; left:0px; right:0px; display: none;'></iframe>" + layer_html;
		
		this.divobj.innerHTML = full_html;
	}		

	//----------------------------------
	// Stop IE showing select boxes over
	// floating div [ 2 ]
	//----------------------------------
	
	if ( is_ie )
	{
		this.shimobj               = this._document.getElementById( this.divname + "-shim" );
		this.shimobj.style.width   = (parseInt(this.divobj.offsetWidth) -5) + "px";
		this.shimobj.style.height  = this.divobj.offsetHeight;
		this.shimobj.style.zIndex  = this.divobj.style.zIndex - 1;
		this.shimobj.style.top     = this.divobj.style.top;
		this.shimobj.style.left    = this.divobj.style.left;
		this.shimobj.style.display = "block";
	}		
	
	//----------------------------------
	// Get div height && width
	//----------------------------------
	
	var divheight = parseInt( this.divobj.style.height ) ? parseInt( this.divobj.style.height ) : parseInt( this.divobj.offsetHeight );
	var divwidth  = parseInt( this.divobj.style.width )  ? parseInt( this.divobj.style.width )  : parseInt( this.divobj.offsetWidth );
	
	divheight = divheight ? divheight : 200;
	divwidth  = divwidth  ? divwidth  : 400;
	
	//----------------------------------
	// Get current scroll offset
	//----------------------------------
	
	var scrolly = this.getYscroll();
	
	//----------------------------------
	// Finalize...
	//----------------------------------

	var setX = ( my_width  - divwidth  ) / 2;
	var setY = ( my_height - divheight ) / 2 + scrolly;
	
	setX = ( setX < 0 ) ? 0 : setX;
	setY = ( setY < 0 ) ? 0 : setY;
	
	this.divobj.style.left = setX + "px";
	this.divobj.style.top  = setY + "px";
	
	//----------------------------------
	// Show for real...
	//----------------------------------
	
	this.divobj.style.zIndex = 99;
};

/*-------------------------------------------------------------------------*/
// Hide div
/*-------------------------------------------------------------------------*/

center_div.prototype.hide_div = function()
{
	try
	{
		if ( ! this.divobj )
		{
			return;
		}
		else
		{
			this.divobj.style.display  = 'none';
		}
	}
	catch(e)
	{
		return;
	}
};

/*-------------------------------------------------------------------------*/
// Get YScroll
/*-------------------------------------------------------------------------*/

center_div.prototype.getYscroll = function()
{
	var scrollY = 0;
	
	if ( this._document.documentElement && this._document.documentElement.scrollTop )
	{
		scrollY = this._document.documentElement.scrollTop;
	}
	else if ( this._document.body && this._document.body.scrollTop )
	{
		scrollY = this._document.body.scrollTop;
	}
	else if ( this._window.pageYOffset )
	{
		scrollY = this._window.pageYOffset;
	}
	else if ( this._window.scrollY )
	{
		scrollY = this._window.scrollY;
	}
	
	return scrollY;
};