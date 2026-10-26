/******************************************************************************************************************\
 * Description: This file is included some functions may be useful for some actions needed.						  *
 * Author: ttg <tatangajah@gmail.com>																			  *
 * Version: 1.4																									  *
 * Updated: March 5th, 2014																						  *
 \******************************************************************************************************************/

/**
 * is your input the number?
 */
var isNumeric = function(sText) {
  var validChars = "0123456789.-";
  var isNumber = true;
  var character;

  for (var i = 0; i < sText.length && isNumber == true; i++) {
    character = sText.charAt(i);
    if (validChars.indexOf(character) == -1) {
      isNumber = false;
    }
  }

  return isNumber;
};

/**
 * Format number with decimal point (number_format.js dependent)
 */
var numberFormat = {
  fields: document.getElementsByClassName('number'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      // load default
      this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
      this.fields[i].style.textAlign = 'right';

      // onfocus
      this.fields[i].onfocus = function(e) {
        if (!isNumeric(this.value)) {
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          var prec = this.value.split('.');
          if (prec[1] == '00') {
            this.value = number_format(this.value, 0, '', '');
          } else {
            this.value = number_format(this.value, 2, '.', '');
          }

          if (this.value == 0) {
            this.value = '';
          }
        }
      };

      // onblur
      this.fields[i].onblur = function(e) {
        if (!isNumeric(this.value)) {
          alert('Please enter only numbers or decimal points.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          this.value = number_format(this.value, 2, '.', '');
        }
      };

      // onkeyup
      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      // onkeypress
      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      };
    }
  },
  removeFormat: function() {
    for (var i = 0; i < this.fields.length; i++) {
      var prec = this.fields[i].value.split('.');
      if (prec[1] == '00') {
        this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      } else {
        this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
      }
    }
  }
};

/**
 * Format number without decimal point (number_format.js dependent)
 */
var justNumberFormat = {
  fields: document.getElementsByClassName('just-number'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      this.fields[i].style.textAlign = 'right';

      this.fields[i].onblur = function(e) {
        if (!justNumberFormat.isNumber(this.value)) {
          alert('Please enter only numbers.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          this.value = number_format(this.value, 0, '', '');
        }
      };

      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!justNumberFormat.isNumber(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!justNumberFormat.isNumber(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      }

      this.fields[i].onfocus = function(e) {
        if (this.value == 0) {
          this.value = '';
        }
      }
    }
  },
  removeFormat: function() {
  },
  isNumber: function(sText) {
    var validChars = "0123456789-";
    var isNumber = true;
    var character;

    for (var i = 0; i < sText.length && isNumber == true; i++) {
      character = sText.charAt(i);
      if (validChars.indexOf(character) == -1) {
        isNumber = false;
      }
    }

    return isNumber;
  }
};

/**
 * Format currency with decimal point and comma separator (number_format.js dependent)
 */
var currencyFormat = {
  fields: document.getElementsByClassName('currency'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      // load default
      this.fields[i].value = number_format(this.fields[i].value, 2);
      this.fields[i].style.textAlign = 'right';

      // onfocus
      this.fields[i].onfocus = function() {
        if (currencyFormat.isCurrency(this.value)) {
          var prec = this.value.split('.');
          if (prec[1] == '00') {
            this.value = number_format(this.value, 0, '', '');
          } else {
            this.value = number_format(this.value, 2, '.', '');
          }

          if (this.value == 0) {
            this.value = '';
          }
        }
      };

      // onblur
      this.fields[i].onblur = function(e) {
        //this.value = number_format(this.value, 2);
        if (!isNumeric(this.value)) {
          alert('Please enter only numbers or decimal points.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          this.value = number_format(this.value, 2);
        }
      };

      // onkeyup
      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      // onkeypress
      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      }
    }
  },
  removeFormat: function() {
    for (var i = 0; i < this.fields.length; i++) {
      var prec = this.fields[i].value.split('.');
      if (prec[1] == '00') {
        this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      } else {
        this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
      }
    }
  },
  isCurrency: function(sText) {
    var validChars = "0123456789.-,";
    var isNumber = true;
    var character;

    for (var i = 0; i < sText.length && isNumber == true; i++) {
      character = sText.charAt(i);
      if (validChars.indexOf(character) == -1) {
        isNumber = false;
      }
    }

    return isNumber;
  }
};

/**
 * Format thousand comma separator (number_format.js dependent)
 */
var thousandFormat = {
  fields: document.getElementsByClassName('thousand'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      // load default
      this.fields[i].value = number_format(this.fields[i].value, 0);
      this.fields[i].style.textAlign = 'right';

      // onfocus
      this.fields[i].onfocus = function() {
        if (thousandFormat.isNumber(this.value)) {
          var prec = this.value.split('.');
          if (prec[1] == '00') {
            this.value = number_format(this.value, 0, '', '');
          } else {
            this.value = number_format(this.value, 0, '.', '');
          }

          if (this.value == 0) {
            this.value = '';
          }
        }
      };

      // onblur
      this.fields[i].onblur = function(e) {
        if (!isNumeric(this.value)) {
          alert('Please enter only numbers or decimal points.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          this.value = number_format(this.value, 0);
        }
      };

      // onkeyup
      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      // onkeypress
      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      }
    }
  },
  removeFormat: function() {
    for (var i = 0; i < this.fields.length; i++) {
      var prec = this.fields[i].value.split('.');
      if (prec[1] == '00') {
        this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      } else {
        this.fields[i].value = number_format(this.fields[i].value, 0, '.', '');
      }
    }
  },
  isNumber: function(sText) {
    var validChars = "0123456789.-,";
    var isNumber = true;
    var character;

    for (var i = 0; i < sText.length && isNumber == true; i++) {
      character = sText.charAt(i);
      if (validChars.indexOf(character) == -1) {
        isNumber = false;
      }
    }

    return isNumber;
  }
};

/**
 * Format percent with decimal point and comma separator (number_format.js dependent)
 */
var percentFormat = {
  fields: document.getElementsByClassName('percent'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      // load default
      this.fields[i].value = number_format(this.fields[i].value, 5, '.', '');

      // onfocus
      this.fields[i].onfocus = function(e) {
        if (!isNumeric(this.value)) {
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          var prec = this.value.split('.');
          if (prec[1] == '00000') {
            this.value = number_format(this.value, 0, '', '');
          } else {
            this.value = number_format(this.value, 5, '.', '');
          }

          if (this.value == 0) {
            this.value = '';
          }
        }
      };

      // onblur
      this.fields[i].onblur = function(e) {
        if (!isNumeric(this.value)) {
          alert('Please enter only numbers or decimal points.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          this.value = number_format(this.value, 5, '.', '');
        }
      };

      // onkeyup
      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      // onkeypress
      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      }
    }
  },
  removeFormat: function() {
    for (var i = 0; i < this.fields.length; i++) {
      var prec = this.fields[i].value.split('.');
      if (prec[1] == '00000') {
        this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      } else {
        this.fields[i].value = number_format(this.fields[i].value, 5, '.', '');
      }
    }
  }
};

/**
 * Format Latitude & Longitude with decimal point and comma separator (number_format.js dependent)
 */
var latLngFormat = {
  fields: document.getElementsByClassName('latlng-number'),
  init: function() {
    for (var i = 0; i < this.fields.length; i++) {
      // load default
      var decimals = this.fields[i].value.indexOf('.') != -1 ? this.fields[i].value.split('.')[1].length : 0;
      //decimals = decimals < 6 ? 6 : decimals;
      decimals = 6;
      var defaultValue = this.fields[i].value.length > 0 ? number_format(this.fields[i].value, decimals, '.', '') : '';

      this.fields[i].value = defaultValue;
      this.fields[i].style.textAlign = 'right';

      // onfocus
      this.fields[i].onfocus = function(e) {
        if (!isNumeric(this.value)) {
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          var prec = this.value.split('.');
          if (prec[1] == '00') {
            this.value = number_format(this.value, 0, '', '');
          } else {
            var decimals = this.value.indexOf('.') != -1 ? this.value.split('.')[1].length : 0;
            var defaultValue = this.value.length > 0 ? number_format(this.value, decimals, '.', '') : '';
            this.value = defaultValue;
          }
        }
      };

      // onblur
      this.fields[i].onblur = function(e) {
        if (!isNumeric(this.value)) {
          alert('Please enter only numbers or decimal points.');
          setTimeout(function() {
            e.target.select()
          });
          return false;
        } else {
          var decimals = this.value.indexOf('.') != -1 ? this.value.split('.')[1].length : 0;
          //decimals = decimals < 6 ? 6 : decimals;
          decimals = 6;
          var defaultValue = this.value.length > 0 ? number_format(this.value, decimals, '.', '') : '';
          this.value = defaultValue;
        }
      };

      // onkeyup
      this.fields[i].onkeyup = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code != 37 && code != 38 && code != 39 && code != 40 && code != 13 && code != 8 && code != 46) {
            this.blur();
          }
        }
      };

      // onkeypress
      this.fields[i].onkeypress = function(e) {
        var code = e.keyCode;
        if (!isNumeric(this.value)) {
          if (code == 13) {
            setTimeout(function() {
              e.target.select()
            });
            return false;
          }
        }
      };
    }
  },
  removeFormat: function() {
    for (var i = 0; i < this.fields.length; i++) {
      var prec = this.fields[i].value.split('.');
      if (prec[1] == '00') {
        this.fields[i].value = number_format(this.fields[i].value, 0, '', '');
      } else {
        this.fields[i].value = number_format(this.fields[i].value, 2, '.', '');
      }
    }
  }
};

/**
 * Format date (jQueryUI dependent)
 */
var dateFormat = {
  monthsName: {'01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug', '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'},
  monthsCode: {'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'},
  fields: document.getElementsByClassName('date'),
  init: function() {
    $('.date').datepicker({
      dateFormat: 'dd-M-yy',
      changeMonth: true,
      changeYear: true
    });

    this.defaultMode();

    for (var i = 0; i < this.fields.length; i++) {
      (function(idx) {

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

          if (!e)
            var e = window.event
          if (e.keyCode)
            code = e.keyCode;
          else if (e.which)
            code = e.which;
          var character = String.fromCharCode(code);

          // if they pressed esc... remove focus from field...
          if (code == 27) {
            this.blur();
            return false;
          }

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

    //if (returnval == false) {
    //	input.select();
    //}

    return returnval;
  }
};

/**
 * Format form field(s)
 */
var formField = {
  enable: function(fields) {
    for (var i = 0; i < fields.length; i++) {
      var elem = document.getElementById(fields[i]);
      if (elem != null) {
        if (elem.type == 'select-one') {
          elem.removeAttribute('disabled');
        } else {
          elem.removeAttribute('readonly');
        }
      }
    }
  },
  disable: function(fields) {
    for (var i = 0; i < fields.length; i++) {
      var elem = document.getElementById(fields[i]);
      if (elem != null) {
        if (elem.type == 'select-one') {
          elem.setAttribute('disabled', 'disabled');
        } else {
          elem.setAttribute('readonly', 'readonly');
        }
      }
    }
  },
  clearForm: function(formElement) {
    var elements = formElement.elements;

    formElement.reset();

    for (var i = 0; i < elements.length; i++) {
      field_type = elements[i].type.toLowerCase();

      switch (field_type) {
        case "text":
        case "password":
        case "textarea":
        case "hidden":
          elements[i].value = "";
          break;

        case "radio":
        case "checkbox":
          if (elements[i].checked) {
            elements[i].checked = false;
          }
          break;

        case "select-one":
        case "select-multi":
          elements[i].selectedIndex = 0;
          break;

        default:
          break;
      }
    }
  }
};

/**
 * Format text, textbox
 */
var TEXT = {
  toUpperCase: function() {
    var fields = document.getElementsByClassName('uppercase');
    for (var i = 0; i < fields.length; i++) {
      fields[i].onkeyup = function(e) {
        if (e.keyCode != 37 && e.keyCode != 39) {
          this.value = this.value.toUpperCase();
        }
      };
    }
    ;
  },
  uneditable: function() {
    var fields = document.getElementsByClassName('uneditable');
    for (var i = 0; i < fields.length; i++) {
      fields[i].onkeypress = function() {
        return false;
      };
    }
  },
  clearAutoComplete: function() {
    var fields = document.getElementsByTagName('input');
    for (var i = 0; i < fields.length; i++) {
      if (fields[i].type.toLowerCase() == 'text') {
        fields[i].setAttribute('autocomplete', 'off');
      }
    }
  }
};

/**
 * Alert Notification (jquery.notyfy.js dependent)
 * 
 * Created by ttg
 * Modified by andedi
 */
var coolNotification = function(message, type, functionAfterOKClicked) {
  var header = 'Info!';

  if (type == 'success') {
    header = 'Success!';
  } else if (type == 'error') {
    header = 'Error!';
  } else if (type == 'warning') {
    header = 'Warning!'
  }

  notyfy({
    layout: 'center',
    text: '<b style="font-size:16px; line-height:2">' + header + '</b><br>' + message,
    type: type, // alert, success, error, warning, information, confirm
    dismissQueue: false,
    modal: true,
    showEffect: false,
    hideEffect: false,
    buttons: [{
        addClass: 'btn btn-notyfy-ok',
        text: '<i class="icon-ok"></i> OK',
        onClick: function(notyfy) {
          notyfy.close();

          if (typeof functionAfterOKClicked != 'undefined' && typeof functionAfterOKClicked == 'function') {
            functionAfterOKClicked();
          }
        }
      }],
    events: {
      shown: function() {
        document.getElementsByClassName('notyfy_buttons')[0].style.textAlign = 'center';
        document.getElementsByClassName('btn-notyfy-ok')[0].focus();
      }
    }
  });
};

/**
 * Alert Confirmation (jquery.notyfy.js dependent)
 */
var coolConfirmation = function(message, functionAfterYesClicked) {
  notyfy({
    layout: 'center',
   text: '<b style="font-size:16px; line-height:2">Confirmation</b><br>' + message,
    type: 'confirm', // alert, success, error, warning, information, confirm
    dismissQueue: false,
    modal: true,
    showEffect: false,
    hideEffect: false,
    buttons: [{
        addClass: 'btn btn-notyfy-yes',
        text: '<i class="icon-ok"></i> Yes',
        onClick: function(notyfy) {
          notyfy.close();

          if (typeof functionAfterYesClicked != 'undefined' && typeof functionAfterYesClicked == 'function') {
            functionAfterYesClicked();
          }
        }
      }, {
        addClass: 'btn',
        text: '<i class="icon-remove"></i> No',
        onClick: function(notyfy) {
          notyfy.close();
        }
      }],
    events: {
      shown: function() {
        document.getElementsByClassName('notyfy_buttons')[0].style.textAlign = 'center';
        document.getElementsByClassName('btn-notyfy-yes')[0].focus();
      }
    }
  });
};

/**
 * Cookie
 */
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

/**
 * Site Info Menu
 */
var siteInfoMenu = function() {
  var menu = document.getElementById('menu');
  var idxModuleMenu = getCookie('idxModuleMenu');
  var parent = menu.children[2].children; // 2 = ul

  for (var i = 0; i < parent.length; i++) {
    (function(idx) {
      parent[idx].onclick = function() {
        createCookie('idxModuleMenu', idx, 1);
      };
    })(i);
  }

  var menuParent = idxModuleMenu != '' ? menu.children[2].children[idxModuleMenu] : menu.children[2].children[0]; // 2 = ul, idxModuleMenu = module menu, eg.: site info
  if (menuParent.children.length > 1) {
    var menuChild = menuParent.children[1].children; // 1 = ul
    var idxSelectedMenu = getCookie('idxSelectedMenu');
    var lastURL = getCookie('lastURL');

    menuParent.setAttribute('class', 'hasSubmenu active');
    menuParent.children[1].setAttribute('class', 'in collapse'); // module menu selected & collapsed

    for (var i = 0; i < menuChild.length; i++) {
      menuChild[i].removeAttribute('class');

      if (idxSelectedMenu != '') {
        menuChild[idxSelectedMenu].setAttribute('class', 'active'); // child menu active and selected
      }

      (function(idx) {
        menuChild[idx].onclick = function() {
          for (var j = 0; j < menuChild.length; j++) {
            menuChild[j].removeAttribute('class');
          }

          createCookie('idxSelectedMenu', idx, 1);
          createCookie('lastURL', menuChild[idx].children[0].href, 1);
        };
      })(i);
    }
  }
};

//NITIP
//BLOCK UI
//andedi 20141210

var block = {
  show: function(message) {
    $.blockUI({
      message: '<img src="' + baseURL + '/file/images/loading-snake.GIF" width="16px"> ' + message + ', please wait...',
      css: {
        border: 'none',
        padding: '15px',
        opacity: .5,
        color: '#fff',
        'z-index': 9999,
        backgroundColor: '#000',
//                '-webkit-border-radius' : '10px',
//                '-moz-border-radius'    : '10px'
      },
      overlayCSS: {
        'z-index': 9998,
        backgroundColor: '#000',
        opacity: 0.6,
        cursor: 'wait'
      },
    });
  },
  close: function() {
    $.unblockUI();
  }
};

/**
 * Add tags
 */
var headEl = document.getElementsByTagName('head')[0], sync = true;
	
function addTag(name, attributes, sync) {
	var el = document.createElement(name), attrName;

	for (attrName in attributes) {
		el.setAttribute(attrName, attributes[attrName]);
	}

	sync ? document.write(outerHTML(el)) : headEl.appendChild(el);
}

function outerHTML(node) {
	// if IE, Chrome take the internal method otherwise build one
	return node.outerHTML || (
		function(n){
			var div = document.createElement('div'), h;
			div.appendChild(n);
			h = div.innerHTML;
			div = null;
			return h;
		})(node);
}