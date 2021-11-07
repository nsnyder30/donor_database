/*----------------------------------------------DISABLE CLIENT COOKIE SETTING---------------------------------------------*/
if(!document.__defineGetter__) {
	Object.defineProperty(document, 'cookie', {
		get: function(){return ''}, 
		set: function(){return true}
	});
} else {
	document.__defineGetter__("cookie", function() {return '';});
	document.__defineSetter__("cookie", function() {} );
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*-------------------------------------FUNCTION: ADD FUNCTIONS TO WINDOW ONLOAD EVENT-------------------------------------*/
if(typeof addOnLoad !== 'function')
{
	function addOnLoad(newFunction){
		var oldOnLoad = window.onload;
		if(typeof oldOnLoad == 'function')
		{
			window.onload = function(){
				if(oldOnLoad)
					{oldOnLoad();}
				newFunction();
			}
		} else 
			{window.onload = newFunction;}
	}
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*---------------------------------------FUNCTION: DETECT INTERNET EXPLORER BROWSER---------------------------------------*/
if(typeof detect_ie !== 'function')
{
	function detect_ie() 
	{
		var ua = window.navigator.userAgent;
		return ua.indexOf("MSIE ") > -1 || ua.indexOf('Internet Explorer') > -1 || ua.indexOf('Trident/7.0') > -1 || ua.indexOf('rv:11.0') > -1;
	}
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*--------------------------------------FUNCTIONS: MAP PERCENTAGE TO COLOR GRADIENT---------------------------------------*/
if(typeof color_gradient_hex !== 'function')
{
	function color_gradient_hex(color1, color2, pcnt)
	{
		pcnt_inv = 1 - pcnt;
		var c1hex = /#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})/i.exec(color1);
		var c1rgb = c1hex ? {
			r: parseInt(c1hex[1], 16),
			g: parseInt(c1hex[2], 16),
			b: parseInt(c1hex[3], 16)
		} : null;
		var c2hex = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color2);
		var c2rgb = c1hex ? {
			r: parseInt(c2hex[1], 16),
			g: parseInt(c2hex[2], 16),
			b: parseInt(c2hex[3], 16)
		} : null;
		
		var rgb = null;
		if(c1rgb !== null && c2rgb !== null)
		{
			rgb = [Math.round(c1rgb['r'] * pcnt_inv + c2rgb['r'] * pcnt), 
				   Math.round(c1rgb['g'] * pcnt_inv + c2rgb['g'] * pcnt), 
				   Math.round(c1rgb['b'] * pcnt_inv + c2rgb['b'] * pcnt)];
		}
		
		var hex = null;
		if(rgb !== null)
		{
			hex1 = rgb[0].toString(16).length == 1 ? "0" + rgb[0].toString(16) : rgb[0].toString(16);
			hex2 = rgb[1].toString(16).length == 1 ? "0" + rgb[1].toString(16) : rgb[1].toString(16);
			hex3 = rgb[2].toString(16).length == 1 ? "0" + rgb[2].toString(16) : rgb[2].toString(16);
			hex = "#" + hex1 + hex2 + hex3;
		}
		return hex;
	}
}

if(typeof color_gradient_rgb !== 'undefined')
{
	function color_gradient_rgb(color1, color2, pcnt)
	{
		var pcnt_inv = 1 - pcnt;
		var rgb = [Math.round(color1[0] * pcnt_inv + color2[0] * pcnt), 
				   Math.round(color1[1] * pcnt_inv + color2[1] * pcnt), 
				   Math.round(color1[2] * pcnt_inv + color2[2] * pcnt)];
		return rgb;
	}
}

if(typeof componentToHex !== 'undefined')
{
	function componentToHex(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}
}

if(typeof rgbToHex !== 'undefined')
{
	function rgbToHex(r, g, b) {
		return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
	}
}

if(typeof hexToRgb !== 'undefined')
{
	function hexToRgb(hex) {
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*--------------------------------------FUNCTION: DETERMINE SIZE OF TEXT IN BROWSER---------------------------------------*/
if(typeof BrowserText === 'undefined')
{
	var BrowserText = (function () {
		var canvas = document.createElement('canvas'),
			context = canvas.getContext('2d');

		/**
		 * Measures the rendered width of arbitrary text given the font size and font face
		 * @param {string} text The text to measure
		 * @param {number} fontSize The font size in pixels
		 * @param {string} fontFace The font face ("Arial", "Helvetica", etc.)
		 * @returns {number} The width of the text
		 **/
		function getWidth(text, fontSize, fontFace) {
			fontFace = fontFace || 'Arial';
			fontSize = fontSize || 12;
			context.font = fontSize + 'px ' + fontFace;
			return context.measureText(text).width;
		}

		return {
			getWidth: getWidth
		};
	})();
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*---------------------------------------FUNCTION: CONVERT DATE TO YYYY-MM-DD FORMAT--------------------------------------*/
if(typeof formatNumber !== 'function')
{
	function formatNumber(x, d) {
		d = d || 0;
		let scale = Math.pow(10, d);
		let val = parseFloat((Math.round(x*scale)/scale)).toFixed(d).toLocaleString();
		let val_split = val.toString().split('.');
		val = val_split[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		if(typeof val_split[1] !== 'undefined')
			{val = val + '.'+val_split[1];}
		return val;
	}
}
/*------------------------------------------------------------------------------------------------------------------------*/


/*---------------------------------------FUNCTION: CONVERT DATE TO YYYY-MM-DD FORMAT--------------------------------------*/
if(typeof DateFormat === 'undefined')
{
	var DateFormat = function(dt) {
		let yr = dt.getFullYear().toString();
		let mo = (dt.getMonth()+1).toString();
		let dy = dt.getDate.toString();
		
		mo = mo.length == 1 ? '0'+mo : mo;
		dy = dy.length == 1 ? '0'+dy : dy;
		return [yr,mo,dy].join('-');
	};
}
/*------------------------------------------------------------------------------------------------------------------------*/