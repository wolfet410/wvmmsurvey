'use strict';

var wvmmsurvey = window.wvmmsurvey || {};

wvmmsurvey.make = {
	create: function(url) {
		// Builds the create survey page
		// url points to CSV file created from WV phone list via "save as .csv (MS-DOS)"
		// with no other changes
		$.ajax({
			url: url,
			type: 'GET',
			cache: false,
			async: false,
			success: function(response) { 
				var arr = $.csv.toObjects(response);
				// Sort adapted from http://stackoverflow.com/a/5503971/1779382
				arr.sort(function(a, b){
					// Number padding adapted from http://stackoverflow.com/a/4258793/1779382
					var a1 = (new Array(5 + 1 - a['SAP#'].toString().length)).join('0') + a['SAP#'];
			    var b1 = (new Array(5 + 1 - b['SAP#'].toString().length)).join('0') + b['SAP#'];
			    if(a1 == b1) return 0;
			    return a1 > b1 ? 1 : -1;
				});
				var html = '<select id="store" name="store" class="chzn-select survey-textarea" data-placeholder="Select a store...">';
				html += '<option value=""></option>';
				$.each(arr, function(k,v){
					if (v['SAP#'] !== '') {
						 html += '<option value="' + v['SAP#'] + '">' + v['SAP#'] + ' - '
							+ v['Store\nName'] + ' (' + v['Market'] + ')</option>';
					} 
				});
				html += '</select>';
        $('#storeVisited').empty();
        $(html).appendTo('#storeVisited');
				$(".chzn-select").chosen();
				AnyTime.picker("visitDate",
					{ format: "%W, %M %d, %z", firstDOW: 1 } 
				);
				$("#visitTime").AnyTime_picker({
					format: "%h:%i %p", labelTitle: "Hour",
					labelHour: "Hour", labelMinute: "Minute"
				});
			}
		});
	},
  select: function() {
    // Builds and displays the list of existing surveys to select from
    $.ajax({
      url: "wvmmsurvey.php",
      type: 'POST',
      data: { 
        todo: "makeSelectSurvey"
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(arr) {
        var html = '<select id="surveyList" name="surveyList" class="chzn-select" data-placeholder="Select a survey...">';
        html += '<option value=""></option>';
        $.each(arr, function(k,v){
          if (v['suid'] !== '') {
             html += '<option value="' + v['suid'] + '">' + v['store'] + ' - ' + v['userCreated'];
          } 
        });
        html += '</select>';
        $('#selectSurvey').empty();
        $(html).appendTo('#selectSurvey');
        $(".chzn-select").chosen();
      }
    });
  },
  edit: function(suid) {
    // Gets and displays the sap & time stamp info for the survey being edited
    var storeHtml = '';
    var createdHtml = '';
    var modifiedHtml = '';
    $.ajax({
      url: "wvmmsurvey.php",
      type: 'POST',
      data: { 
        todo: "makeEditSurvey",
        suid: suid
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        $.each(r, function(key, val) {
          // Internet Explorer has problems with - in dates, see: 
          // http://stackoverflow.com/questions/8098963/javascript-datedatestring-returns-nan-on-specific-server-and-browser
          var dc = new Date(val['userCreated'].replace(/-/g,"/"));
          var dm = new Date(val['systemLastModified'].replace(/-/g,"/"));
          storeHtml = '<div>SAP Number: ' + val['store'] + '</div>';
          createdHtml = '<div>' + dtc.lib.formatDate(dc) + '</div>';
          modifiedHtml = '<div id="modifiedDate">' + dtc.lib.formatDate(dm) + '</div>';
          $('#storeVisited').empty();
          $('#createdDate').empty();
          $('#modifiedDate').empty();
          $(storeHtml).appendTo('#storeVisited');
          $(createdHtml).appendTo('#createdDate');
          $(modifiedHtml).appendTo('#modifiedDate');
        });
      }
    });
  },
  questions: function(suid) {
    // Builds dynamic survey content div using Questions table
    var html = '';
    // Show all questions in DOM
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "makeSurveyQuestions",
        suid: suid
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(aq) {
        var qnum = 0;
        $.each(aq, function(key, val) {
          switch(val['type']) {
            case 'heading':
              html += '<br><div class="survey-heading">' + val['text'] + '</div>';
              html += '<hr>';
              break;
            case 'radio':
              qnum++;
              html += '<div class="survey-question';
              html += val['table'] == 'true' ? '-table' : '';
              html += '">' + qnum + ') ' + val['text'] + '</div>';
              var ans = val['answers'].split(",");
              $.each(ans, function(k,v) {
                html += '<label class="survey-radio';
                html += val['table'] == 'true' ? '-table' : '';
                html += '"><input type="radio" name="radio' + val['quid'] + '" '
                     + 'id="radio' + val['quid'] + '" value="' + v + '">' + v 
                     + '</label>';
              });
              if (val['notes'] == "true") {
                html += '<p class="survey-question" style="text-indent: 0px; padding-left: ';
                html += val['table'] == 'true' ? '240' : '10';
                html += 'px;">' + val['notestext'] + '<br><textarea id="notes' 
                     + val['quid'] + '" class="survey-textarea"></textarea></p>';
              } else {
                html += val['table'] == 'true' ? '' : '<br><br>';
              }
              break;
            case 'textbox':
              qnum++;
              html += '<p class="survey-question">';
              html += qnum + ') ' + val['text'] + '<br><textarea id="text' 
                   + val['quid'] + '" class="survey-textarea"></textarea></p>';
              break;
          }
        });
        $('#dynamicContent').empty();
        $(html).appendTo('#dynamicContent');
      }
    });
    // Populate each question with the latest answer
    $(document).find(':input').each(function() {
      var type = this.type;
      var id = this.id;
      $.ajax({
        url: "wvmmsurvey.php", 
        type: 'POST',
        data: { 
          todo: "makeGetAnswers",
          type: type,
          quid: this.id.match(/[0-9]+/g).toString(),
          suid: suid
        },
        cache: false,
        async: false,
        dataType: 'json',
        success: function(r) {
          if (r) {
            (type == 'textarea') && $('textarea#'+id).val(r[0]['textarea']);
            (type == 'radio') && $('[name='+id+'][value="'+r[0]['radio']+'"]').prop('checked',true);
          }
        }
      });
    });
  },
  popup: function() {
    // Builds dynamic survey content div using Questions table
    var html = '';
    var types = {
      'heading': 'Heading',
      'radio': 'Radio',
      'textbox': 'Text Box'
    };
    var eo = 0;
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "makeSurveyQuestions",
        suid: 0
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(aq) {
        html = "<table style='font-family: Courier;'><tr><th>Row&nbsp;</th><th>Type</th><th>Options</th></tr><tr>";
        $.each(aq, function(key, val) {
          eo++;
          // Begin row
          html += "<tr class='" + (eo % 2 == 0 ? 'evenrow' : 'oddrow') + "'>"
          // Sort column
               + "<td style='text-align:center;'>"
               + "<a href='#' class='row-controls' id='add' title='Add a new row under this one'"
               + " onclick='wvmmsurvey.act.popupRow(\"Add\"," + val['quid'] + ")'>+</a>"
               + "<a href='#' class='row-controls' id='del' title='Delete this row'"
               + " onclick='wvmmsurvey.act.popupRow(\"Del\"," + val['quid'] + ")'>-</a>"
               + "<a href='#' class='row-controls' id='up' title='Move this row up'"
               + " onclick='wvmmsurvey.act.popupRow(\"Swap\"," + val['quid'] + ",\"up\")'>&uarr;</a>"
               + "<a href='#' class='row-controls' id='down' title='Move this row down'"
               + " onclick='wvmmsurvey.act.popupRow(\"Swap\"," + val['quid'] + ",\"down\")'>&darr;</a></td>"               
          // Type column
               + "<td><select id='type" + val['quid'] + "'>";
          $.each(types, function(k,v) {
            html += "<option value='" + k + "'";
            html += k == val['type'] ? " selected='selected'" : "";
            html += ">" + v + "</option>";
          });
          html += "</select></td>";
          // Options column
          html += "<td style='padding-left: 5px;padding-top:10px;padding-bottom:10px;'>";
          switch(val['type']) {
            case 'heading':
              html += "Text: <input type='text' id='desc" + val['quid'] + "' value='" + val['text'] + "' style='width:562px;'>";
              break;
            case 'radio':
              var aa = val['answers'].split(",");
              html += "Question:"
                   + "<br><input type='text' id='desc" + val['quid'] + "' value='" + val['text'] + "' style='width:622px;'>"
                   + "<br>Radio button options:<br>"
                   + "<input type='text' id='1ropt" + val['quid'] + "' value='" 
                   + (typeof aa[0] != 'undefined' ? aa[0] : "") + "'>"
                   + "<input type='text' id='2ropt" + val['quid'] + "' value='"
                   + (typeof aa[1] != 'undefined' ? aa[1] : "") + "'>"
                   + "<input type='text' id='3ropt" + val['quid'] + "' value='"
                   + (typeof aa[2] != 'undefined' ? aa[2] : "") + "'>"
                   + "<input type='text' id='4ropt" + val['quid'] + "' value='" 
                   + (typeof aa[3] != 'undefined' ? aa[3] : "") + "'>"
                   + "<br>Notes?<label><input type='radio' name='notes" + val['quid'] + "' value='true'"
                   + (val['notes'] == 'true' ? " checked='checked'" : "") 
                   + ">Yes</label><label>"
                   + "<input type='radio' name='notes" + val['quid'] + "' value='false'"
                   + (val['notes'] == 'false' ? " checked='checked'" : "") 
                   + ">No</label>"
                   + "<br><div id='notestextdiv" + val['quid'] + "'"
                   + (val['notes'] == 'false' ? " style='display:none;'" : "")
                   + ">Notes text:<br><input type='text' id='notestext" + val['quid'] + "' style='width:622px;' value='"
                   + (typeof val['notestext'] != 'undefined' ? val['notestext'] : '')
                   + "'></div>Table View?<label><input type='radio' name='table" + val['quid'] + "' value='true'"
                   + (val['table'] == 'true' ? " checked='checked'" : "")
                   + ">Yes</label><label>"
                   + "<input type='radio' name='table" + val['quid'] + "' value='false'"
                   + (val['table'] == 'false' ? " checked='checked'" : "") 
                   + ">No</label>";
              break;
            case 'textbox':
              html += "Question:"
                   + "<br><input type='text' id='desc" + val['quid'] + "' value='" + val['text'] + "' style='width:622px;'>";
              break;
          }
          html += "</td>";
          // End row
          html += "</tr>";
        });
        html += "</table>";
        $('#popupContent').empty();
        $(html).appendTo('#popupContent');
      }
    });
  },
  refresh: function(quid) {
    // Refresh the child window
    location.reload();
    // Scroll the child window
    dtc.lib.scrollTo('#type'+quid);
    // Refresh the parent
    window.opener.location.reload();
    // Scrolling doesn't work on the parent because the objects do not have an ID
    window.opener.dtc.lib.scrollTo('#type'+quid);
  }
}

wvmmsurvey.act = {
	save: function(quid,type,value,suid) {
    var altText = "Saving ...";
    $('#saveStatus').attr({'src':"/img/saving.png",'alt':altText,'title':altText});
    $('#saveStatus').css('display', 'block');
		$.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "actWriteResults",
        suid: suid,
        quid: quid.toString(),
        type: type,
        value: value
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        if (r != "0" && r != null) {
          var altText = "Last Saved: " + r;
          $('#saveStatus').attr({'src':"/img/saved.png",'alt':altText,'title':altText});
          $('#saveStatus').css('display', 'block');
          var modifiedHtml = '<div id="modifiedDate">' + r + '</div>';
          $('#modifiedDate').empty();
          $(modifiedHtml).appendTo('#modifiedDate');
        } else {
          var altText = "Error writing to database!";
          $('#saveStatus').attr({'src':"/img/error.png",'alt':altText,'title':altText});
          $('#saveStatus').css('display', 'block');
        }
      },
      error: function() {
        var altText = "Error sending data to server!";
        $('#saveStatus').attr({'src':"/img/error.png",'alt':altText,'title':altText});
        $('#saveStatus').css('display', 'block');
      }
    });
	},
	create: function(store,date,time) {
		if (!store || !date || !time) { 
      new Messi('Please enter the Store, Date and Time of Visit!', {
        title: 'Error',
        buttons: [{id: 0, label: 'OK', val: ''}],
      });
      return;
    }
    var d = new Date(date + " " + time);
    var userCreated = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "createSurvey",
        store: store,
        usercreated: userCreated
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        new Messi('Survey successfully created!', {
          title: 'Created',
          buttons: [{id: 0, label: 'Edit It', val: ''}],
          callback: function() { window.location.href = "edit.php?suid=" + r; }
        });        
      }
    });
  },
  change: function(oldquid,type,text,answers,notes,notestext,table) {
    // Changes questions
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "actWriteQuestions",
        oldquid: oldquid,
        type: type,
        text: text,
        answers: answers,
        notes: notes,
        notestext: notestext,
        table: table
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        if (r == '0') {
          // success
        } else {
          // failed
        }
  // Need to get quid or something here!!
        // wvmmsurvey.make.refresh();
      },
      error: function() {
        alert("failed error");
      }
    });
  },
  popupWork: function(id) {
    // What type is it? Note: type = results from Type column, not this.type
    // regex returns all numbers in comma separated list, so split and pull 2nd value
    var oldquid = (id.match(/[0-9]+/g).toString().split(',')[1]) ?
      id.match(/[0-9]+/g).toString().split(',')[1] : 
      id.match(/[0-9]+/g).toString().split(',')[0];
    var type = $('#type' + oldquid).val();
    var notes = 'false';
    var table = 'false';
    if (type == 'radio') {
      var text = $('#desc' + oldquid).val();
      // Create answers
      var answers = '';
      for (var n=1;n<5;n++) {
        answers += $('#' + n + 'ropt' + oldquid).val() != '' ? $('#' + n + 'ropt' + oldquid).val() + ',' : '';
      }
      answers = answers.substring(0, answers.length - 1);
      notes = $('input:radio[name=notes' + oldquid + ']:checked').val();
      var notestext = $('#notestext' + oldquid).val();
      table = $('input:radio[name=table' + oldquid + ']:checked').val();
      wvmmsurvey.act.change(oldquid,type,text,answers,notes,notestext,table); 
    } else {
      var text = $('#desc' + oldquid).val();
      wvmmsurvey.act.change(oldquid,type,text,null,notes,null,table); 
    }                
  },
  popupRow: function(action,quid,direction) {
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "row"+action,
        quid: quid,
        direction: direction
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        wvmmsurvey.make.refresh(quid);
      },
      error: function(a,b,c) {
        alert(a+","+b+","+c);
      }
    });
  }
}

wvmmsurvey.report = {
  csvBySurvey: function() {
    if ($('#surveyList').val() == '') {
      new Messi('Please select a survey first!', {
        title: 'Error',
        buttons: [{id: 0, label: 'OK', val: ''}],
      });
    } else {
      $('#formCsvBySurvey').submit();
    }
  }
}
