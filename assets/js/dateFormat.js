var dateFormat = {
	monthsName: { '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug', '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec' },
	monthsCode: { 'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12' },
	fields: document.getElementsByClassName('datepicker'),
	init: function() {
		$('.datepicker').datepicker({
			dateFormat: 'dd-M-yy',
			changeMonth: true,
			changeYear: true
		}).css({"z-index":8300});
		this.defaultMode();
		
		for (var i = 0; i < this.fields.length; i++) {
			(function(idx) {
				dateFormat.fields[idx].setAttribute("placeholder","DD-MM-YYYY");



				dateFormat.fields[idx].onfocus = function() {
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsCode[month] != 'undefined') {						
						this.value = day + '-' + dateFormat.monthsCode[month] + '-' + year;
					}
				};
				dateFormat.fields[idx].onblur = function(e) {
					if (this.value != '') {
						if (!dateFormat.checkValidDate(this.value)) {
							setTimeout(function() {
								e.target.select();
							});
						}
					}
					
					var day = this.value.split('-')[0];
					var month = this.value.split('-')[1];
					var year = this.value.split('-')[2];
					
					if (typeof dateFormat.monthsName[month] != 'undefined') {
						this.value = day + '-' + dateFormat.monthsName[month] + '-' + year;
					}
				};
				dateFormat.fields[idx].onkeypress = function(e) {
					var restrictionFormat = /[0-9\-]/g; // cuma angka dan dash
					
					if (!e) var e = window.event
					if (e.keyCode) code = e.keyCode;
					else if (e.which) code = e.which;
					var character = String.fromCharCode(code);

					// if they pressed esc... remove focus from field...
					if (code == 27) { this.blur(); return false; }
					
					// ignore if they are press other keys
					// strange because code: 39 is the down key AND ' key...
					// and DEL also equals .
					if (!e.ctrlKey && code != 9 && code != 8 && code != 36 && code != 37 && code != 38 && (code != 39 || (code == 39 && character == "'")) && code != 40) {
						if (character.match(restrictionFormat)) {
							return true;
						} else {
							return false;
						}
						
					}
				};
			
			})(i);
		}
	},
	
	defaultMode: function() { // Call this after call 'saveMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[2];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[0];
			
				if (typeof this.monthsName[month] != 'undefined') {
					this.fields[i].value = day + '-' + this.monthsName[month] + '-' + year;
				}
			}
		}
	},
	saveMode: function() { // Call this after call 'defaultMode'
		for (var i = 0; i < this.fields.length; i++) {
			if (this.fields[i].value != '') {
				var day = this.fields[i].value.split('-')[0];
				var month = this.fields[i].value.split('-')[1];
				var year = this.fields[i].value.split('-')[2];
			
				if (typeof this.monthsCode[month] != 'undefined') {
					this.fields[i].value = year + '-' + this.monthsCode[month] + '-' + day;
				}
			}
		}
	},	
	checkValidDate: function(strDate) {
		var validformat = /^\d{2}-\d{2}-\d{4}$/; // Basic check for format validity
		var returnval = false;
		
		var monthfield = strDate.split('-')[1];
		if (monthfield.length==3){
			returnval=true
		}else{
			if (!validformat.test(strDate)) {
				alert('Invalid date format.');
			} else { // Detailed check for valid date ranges
				var monthfield = strDate.split('-')[1];
				var dayfield = strDate.split('-')[0];
				var yearfield = strDate.split('-')[2];
				var dayobj = new Date(yearfield, monthfield - 1, dayfield);
				if ((dayobj.getMonth() + 1) != monthfield || dayobj.getDate() != dayfield || dayobj.getFullYear() != yearfield) {
					alert('Invalid day, month or year range.');
				} else {
					returnval = true;
				}
			}
		}
		return returnval;
	}
};