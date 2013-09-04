'use strict';

var wvmmsurvey = window.wvmmsurvey || {};

wvmmsurvey.make = {
	create: function() {
    alert('<?php echo "from php"; ?>');

// Need to create arr using http://api.jquery.com/jQuery.parseJSON/ and data from:
    // ?php 
    //   session_start();
    //   require "/var/www/lib/php/library.php";
    //   fnErrorLog("Select admin:".$_SESSION['admin']);
    //   fnErrorLog("Select stores:".$_SESSION['stores']);
    // ?
  //   var arr = $.parseJSON('<?php echo $_SESSION["stores"]; ?>');
		// arr.sort(function(a, b) {
		// 	// Number padding adapted from http://stackoverflow.com/a/4258793/1779382
		// 	var a1 = (new Array(5 + 1 - a['Title'].toString().length)).join('0') + a['Title'];
	 //    var b1 = (new Array(5 + 1 - b['Title'].toString().length)).join('0') + b['Title'];
	 //    if(a1 == b1) return 0;
	 //    return a1 > b1 ? 1 : -1;
		// });
		// var html = '<select id="store" name="store" class="chzn-select survey-textarea" data-placeholder="Select a store...">';
var html = '';
html += '<div>why arent i here2?';
alert('<?php echo "session:".$_SESSION["stores"]; ?>');
html += '<?php echo "session:".$_SESSION["stores"]; ?>';
html += '</div>';
  //   html += '<option value=""></option>';
		// $.each(arr, function(k,v){
		// 	if (v['Title'] !== '') {
		// 		 html += '<option value="' + v['Title'] + '">' + v['Title'] + ' - '
		// 			+ v['Description'] + ' (' + v['Market'] + ')</option>';
		// 	} 
		// });
		// html += '</select>';
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
alert('yellow');
    var storeHtml = '';
    var createdHtml = '';
    var modifiedHtml = '';
    // $.ajax({
    //   url: "wvmmsurvey.php",
    //   type: 'POST',
    //   data: { 
    //     todo: "makeEditSurvey",
    //     suid: suid
    //   },
    //   cache: false,
    //   async: false,
    //   dataType: 'json',
    //   success: function(r) {
    //     $.each(r, function(key, val) {
    //       // Internet Explorer has problems with - in dates, see: 
    //       // http://stackoverflow.com/questions/8098963/javascript-datedatestring-returns-nan-on-specific-server-and-browser
    //       var dc = new Date(val['userCreated'].replace(/-/g,"/"));
    //       var dm = new Date(val['systemLastModified'].replace(/-/g,"/"));
    //       storeHtml = '<div>SAP Number: ' + val['store'] + '</div>';
    //       createdHtml = '<div>' + dtc.lib.formatDate(dc) + '</div>';
    //       modifiedHtml = '<div id="modifiedDate">' + dtc.lib.formatDate(dm) + '</div>';
    //       $('#storeVisited').empty();
    //       $('#createdDate').empty();
    //       $('#modifiedDate').empty();
    //       $(storeHtml).appendTo('#storeVisited');
    //       $(createdHtml).appendTo('#createdDate');
    //       $(modifiedHtml).appendTo('#modifiedDate');
    //     });
    //   }
    // });
    // var fields = "<ViewFields><FieldRef Name='store' /></ViewFields>";
    var query = "<Query><Where><Eq><FieldRef Name='Title'/><Value Type='Text'>"+suid+"</Value></Eq></Where></Query>";
    $().SPServices({
      operation: "GetListItems",
      async: false,
      listName: "Surveys",
      // CAMLViewFields: fields,
      CAMLQuery: query,
      completefunc: function (xData, Status) {
        $(xData.responseXML).SPFilterNode("z:row").each(function () {
          storeHtml = '<div>SAP Number: ' + $(this).attr("ows_store") + '</div>';
          alert("T:"+$(this).attr("ows_Title"));
          alert("S:"+$(this).attr("ows_store"));
        })
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
                     + 'id="radio' + val['quid'] + '" value="' + v + '">' + v.split('~')[0] 
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
          if (r != 0) {
            (type == 'textarea') && $('textarea#'+id).val(r[0]['textarea']);
            (type == 'radio') && $('[name='+id+'][value="'+r[0]['radio']+'"]').prop('checked',true);
          }
        }
      });
    });
  },
  print: function(suid) {
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
              html += '<br><br><div class="survey-question';
              html += val['table'] == 'true' ? '-table' : '';
              html += '">' + qnum + ') ' + val['text'];
              html += val['rated'] == 'true' ? '<br><em>Rating: <span id="rating'+val['quid']+'"></span></em>' : '';
              html += '</div>';
              var ans = val['answers'].split(",");
              $.each(ans, function(k,v) {
                html += '<label class="survey-radio';
                html += val['table'] == 'true' ? '-table' : '';
                html += '"><input type="radio" name="radio' + val['quid'] + '" '
                     + 'id="radio' + val['quid'] + '" value="' + v + '"><span>' + v.split('~')[0] 
                     + '</span></label>';                
              });
              if (val['notes'] == "true") {
                html += '<p class="survey-question" style="text-indent: 0px; padding-left: ';
                html += val['table'] == 'true' ? '240' : '10';
                html += 'px;">' + val['notestext'] + '<br><span class="survey-textarea" id="notes'
                     + val['quid'] + '" ></span></p>';
              } else {
                html += val['table'] == 'true' ? '' : '<br><br>';
              }
              break;
            case 'textbox':
              qnum++;
              html += '<p class="survey-question">';
              html += qnum + ') ' + val['text'] + '<br><span id="text' 
                   + val['quid'] + '" class="survey-textarea"></span></p>';
              break;
          }
        });
        $('#dynamicContent').empty();
        $(html).appendTo('#dynamicContent');
      }
    });
    // Populate each question with the latest answer
    $(document).find('span.survey-textarea').each(function() {
      var id = this.id;
      $.ajax({
        url: "wvmmsurvey.php", 
        type: 'POST',
        data: { 
          todo: "makeGetAnswers",
          type: "textarea",
          quid: this.id.match(/[0-9]+/g).toString(),
          suid: suid
        },
        cache: false,
        async: false,
        dataType: 'json',
        success: function(r) {
          if ( r != 0) {
            $('#'+id).html(r[0]['textarea']);
          }
        },
        error: function(a,b,c) { alert(a,b,c); }
      });
    });
    $(document).find(':radio').each(function() {
      var id = this.id;
      $.ajax({
        url: "wvmmsurvey.php", 
        type: 'POST',
        data: { 
          todo: "makeGetAnswers",
          type: "radio",
          quid: this.id.match(/[0-9]+/g).toString(),
          suid: suid
        },
        cache: false,
        async: false,
        dataType: 'json',
        success: function(r) {
          if ( r != 0) {
            $('[name='+id+'][value="'+r[0]['radio']+'"]').prop('checked',true);
            $('#rating'+id.match(/[0-9]+/g).toString()).html(r[0]['radio'].split('~')[1]);
          }
        },
        error: function(a,b,c) { alert(a,b,c); }
      });
    });
    // Update overall rating
    $.ajax({
      url: "wvmmsurvey.php", 
      type: 'POST',
      data: { 
        todo: "printRating",
        suid: suid
      },
      cache: false,
      async: false,
      success: function(r) {
        html = '<span style="font-weight:bold;">Overall Rating: '+(r*100).toFixed(0)+'%</span>';
        $('#ratingDiv').empty();
        $(html).appendTo('#ratingDiv');
      },
      error: function(a,b,c) { alert(a,b,c); }
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
               + "<a href='javascript:;' class='row-controls' id='add' title='Add a new row under this one'"
               + " onclick='wvmmsurvey.act.popupRow(\"Add\"," + val['quid'] + ")'>+</a>"
               + "<a href='javascript:;' class='row-controls' id='del' title='Delete this row'"
               + " onclick='wvmmsurvey.act.popupRow(\"Del\"," + val['quid'] + ")'>-</a>"
               + "<a href='javascript:;' class='row-controls' id='up' title='Move this row up'"
               + " onclick='wvmmsurvey.act.popupRow(\"Swap\"," + val['quid'] + ",\"up\")'>&uarr;</a>"
               + "<a href='javascript:;' class='row-controls' id='down' title='Move this row down'"
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
                   + "<hr style='border: 1px dashed grey;'>Radio button options:"
                   + "<br>Rated?<label><input type='radio' name='rated" + val['quid'] + "' id='rated" + val['quid'] + "' value='true'"
                   + (val['rated'] == 'true' ? " checked='checked'" : "")
                   + ">Yes</label><label>"
                   + "<input type='radio' name='rated" + val['quid'] + "' id='rated" + val['quid'] + "' value='false'"
                   + (val['rated'] == 'false' ? " checked='checked'" : "") 
                   + ">No</label>"
                   + "<br><input type='text' id='1ropt" + val['quid'] + "' value='" 
                   + (typeof aa[0] != 'undefined' ? aa[0].split('~')[0] : "") + "'>"
                   + "<input type='text' id='2ropt" + val['quid'] + "' value='"
                   + (typeof aa[1] != 'undefined' ? aa[1].split('~')[0] : "") + "'>"
                   + "<input type='text' id='3ropt" + val['quid'] + "' value='"
                   + (typeof aa[2] != 'undefined' ? aa[2].split('~')[0] : "") + "'>"
                   + "<input type='text' id='4ropt" + val['quid'] + "' value='" 
                   + (typeof aa[3] != 'undefined' ? aa[3].split('~')[0] : "") + "'>"
                   + "<br><table id='ratingtable' width='100%'"
                   + (val['rated'] == 'false' ? " style='display:none;'><tr>" : "><tr>")
                   + "<td class='ratingtext'>"
                   + (typeof aa[0] != 'undefined' ? 
                      "Rating: <a href='javascript:;' class='row-controls' title='Increase rating' "
                      + "onclick='$(\"#1roptS\").html(parseInt($(\"#1roptS\").html()) + 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&uarr;</a>" 
                      + "<span id='1roptS'>" + (typeof aa[0].split('~')[1] != 'undefined' ? aa[0].split('~')[1] : "0") + "</span>"
                      + "<a href='javascript:;' class='row-controls' title='Decrease rating' "
                      + "onclick='$(\"#1roptS\").html(parseInt($(\"#1roptS\").html()) - 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&darr;</a></td>"
                      : "</td>")
                   + "<td class='ratingtext'>"
                   + (typeof aa[1] != 'undefined' ? 
                      "Rating: <a href='javascript:;' class='row-controls' title='Increase rating' "
                      + "onclick='$(\"#2roptS\").html(parseInt($(\"#2roptS\").html()) + 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&uarr;</a>" 
                      + "<span id='2roptS'>" + (typeof aa[1].split('~')[1] != 'undefined' ? aa[1].split('~')[1] : "0") + "</span>"
                      + "<a href='javascript:;' class='row-controls' title='Decrease rating' "
                      + "onclick='$(\"#2roptS\").html(parseInt($(\"#2roptS\").html()) - 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&darr;</a></td>"
                      : "</td>")
                   + "<td class='ratingtext'>"
                   + (typeof aa[2] != 'undefined' ? 
                      "Rating: <a href='javascript:;' class='row-controls' title='Increase rating' "
                      + "onclick='$(\"#3roptS\").html(parseInt($(\"#3roptS\").html()) + 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&uarr;</a>" 
                      + "<span id='3roptS'>" + (typeof aa[2].split('~')[1] != 'undefined' ? aa[2].split('~')[1] : "0") + "</span>"
                      + "<a href='javascript:;' class='row-controls' title='Decrease rating' "
                      + "onclick='$(\"#3roptS\").html(parseInt($(\"#3roptS\").html()) - 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&darr;</a></td>"
                      : "</td>")
                   + "<td class='ratingtext'>"
                   + (typeof aa[3] != 'undefined' ? 
                      "Rating: <a href='javascript:;' class='row-controls' title='Increase rating' "
                      + "onclick='$(\"#4roptS\").html(parseInt($(\"#4roptS\").html()) + 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&uarr;</a>" 
                      + "<span id='4roptS'>" + (typeof aa[3].split('~')[1] != 'undefined' ? aa[3].split('~')[1] : "0") + "</span>"
                      + "<a href='javascript:;' class='row-controls' title='Decrease rating' "
                      + "onclick='$(\"#4roptS\").html(parseInt($(\"#4roptS\").html()) - 1);"
                      + "wvmmsurvey.act.popupWork(\"rated"+val['quid']+"\");wvmmsurvey.make.refresh(\"desc\","+val['quid']+");'>&darr;</a></td>"
                      : "</td>")
                   + "</tr></table>"
                   + "<hr style='border: 1px dashed grey;'>Notes?<label><input type='radio' name='notes" + val['quid'] + "' id='notes" + val['quid'] + "' value='true'"
                   + (val['notes'] == 'true' ? " checked='checked'" : "") 
                   + ">Yes</label><label>"
                   + "<input type='radio' name='notes" + val['quid'] + "' name='id" + val['quid'] + "' value='false'"
                   + (val['notes'] == 'false' ? " checked='checked'" : "") 
                   + ">No</label>"
                   + "<br><div id='notestextdiv" + val['quid'] + "'"
                   + (val['notes'] == 'false' ? " style='display:none;'" : "")
                   + ">Notes text:<br><input type='text' id='notestext" + val['quid'] + "' style='width:622px;' value='"
                   + (typeof val['notestext'] != 'undefined' ? val['notestext'] : '')
                   + "'></div><hr style='border: 1px dashed grey;'>"
                   + "Table View?<label><input type='radio' name='table" + val['quid'] + "' id='table" + val['quid'] + "' value='true'"
                   + (val['table'] == 'true' ? " checked='checked'" : "")
                   + ">Yes</label><label>"
                   + "<input type='radio' name='table" + val['quid'] + "' id='table" + val['quid'] + "' value='false'"
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
  useremail: function () {
    // Returns user e-mail address


// DUMB ASS!!!










  },
  refresh: function(type,quid) {
    // Refresh the child window
    location.reload();
    // Scroll the child window
    dtc.lib.scrollTo('#'+type+quid);
    // Refresh the parent
    window.opener.location.reload();
    // Scrolling doesn't work on the parent because the objects do not have an ID
    window.opener.dtc.lib.scrollTo('#'+type+quid);
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
  change: function(oldquid,type,text,answers,notes,notestext,table,rated) {
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
        table: table,
        rated: rated
      },
      cache: false,
      async: false,
      dataType: 'json',
      success: function(r) {
        if (r == '0') {
          // success
        } else {
          alert("Failed to update question!");
        }
      },
      error: function() {
        alert("Failed to update question!");
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
    var rated = 'false';
    if (type == 'radio') {
      var text = $('#desc' + oldquid).val();
      // Create answers
      var answers = '';
      rated = $('input:radio[name=rated' + oldquid + ']:checked').val();
      for (var n=1;n<5;n++) {
        var t = rated == 'true' ? '~' + $('#'+n+'roptS').html() + ',' : ',';
        answers += $('#'+n+'ropt'+oldquid).val() != '' ? $('#'+n+'ropt'+oldquid).val() + t : '';
      }
      answers = answers.substring(0, answers.length - 1);
      notes = $('input:radio[name=notes' + oldquid + ']:checked').val();
      var notestext = $('#notestext' + oldquid).val();
      table = $('input:radio[name=table' + oldquid + ']:checked').val();
      wvmmsurvey.act.change(oldquid,type,text,answers,notes,notestext,table,rated); 
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
// NEED TO BE MORE DYNAMIC HERE, IT'S NOT ALWAYS A RADIO!!
        wvmmsurvey.make.refresh('radio',quid);
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

wvmmsurvey.sharepoint = {
  isAdmin: function() {
    // Returns e-mail address,(true or false) if logged on user is a member of the SandBox Owners group
    var owner = 'false';
    var user = $().SPServices.SPGetCurrentUser();
    var email = $().SPServices.SPGetCurrentUser({fieldName: "EMail", debug: false});
    $().SPServices({
      operation: 'GetGroupCollectionFromUser',
      userLoginName: user,  
      async: false,  
      completefunc: function(xData, Status) { 
        $(xData.responseXML).find('Group').each(function() {
          if ($(this).attr('Name') == 'SandBox Owners') { owner = 'true'; }
        });
      }
    });
    return email + "," + owner;
  },
  stores: function() {
    // Returns a JSON array of the stores listed in the Stores SharePoint list
    // Use JSON.stringify(wvmmsurvey.sharepoint.stores()) to send this data to PHP
    var json = [];
    var fields = "<ViewFields><FieldRef Name='Title' /><FieldRef Name='Description' />"
               + "<FieldRef Name='Market' /><FieldRef Name='Region' /></ViewFields>";
    $().SPServices({
        operation: "GetListItems",
        async: false,
        listName: "Stores",
        CAMLViewFields: fields,
        completefunc: function (xData, Status) {
          var i = 0;
          json = $(xData.responseXML).SPFilterNode("z:row").SPXmlToJson({
            mapping: {
              ows_Title: {mappedName: 'Title', objectType: 'Text'},
              ows_Description: {mappedName: 'Description', objectType: 'Text'},
              ows_Market: {mappedName: 'Market', objectType: 'Text'},
              ows_Region: {mappedName: 'Region', objectType: 'Text'}
            },
            includeAllAttrs: false,
            removeOws: true
          });
        }
    });
    return json;
  },
  pass: function(type,path,inp) {
    // Pass information to CEWP iFrame
    // Adapted from: http://stackoverflow.com/a/9815335/1779382
    switch(type) {
      case 'auth':
        var arr = inp.split(",");
        var params = {'email': arr[0].toLowerCase(), 'admin': arr[1]}
        var target = 'iframe';
        break;
      case 'stores':
        var params = {'stores': inp}
        var target = '_blank';
        break;
    }
    var form = $(document.createElement('form'))
        .attr({'method': 'post', 'action': path, 'target': target});
    $.each(params, function(key,value){
      $.each(value instanceof Array ? value : [value], function(i,val){
        $(document.createElement('input'))
          .attr({'type': 'hidden', 'name': key, 'value': val})
          .appendTo(form);
      }); 
    }); 
    form.appendTo(document.body).submit(); 
  }
}
