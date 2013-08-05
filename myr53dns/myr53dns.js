// FAQ Accordion
window.addEvent('domready', function(){
  new Fx.Accordion($('accordion'), '#accordion h3', '#accordion .content');
  });

// Create/Update Record Help
window.addEvent('domready', function() {

  var status = {
    'true': 'open',
    'false': 'close'
  };

  // -- vertical

  var myVerticalSlide = new Fx.Slide('vertical_slide');
  myVerticalSlide.hide();

  $('v_toggle').addEvent('click', function(event){
    event.stop();
    myVerticalSlide.toggle();
  });

});

// Other Functions
function fillForm(vName,vType,vTTL,vValue) {
  objForm = document.forms["formRecord"];
  objForm.elements["rname"].value = vName;
  objForm.elements["rtype"].value = vType;
  objForm.elements["rttl"].value = vTTL;
  objForm.elements["rvalue"].value = decode64(vValue);
}

var b64array = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
function decode64(input) {
    var output = "";
    var hex = "";
    var chr1, chr2, chr3 = "";
    var enc1, enc2, enc3, enc4 = "";
    var i = 0;

    var base64test = /[^A-Za-z0-9\+\/\=]/g;
    input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

    do {
        enc1 = b64array.indexOf(input.charAt(i++));
        enc2 = b64array.indexOf(input.charAt(i++));
        enc3 = b64array.indexOf(input.charAt(i++));
        enc4 = b64array.indexOf(input.charAt(i++));
        
        chr1 = (enc1 << 2) | (enc2 >> 4);
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        chr3 = ((enc3 & 3) << 6) | enc4;
        
        output = output + String.fromCharCode(chr1);
        
        if (enc3 != 64) {
            output = output + String.fromCharCode(chr2);
        }
        if (enc4 != 64) {
            output = output + String.fromCharCode(chr3);
        }
    
        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    
    } while (i < input.length);

    return unescape(output);
}
