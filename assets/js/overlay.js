var siteOverlay = {
	
	/**
	 * for window overlay
	 * Author: ttg <tatangalmubarak@gmail.com>
	 * Desc: hehehe...
	 */
		
	message: null,	// text message
	left: null,		// pixel
	top: null,		// pixel
	create: function() {
		var overlay = document.createElement('div');
		
		overlay.setAttribute('id', 'site-overlay');
		overlay.style.position = 'fixed';
		overlay.style.top = '0px';
		overlay.style.bottom = '0px';
		overlay.style.zIndex = 99999;
		overlay.style.width = '100%';
		overlay.style.height = '100%';
		overlay.style.background = 'rgba(0, 0, 0, 0.6)';
		document.body.appendChild(overlay);

		var div = document.createElement('div');
		div.setAttribute('class','loading');
		div.style.margin = '0 10px 0 0';

		var h1 = document.createElement('h1');
		if (this.message == null) {
			this.message = document.createTextNode('Loading, please wait...');
		} 
		//Sory ya mas tang,ditambahin gambar :D #alen

		h1.setAttribute('id', 'overlay-message');
		h1.style.width = '300px';
		h1.appendChild(div);
		h1.appendChild(this.message);
		overlay.appendChild(h1);
		
		var windowWidth = document.body.clientWidth;
		var windowHeight = document.body.clientHeight;
		var h1Width = document.getElementById('overlay-message').offsetWidth;
		var h1Height = document.getElementById('overlay-message').offsetHeight;
		if (this.left == null) {
			this.left = (windowWidth - h1Width) / 2 + 'px';
		}
		if (this.top == null) {
			this.top = (windowHeight - h1Height) / 2 + 'px';
		}
		
		var overlayMessage = document.getElementById('overlay-message');
		overlayMessage.style.position = 'absolute';
		overlayMessage.style.zIndex = 999999;
		overlayMessage.style.top = this.top;
		overlayMessage.style.left = this.left;
		overlayMessage.style.width = '300px';
		overlayMessage.style.border = '2px solid #468847';
		overlayMessage.style.background = '#FCF8E3';
		overlayMessage.style.textAlign = 'center';
		overlayMessage.style.padding = '10px';
		overlayMessage.style.color = '#C09853';
		overlayMessage.style.fontSize = '16px';
	},
	remove: function() {
		var siteOverlay = document.getElementById('site-overlay');
		
		if (siteOverlay != null) {
			siteOverlay.parentNode.removeChild(siteOverlay);
		}
	}
};