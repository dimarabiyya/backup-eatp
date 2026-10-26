/**
 * ttg <tatangajah@gmail.com>
 */

var mainMenu = function() {
	var menu = document.getElementById('menu');
	var parent = menu.children[2].children; // 2 = ul
	var menuId = getCookie('menuId');
	var selectedMenuIdx = getCookie('selectedMenuIdx');
	var visitedURL = getCookie('visitedURL');
	
	for (var i = 0; i < parent.length; i++) {
		if (parent[i].children[1] != undefined) {
			for (var j = 0; j < parent[i].children[1].children.length; j++) {
				
				(function(idx) {
					parent[i].children[1].children[idx].children[0].onclick = function() {
						createCookie('menuId', this.parentNode.parentNode.id, 1);
						createCookie('selectedMenuIdx', idx, 1);
						createCookie('visitedURL', this.href, 1);
					};
				})(j);
				
				if (visitedURL != '') {
					if (visitedURL != document.URL) {
						//eraseCookie('visitedURL');
						//eraseCookie('menuId');
						//eraseCookie('selectedMenuIdx');
					}
				}
				
				if (menuId != '') {
					if (parent[i].children[1].id == menuId) {
						if (selectedMenuIdx != '') {
							parent[i].children[1].children[selectedMenuIdx].setAttribute('class', 'active active-menu');
							parent[i].children[1].children[j].parentNode.setAttribute('class', 'in collapse');
							parent[i].children[1].children[j].parentNode.parentNode.setAttribute('class', 'hasSubmenu active');
						}
					}
				}
				
				if (menuId == '' && selectedMenuIdx == '' && visitedURL == '') {
					// 2nd hirarcy
					if (parent[i].children[1].children[j].children[0].href == document.URL) {
						parent[i].setAttribute('class', 'hasSubmenu active');
						parent[i].children[1].setAttribute('class', 'in collapse');
						parent[i].children[1].children[j].setAttribute('class', 'active active-menu');
					}
					
					// 3rd hirarcy
					if (parent[i].children[1].children[j].className == 'hasSubmenu') {
						for (var k = 0; k < parent[i].children[1].children[j].children[1].children.length; k++) {
							if (parent[i].children[1].children[j].children[1].children[k].children[0].href == document.URL) {
								parent[i].children[1].setAttribute('class', 'in collapse');
								parent[i].children[1].parentNode.setAttribute('class', 'hasSubmenu active');
								parent[i].children[1].children[j].children[1].setAttribute('class', 'in collapse');
								parent[i].children[1].children[j].children[1].children[k].setAttribute('class', 'active active-menu');
							}
						}
					}
				}
			}
		}
	}
};

var createCookie = function(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
};

var getCookie = function(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
};

eraseCookie = function(name) {
    createCookie(name, "", -1);
};