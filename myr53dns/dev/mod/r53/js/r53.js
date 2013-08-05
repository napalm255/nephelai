var $ajaxUrl_Zones = '';
var $ajaxUrl_Zone = '';

function zonesGet_Zones() {
  $data = ({get_zones : 'true'});
  $.ajax({
      url: "r53.zones.ajax.php",
      type: "POST",
      data: $data,
      dataType: "html",
      error: function(msg){
        $('#hosted_zones').html('Error: ' + msg);
      },
      success: function(msg){
        $('#hosted_zones').html(msg);
      }
  });
}

function zoneGet_Records($zone_id,$zone_name) {
  $data = ({get_records : 'true',
                zone_id : $zone_id,
              zone_name : $zone_name
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "html",
      error: function(msg){
        $('#records').html('Error: ' + msg);
      },
      success: function(msg){
        $('#records').html(msg);
      }
  });
}

function zoneGet_NS($zone_id) {
  $data = ({get_ns : 'true',
           zone_id : $zone_id
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "html",
      error: function(msg){
        $('#ns').html('Error: ' + msg);
      },
      success: function(msg){
        $('#ns').html(msg);
      }
  });
}

function zoneGet_SOA($zone_id,$zone_name) {
  $data = ({get_soa : 'true',
            zone_id : $zone_id,
          zone_name : $zone_name
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "html",
      error: function(msg){
        $('#soa').html('Error: ' + msg);
      },
      success: function(msg){
        $('#soa').html(msg);
      }
  });
}

function zoneGet_Change($change_id) {
  $data = ({get_change : 'true',
             change_id : $change_id
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "html",
      error: function(msg){
        $('#change').html('Error: ' + msg);
      },
      success: function(msg){
        $('#change').html(msg);
      }
  });
}

function zoneDelete_Record($jsonCurrent) {
  var $current = jQuery.parseJSON($jsonCurrent);
  $data = ({zone_delete_record : 'true',
                       zone_id : $current.Id,
                     zone_name : $current.Subdomain + '.' + $current.Name,
                     zone_type : $current.Type,
                      zone_ttl : $current.TTL,
                    zone_value : $current.Value
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "json",
      async: false,
      error: function(){
        $('#zone_msg').html('<div class="message errormsg">Error Sending Request</div>');
      },
      success: function(msg){
        if (msg.Name == 'Error') {
          $('#zone_msg').html('<div class="message errormsg">Type: ' + msg.Type[0] + ', Code: ' + msg.Code[0] + ', Message: ' + msg.Message[0] + '</div>');
        }
        if (msg.Name == 'Change') {
          $('#zone_msg').html('<div class="message success">Change ID: ' + msg.Id + ', Status: ' + msg.Status[0] + ', Submitted At: ' + msg.Timestamp[0] + '</div>');
        }
      }
   }
  );
  $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
  $('.block .message .close').hover(
    function() { $(this).addClass('hover'); },
    function() { $(this).removeClass('hover'); }
  );
  $('.block .message .close').click(function() {
    $(this).parent().fadeOut('slow', function() { $(this).remove(); });
  });
  zoneGet_Records($.query.get('id'),$.query.get('name'));
}

function zoneSave_Record($jsonCurrent,$jsonNew) {
  var $current = jQuery.parseJSON($jsonCurrent);
  var $new = jQuery.parseJSON($jsonNew);
  $data = ({zone_save_record : 'true',
                     zone_id : $current.Id,
                   zone_name : $current.Subdomain + '.' + $current.Name,
                   zone_type : $current.Type,
                    zone_ttl : $new.TTL,
                  zone_value : $new.Value
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "json",
      async: false,
      error: function(){
        $('#zone_msg').html('<div class="message errormsg">Error Sending Request</div>');
      },
      success: function(msg){
        if (msg.Name == 'Error') {
          $('#zone_msg').html('<div class="message errormsg">Type: ' + msg.Type[0] + ', Code: ' + msg.Code[0] + ', Message: ' + msg.Message[0] + '</div>');
        }
        if (msg.Name == 'Change') {
          $('#zone_msg').html('<div class="message success">Change ID: ' + msg.Id + ', Status: ' + msg.Status[0] + ', Submitted At: ' + msg.Timestamp[0] + '</div>');
        }
      }
   }
  );
  $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
  $('.block .message .close').hover(
    function() { $(this).addClass('hover'); },
    function() { $(this).removeClass('hover'); }
  );
  $('.block .message .close').click(function() {
    $(this).parent().fadeOut('slow', function() { $(this).remove(); });
  });
  if ($current.Type == 'NS') { zoneGet_NS($current.Id); }
  if ($current.Type == 'SOA') { zoneGet_SOA($current.Id, $current.Name); }
  zoneGet_Records($current.Id, $current.Name);
}

function zoneCreate_Record($jsonNew) {
  var $new = jQuery.parseJSON($jsonNew);
  $data = ({zone_save_record : 'true',
                     zone_id : $new.Id,
                   zone_name : $new.Subdomain + '.' + $new.Name,
                   zone_type : $new.Type,
                    zone_ttl : $new.TTL,
                  zone_value : $new.Value
          });
  $.ajax({
      url: "r53.zone.ajax.php",
      type: "POST",
      data: $data,
      dataType: "json",
      async: false,
      error: function(){
        $('#zone_msg').html('<div class="message errormsg">Error Sending Request</div>');
      },
      success: function(msg){
        if (msg.Name == 'Error') {
          $('#zone_msg').html('<div class="message errormsg">Type: ' + msg.Type[0] + ', Code: ' + msg.Code[0] + ', Message: ' + msg.Message[0] + '</div>');
        }
        if (msg.Name == 'Change') {
          $('#zone_msg').html('<div class="message success">Change ID: ' + msg.Id + ', Status: ' + msg.Status[0] + ', Submitted At: ' + msg.Timestamp[0] + '</div>');
        }
      }
   }
  );
  $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
  $('.block .message .close').hover(
    function() { $(this).addClass('hover'); },
    function() { $(this).removeClass('hover'); }
  );
  $('.block .message .close').click(function() {
    $(this).parent().fadeOut('slow', function() { $(this).remove(); });
  });
  zoneGet_Records($new.Id, $new.Name);
}

function zoneCreate_Form_Type_Hints() {
  var $semi = '<span style="float:right;margin-right:25px;padding:2px;"><img src="images/semicolon.png" alt="Semicolon ; Delimited" title="Semicolon ; Delimited" /></span>';
  var $note_txt = '"a string with a \\100 strange character in it and a \\" quote"';
  switch($('#rrc_type').val()) {
    case 'A':
      $('#rrc_type_note').html('Value: 192.0.2.1' + $semi);
      break;
    case 'AAAA':
      $('#rrc_type_note').html('Value: 2001:db8::1' + $semi);
      break;
    case 'CNAME':
      $('#rrc_type_note').html('Value: hostname.example.com');
      break;
    case 'MX':
      $('#rrc_type_note').html('Value: 10 sub.domain.com' + $semi);
      break;
    case 'NS':
      $('#rrc_type_note').html('Value: ns-1.example.com' + $semi);
      break;
    case 'PTR':
      $('#rrc_type_note').html('Value: hostname.example.com' + $semi);
      break;
    case 'SOA':
      $('#rrc_type_note').html('Value: ns-500.awsdns-11.net hostmaster.awsdns.com 1 1 1 1 60');
      break;
    case 'SPF':
      $('#rrc_type_note').html('Value: ' + $note_txt  + $semi);
      break;
    case 'SRV':
      $('#rrc_type_note').html('Value: 10 5 80 hostname.example.com' + $semi);
      break;
    case 'TXT':
      $('#rrc_type_note').html('Value: ' + $note_txt + $semi);
      break;
    default:
      $('#rrc_type_note').html('');
      break;
  }
}

function zonesForm_Create() {
  $.facebox(function() {
    $.ajax({
          url: 'r53.zones.ajax.php',
          data: { zones_form_create: 'true'},
          type: 'POST',
          error: function() {
            $.facebox('Error loading form.');
          },
          success: function(data) {
            $.facebox(data);
          }
    });
  });
}

function zoneForm_Create($zone_id,$zone_name,$zone_copy_json) {
  $.facebox(function() {
    $.ajax({
          url: 'r53.zone.ajax.php',
          data: { zone_form_create: 'true', zone_id: $zone_id, zone_name: $zone_name, zone_copy: $zone_copy_json},
          type: 'POST',
          error: function() {
            $.facebox('Error loading form.');
          },
          success: function(data) {
            $.facebox(data);
          }
    });
  });
}

function zonesCreate_Zone($jsonNew) {
  var $new = jQuery.parseJSON($jsonNew);
  $data = ({zones_create_zone : 'true',
                    zone_name : $new.Name
          });
  $.ajax({
      url: "r53.zones.ajax.php",
      type: "POST",
      data: $data,
      dataType: "json",
      async: false,
      error: function(){
        $('#zones_msg').html('<div class="message errormsg">Error Sending Request</div>');
      },
      success: function(msg){
        if (msg.Name == 'Error') {
          $('#zones_msg').html('<div class="message errormsg">Type: ' + msg.Type[0] + ', Code: ' + msg.Code[0] + ', Message: ' + msg.Message[0] + '</div>');
        }
        if (msg.Name == 'Change') {
          $('#zones_msg').html('<div class="message success">Change ID: ' + msg.Id + ', Status: ' + msg.Status[0] + ', Submitted At: ' + msg.Timestamp[0] + '</div>');
        }
      }
   }
  );
  $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
  $('.block .message .close').hover(
    function() { $(this).addClass('hover'); },
    function() { $(this).removeClass('hover'); }
  );
  $('.block .message .close').click(function() {
    $(this).parent().fadeOut('slow', function() { $(this).remove(); });
  });
  zonesGet_Zones();
}

function zonesDelete_Zone($zone_id) {
  $data = ({zones_delete_zone : 'true',
                      zone_id : $zone_id
          });
  $.ajax({
      url: "r53.zones.ajax.php",
      type: "POST",
      data: $data,
      dataType: "json",
      async: false,
      error: function(){
        $('#zones_msg').html('<div class="message errormsg">Error Sending Request</div>');
      },
      success: function(msg){
        if (msg.Name == 'Error') {
          $('#zones_msg').html('<div class="message errormsg">Type: ' + msg.Type[0] + ', Code: ' + msg.Code[0] + ', Message: ' + msg.Message[0] + '</div>');
        }
        if (msg.Name == 'Change') {
          $('#zones_msg').html('<div class="message success">Change ID: ' + msg.Id + ', Status: ' + msg.Status[0] + ', Submitted At: ' + msg.Timestamp[0] + '</div>');
        }
      }
   }
  );
  $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
  $('.block .message .close').hover(
    function() { $(this).addClass('hover'); },
    function() { $(this).removeClass('hover'); }
  );
  $('.block .message .close').click(function() {
    $(this).parent().fadeOut('slow', function() { $(this).remove(); });
  });
  zonesGet_Zones();
}


/*
  Login Screen FAQ
*/
function faq() {
  $.facebox(function() {
    $.ajax({
          url: 'r53.faq.php',
          data: { login : 'true' },
          type: 'POST',
          error: function() {
            $.facebox('Error loading form.');
          },
          success: function(data) {
            $.facebox(data);
          }
    });
  });
}
