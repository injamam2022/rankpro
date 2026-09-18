
function renderLeadList(data){
  removeElementsByClass(data.key_code,'dragndrop-contain');
  var innerHtmlT = ''
  data.list.forEach(function(val){
    console.log(val);
    var leadImage = globalData.base_url+"uploads/leads/noimage.jpg";
    if(val.lead_icon){
      leadImage = globalData.base_url+"uploads/leads/"+val.lead_icon;
    }
    innerHtmlT = innerHtmlT +`
    <div class="dragndrop-contain" draggable="true" id="itm-`+val.id+`">
      <div class="mini-img" onclick='openLeadEditModal(`+JSON.stringify(val)+`)'>
        <img src="`+leadImage+`" alt="" />
      </div>
      <div class="dragndrop-contain-text">
        <div class="dragndrop-text1">`+val.client_name+`</div>
        <div class="dragndrop-text2">`+val.business_name+`</div>
      </div>
      <div class="dragndrop-arrow"><img src="`+globalData.base_url+`admin/img/arrow.png" alt=""></div>
    </div>`
  });
  //console.log(innerHtmlT);
  document.getElementById(data.key_code).innerHTML += innerHtmlT;
}

function removeElementsByClass(parentClassName,className){
    var data = document.getElementById(parentClassName);
    var elements = data.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}


function renderGoalDropdownList(){
  var htmlData = `<option value="">Select</option>`;
  globalData.goal_list.forEach(function(val){
    var selected = "";
    if(val.is_goal_selected == 1){
      selected = "selected";
    }
    htmlData = htmlData + `
      <option value="`+val.id+`" `+selected+`>`+val.goal_title+`</option>
    `;
  });
  document.getElementById('lead_goal_id').innerHTML = htmlData;
}

function renderGoalList(data){
  renderGoalDropdownList();
  var htmlData = "";
  var viewHtml = document.getElementById('goal_view_ul');
  viewHtml.innerHTML = "";
  if(data.length >0){
    var goal_count = data.length;
    if(goal_count <10){
      goal_count = "0"+goal_count;
    }
    document.getElementById("goal_total_count").innerHTML=goal_count;
    data.forEach(function(val){
      var borderVar = "";
      if(val.is_goal_selected == 1){
        borderVar = "border: 2px solid;";
      }else{
        borderVar = "border:none;";
      }
      htmlData = htmlData + `
      <li style="display:inline-block;">
        <div class="max-img" onclick='openGoalViewModel(`+JSON.stringify(val)+`)' style="`+borderVar+`">
          <img src="`+val.goal_icon+`" alt="" />

        </div>
        <span style="margin-top:5px; display:block;">12/100</span>
      </li>`;
    });
  }else{
    document.getElementById("goal_total_count").innerHTML="00";
    htmlData = `
    <li>
      <div class="max-img active">
        No Data found
      </div>
    </li>`;
  }
  viewHtml.innerHTML = htmlData;
}

function renderGeneratorOrProcesserList(data,divName,countDiv,){
  var htmlData = "";
  var viewHtml = document.getElementById(divName);
  var countDivHtml = document.getElementById(countDiv);
  viewHtml.innerHTML = "";
  if(data.length >0){
    var goal_count = data.length;
    if(goal_count <10){
      goal_count = "0"+goal_count;
    }
    countDivHtml.innerHTML = goal_count;
    data.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      htmlData = htmlData + `
      <li style="display:inline-block;">
        <div class="max-img" onclick='openStatOfLeadGeneratorProcesser(`+JSON.stringify(val)+`)'>
          <img src="`+profile_image+`" alt="" />
        </div>
        <span style="display:block; margin-top:5px;">`+val.first_name+`</span>
      </li>`;
    });
  }else{
    countDivHtml.innerHTML = "00";
    htmlData = `
    <li>
      <div class="max-img active">
        No Data found
      </div>
    </li>`;
  }
  viewHtml.innerHTML = htmlData;
}

function renderFollowUpList(data){
  var divName = document.getElementById('lead_follow_up_listing_view');
  divName.innerHTML = "";
  var htmlData = "";
  data.forEach(function(val){
    htmlData = htmlData+`
    <div class="list-box">
      <div>
        <div class="button-left">
          `+val.title+`
        </div>
        <div class="button-right" >
          `+val.date+`
        </div>
        <div class="clearfix"></div>
      </div>
      <div>
        `+val.details+`
      </div>
    </div>
    `;
  });
  divName.innerHTML = htmlData;
}

function renderProcesserDropdownList(){
  var htmlData = "";
  globalData.lead_processers.forEach(function(val){
    htmlData = htmlData + `
      <option value="`+val.id+`">`+val.first_name+` `+val.last_name+`</option>
    `;
  });
  document.getElementById('lead_processer_id').innerHTML = htmlData;
}

function renderLeadGeneratorDropdownList(){
  // Lead Generator
  var htmlData = `<option value="">Select</option>`;
  if(globalData.user_type == "company"){
    globalData.lead_generators.forEach(function(val){
      htmlData = htmlData + `
        <option value="`+val.id+`">`+val.first_name+` `+val.last_name+`</option>
      `;
    });
    document.getElementById('lead_generator_id').innerHTML = htmlData;
  }
}


renderGoalList(globalData.goal_list);
renderGeneratorOrProcesserList(globalData.lead_generators,'lead_generator_view_ul','lead_generator_total_count');
renderGeneratorOrProcesserList(globalData.lead_processers,'lead_processer_view_ul','lead_processer_total_count');
renderProcesserDropdownList();
renderLeadGeneratorDropdownList();




function saveGoalApi(){
  var error = false;
  $('small').empty();
  var data = {};
  //console.log(1);
  var formData = new FormData();
  $("form#addGoalForm :input").each(function(){
    var $this = $(this);
    if($this.attr('name')){
      if($this.attr('name') == 'goal_icon'){
      }else{
        formData.append($this.attr('name'), $this.val());
      }
    }
    var error_message = $this.data('error_message');
    if( ($this.val() == '' || $this.val() == null) && error_message ){
        $('<small class="text-danger">'+error_message+'</small>').insertAfter($this);
          error = true;
    }
  });
  if(error){
    console.log("error");
  }else{
    var fileInput = document.getElementById('goal_icon');
    var file = fileInput.files[0];
    formData.append('goal_icon', file);
    $.ajax({
      url: globalData.base_url+"leads/add-goal",
      type: "POST",
      data: formData,
      contentType: false,
      cache: false,
      processData:false,
      success: function(response)
      {
        response = JSON.parse(response);
        if(response.error_code){
          alert(response.message);
        }else{
          globalData.goal_list = response.data;
          renderGoalList(response.data);
          $('#goalAddModal').modal('hide');
        }
      }
      });
  }
  return false;
}

function deleteGoalApi(){

}

function saveLeadsApi(){
  var error = false;
  $('small').empty();
  var data = {};
  //console.log(1);
  var formData = new FormData();
  $("form#addLeadForm :input").each(function(){
    var $this = $(this);
    if($this.attr('name')){
      if($this.attr('name') == 'lead_icon'){
      }else{
        formData.append($this.attr('name'), $this.val());
      }
    }
    var error_message = $this.data('error_message');
    if( ($this.val() == '' || $this.val() == null) && error_message ){
        $('<small class="text-danger">'+error_message+'</small>').insertAfter($this);
          error = true;
    }
  });
  if(error){
    console.log("error");
  }else{
    var fileInput = document.getElementById('lead_icon');
    var file = fileInput.files[0];
    formData.append('lead_icon', file);
    $.ajax({
      url: globalData.base_url+"leads/add-lead",
      type: "POST",
      data: formData,
      contentType: false,
      cache: false,
      processData:false,
      success: function(response)
      {
        response = JSON.parse(response);
        //console.log(response);
        if(response.error_code){
          alert(response.message);
        }else{
          globalData.goal_list = response.data;
          renderLeadList(response.data);
          $('#leadAddModal').modal('hide');
        }
      }
      });
  }
  return false;
}

function saveLeadGeneratorOrProcesserApi(){
  var error = false;
  $('small').empty();
  var data = {};
  $("form#addLeadGeneratorForm :input").each(function(){
    var $this = $(this);
    if($this.attr('name')){
      if($this.is(':checked')){
        data[$this.attr('name')] = $this.val();
      }
      if($this.attr('name') == 'lead_generator_processer_type'){
        data['type'] = $this.val();
      }
    }
  });
  if(error){
    console.log("error");
  }else{
    $.post( globalData.base_url+"leads/assign-to-lead-generator-processer", data)
      .done(function( response ) {
        response = JSON.parse(response);
        if(response.error_code){
          alert(response.message);
        }else{
          if(data.type == 1){
            globalData.lead_generators = response.data;
            renderGeneratorOrProcesserList(globalData.lead_generators,'lead_generator_view_ul','lead_generator_total_count');
            renderLeadGeneratorDropdownList();
          }else{
            globalData.lead_processers = response.data;
            renderGeneratorOrProcesserList(globalData.lead_processers,'lead_processer_view_ul','lead_processer_total_count');
            renderProcesserDropdownList();
          }
          $('#openAssignUser').modal('hide');
        }
      });
  }
  return false;
}


function saveFollowUpApi(){
  var error = false;
  $('small').empty();
  var data = {};
  $("form#addFollowUpForm :input").each(function(){
    var $this = $(this);
    if($this.attr('name')){
      data[$this.attr('name')] = $this.val();
    }
  });
  if(error){
    console.log("error");
  }else{
    $.post( globalData.base_url+"leads/add-follow-up", data)
      .done(function( response ) {
        response = JSON.parse(response);
        if(response.error_code){
          alert(response.message);
        }else{
          renderFollowUpList(response.result);
          followUpClearForm();
          //$('#leadFollowUpModel').modal('hide');
        }
      });
  }
  return false;
}

function getStateList(state_id = false){
  var data = {};
  data.country_id = document.getElementById('country_id').value;
  $.post(globalData.base_url+"leads/get-state-list", data)
    .done(function( response ) {
      response = JSON.parse(response);
      //console.log(response);
      if(response.error_code){
        alert(response.message);
      }else{
        document.getElementById('state_id').innerHTML = response.data;
        if(state_id){
            document.getElementById('state_id').value = state_id;
        }
      }
  });
}

function saveLeadTypeApi(){
  var data = {};
  data.leader_type_id = document.getElementById('leader_type_id').value;
  data.lead_type_name = document.getElementById('lead_type_name').value;
  $.post(globalData.base_url+"leads/save-lead-type", data)
    .done(function( response ) {
      response = JSON.parse(response);
      //console.log(response);
      if(response.error_code){
        alert(response.message);
      }else{
        document.getElementById("leader_type_id_"+data.leader_type_id).innerHTML = data.lead_type_name;
        $('#openLeadTypeModel').modal('hide');
      }
  });
  return false;
}

function getStatsListApi(data,callback){
  document.getElementById('lead_stats_list_view').style.display = 'none';
  document.getElementById('lead_stats_list_loader').style.display = 'block';
  $.post(globalData.base_url+"leads/get-stats-list", data)
    .done(function( response ) {
      response = JSON.parse(response);
      //console.log(response);
      if(response.error_code){
        alert(response.message);
      }else{
        callback(response);
      }
  });
}

function getLeadDeleteStatsListApi(data,callback){
  $.post(globalData.base_url+"leads/get-lead-delete-stats-list", data)
    .done(function( response ) {
      response = JSON.parse(response);
      //console.log(response);
      if(response.error_code){
        alert(response.message);
      }else{
        callback(response);
      }
  });
}

function deleteCurrentGoalApi(){
  var data = {};
  data.goal_id = document.getElementById('goal_id').value;
  $.post(globalData.base_url+"leads/delete-goal", data)
    .done(function( response ) {
      response = JSON.parse(response);
      if(response.error_code){
        alert(response.message);
      }else{
        globalData.goal_list = response.result;
        renderGoalList(response.result);
        $('#goalAddModal').modal('hide');
        callback(response);
      }
  });
}

function changeIsSelectedGoal(goalId){
  var data = {};
  data.goal_id = goalId;
  $.post(globalData.base_url+"leads/select-goal", data)
    .done(function( response ) {
      response = JSON.parse(response);
      if(response.error_code){
        alert(response.message);
      }else{
        globalData.goal_list = response.result;
        renderGoalList(response.result);
      }
  });
}
