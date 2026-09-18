document.addEventListener("dragstart", function(event) {
  event.dataTransfer.setData("Text", event.target.id);
  event.target.style.opacity = "0.4";
});

document.addEventListener("dragend", function(event) {
  event.target.style.opacity = "1";
});

document.addEventListener("dragenter", function(event) {
  if ( event.target.className == "col-7" ) {
    event.target.style.background = "#dbdbdb";
  }
  else if (event.target.className == "delet-section" || event.target.className == "delet-box" || event.target.className == "list-unstyled deleteBlock" || event.target.className == "view-box") {
    $('.delet-box').addClass('gotoDel');
  }
});

document.addEventListener("dragover", function(event) {
  event.preventDefault();
});

document.addEventListener("dragleave", function(event) {
  if ( event.target.className == "col-7" ) {
    event.target.style.background = "";
  }
});

document.addEventListener("drop", function(event) {
  event.preventDefault();
  //console.log(event.target.id);
  //alert(event.target.className);
  $('.delet-box').removeClass('gotoDel');
  if ( event.target.className == "col-7" ) {
    event.target.style.background = "";
    var data = event.dataTransfer.getData("Text");
    event.target.appendChild(document.getElementById(data));
    var insertData = {
      "key_code":event.target.id,
      "lead_id":data
    }
    $.post( globalData.base_url+"leads/move-lead", insertData)
      .done(function( response ) {
        response = JSON.parse(response);
        //console.log(response);
        if(response.error_code){
          alert(response.message);
        }else{

        }
      });
  } else if (event.target.className == "delet-section" || event.target.className == "delet-box" || event.target.className == "list-unstyled deleteBlock" || event.target.className == "view-box") {
    var data = event.dataTransfer.getData("Text");
    if(confirm("Do You Want to Remove Data")){
      $('#'+ data).remove();
      $.post( globalData.base_url+"leads/delete-lead", {"lead_id":data})
        .done(function( response ) {
          response = JSON.parse(response);
          //console.log(response);
          if(response.error_code){
            alert(response.message);
          }else{

          }
        });
    }
  }
});

function openLeadAddModal(){
  document.getElementById('lead_id').value = "";
  document.getElementById('business_name').value = "";
  document.getElementById('city').value = "";
  document.getElementById('client_name').value = "";
  document.getElementById('address').value = "";
  document.getElementById('country_id').value = "";
  document.getElementById('email').value = "";
  document.getElementById('lead_icon_view').src = globalData.base_url+"uploads/leads/noimage.jpg";
  document.getElementById('facebook').value = "";
  document.getElementById('linkedin').value = "";
  document.getElementById('notes').value = "";
  document.getElementById('phone').value = "";
  document.getElementById('skype').value = "";
  document.getElementById('source').value = "";
  document.getElementById('state_id').value = "";
  document.getElementById('twitter').value = "";
  document.getElementById('website').value = "";
  document.getElementById('whatsapp').value = "";
  document.getElementById('zipcode').value = "";
  $('#lead_processer_id').val([]);
  $('#lead_processer_id').trigger('change');
  $('#leadAddModal').modal('show');
}

function openLeadEditModal(data){
  console.log(data);
  document.getElementById('lead_id').value = data.id;
  document.getElementById('business_name').value = data.business_name;
  document.getElementById('city').value = data.city;
  document.getElementById('client_name').value = data.client_name;
  document.getElementById('address').value = data.address;
  document.getElementById('country_id').value = data.country_id;
  document.getElementById('email').value = data.email;
  var leadImage = globalData.base_url+"uploads/leads/noimage.jpg";
  if(data.lead_icon){
    leadImage = globalData.base_url+"uploads/leads/"+data.lead_icon;
  }
  document.getElementById('lead_icon_view').src = leadImage;
  getStateList(data.state_id);
  document.getElementById('facebook').value = data.facebook;
  document.getElementById('linkedin').value = data.linkedin;
  document.getElementById('notes').value = data.notes;
  document.getElementById('phone').value = data.phone;
  document.getElementById('skype').value = data.skype;
  document.getElementById('source').value = data.source;
  document.getElementById('state_id').value = data.state_id;
  document.getElementById('twitter').value = data.twitter;
  document.getElementById('website').value = data.website;
  document.getElementById('whatsapp').value = data.whatsapp;
  document.getElementById('zipcode').value = data.zipcode;
  var user = data.processer_ids.split(',');
  console.log(user);
  $('#lead_processer_id').val(user);
  $('#lead_processer_id').trigger('change');
  $('#leadAddModal').modal('show');
}

function readURL(input,viewId,keyName) {
  if (input.files && input.files[0]) {
    globalData[keyName] = input.files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
      $(viewId).attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#lead_icon").change(function() {
  readURL(this,'#lead_icon_view','lead_icon');
});

$("#goal_icon").change(function() {
  readURL(this,'#goal_icon_view','goal_icon');
});


function openAssignUserModal(data,type){
  var users = [];
  //console.log(globalData);
  data.forEach(function(val){
    users.push(val.id);
  });
  document.getElementById('lead_generator_processer_type').value = type;
  //console.log(users);
  var htmlData = "";
  if(globalData.user_list.length>0){
    globalData.user_list.forEach(function(val){
      // console.log(val);
      var checked = "";
      if(users.includes(val.id)){
        checked = "checked";
      }
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'public/uploads/user_images/thumbnail/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"public/uploads/avatar1.png";
      }
      htmlData = htmlData + `<div class="col-sm-12" style="height:50px; padding-top:14px;">
        <div class="row">
          <div class="col-sm-10">
            <label for="user__`+val.id+`">
              <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
              <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.first_name+` `+val.last_name+`</span>
            </lable>
          </div>
          <div class="col-sm-2">
            <input type="checkbox" id="user__`+val.id+`" name="user__`+val.id+`" value="`+val.id+`" `+checked+`>
          </div>
        </div>
      </div>`;
    });
  }else{
    htmlData = `<div class="col-sm-12 text-center text-danger" style="height:50px; padding-top:14px;">
      <span style="font-size: 15px;">No data found </span>
    </div>`;
  }
  var htmlDataFreelancer = "";
  if(globalData.freelancer_list.length>0){
    globalData.freelancer_list.forEach(function(val){
      var checked = "";
      if(users.includes(val.id)){
        checked = "checked";
      }

      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'public/uploads/user_images/thumbnail/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"public/uploads/avatar1.png";
      }
      htmlDataFreelancer = htmlDataFreelancer + `<div class="col-sm-12" style="height:50px; padding-top:14px;">
        <div class="row">
          <div class="col-sm-10">
            <label for="user__`+val.id+`">
              <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
              <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.first_name+` `+val.last_name+`</span>
            </lable>
          </div>
          <div class="col-sm-2">
            <input type="checkbox" id="user__`+val.id+`" name="user__`+val.id+`" value="`+val.id+`" `+checked+`>
          </div>
        </div>
      </div>`;
    });
  }else{
    htmlDataFreelancer = `<div class="col-sm-12 text-center text-danger" style="height:50px; padding-top:14px;">
      <span style="font-size: 15px;">No data found </span>
    </div>`;
  }
  document.getElementById("employees").innerHTML = htmlData;
  document.getElementById("freelancer").innerHTML = htmlDataFreelancer;
  $('#openAssignUser').modal('show');
}

function changeMenuOfModal(tabName){
  document.getElementById("tab_employees").classList.remove("active");
  document.getElementById("tab_freelancer").classList.remove("active");

  document.getElementById("employees").style.display = "none";
  document.getElementById("freelancer").style.display = "none";

  document.getElementById(tabName).style.display = "block";
  document.getElementById("tab_"+tabName).classList.add("active");
}

function openLeadTypeEdit(leader_type_id){
  console.log(leader_type_id);
  var temoData =  document.getElementById('leader_type_id_'+leader_type_id).innerHTML;
  console.log(temoData);
  document.getElementById('leader_type_id').value = leader_type_id;
  document.getElementById('lead_type_name').value = temoData;
  $('#openLeadTypeModel').modal('show');
}

function openLeadFollowUpModel(lead_id){
  document.getElementById('lead_follow_up_listing_view').innerHTML = '<div class="row text-center"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></div>';
  document.getElementById('add_follow_up_lead_id').value = lead_id;
  followUpClearForm();
  var data = {
    "lead_id":lead_id
  };
  $.post(globalData.base_url+"leads/lead-follow-up", data)
    .done(function( response ) {
      response = JSON.parse(response);
      console.log(response);
      if(response.error_code){
        alert(response.message);
      }else{
        renderFollowUpList(response.result);
      }
  });
  $('#leadFollowUpModel').modal('show');
}

function followUpClearForm(){
  document.getElementById('add_follow_up_details').value = "";
  document.getElementById('add_follow_up_title').value = "";
  document.getElementById('add_follow_up_date').value = "";
  openOrHideAddFollowUpForm(0);
  addFollowUpLeadScoreFunction(1);
  followUpChangeButton(0);
}


function followUpChangeButton(type){
  document.getElementById('add_follow_up_type').value = type;
  var followUp = document.getElementById('add_follow_up_button');
  var appointment = document.getElementById('add_appointment_button');
  followUp.classList.remove('activeButton');
  appointment.classList.remove('activeButton');
  followUp.getElementsByClassName('active')[0].style.display = 'none';
  appointment.getElementsByClassName('active')[0].style.display = 'none';
  if(type == 1){
    followUp.classList.add('activeButton');
    followUp.getElementsByClassName('active')[0].style.display = 'block';
  }else{
    appointment.classList.add('activeButton');
    appointment.getElementsByClassName('active')[0].style.display = 'block';
  }
}

function openOrHideAddFollowUpForm(type){
  var followUpForm = document.getElementById('lead_follow_up_add_view');
  if(type){
    followUpForm.style.display = "block";
  }else{
    followUpForm.style.display = "none";
  }
}

function addFollowUpLeadScoreFunction(data){
  document.getElementById('add_follow_up_lead_score').value = data;
  document.getElementById("addFollowUpStem1").style.backgroundColor = "green";
  document.getElementById("addFollowUpKick1").style.backgroundColor = "green";
  document.getElementById("addFollowUpStem2").style.backgroundColor = "yellow";
  document.getElementById("addFollowUpKick2").style.backgroundColor = "yellow";
  document.getElementById("addFollowUpStem3").style.backgroundColor = "red";
  document.getElementById("addFollowUpKick3").style.backgroundColor = "red";
  document.getElementById("addFollowUpStem"+data).style.backgroundColor = "#fff";
  document.getElementById("addFollowUpKick"+data).style.backgroundColor = "#fff";
}

function openLeadStatsModel(){
  $('#leadStatsModel').modal('show');
}

function openUploadCsvModel(){
  $('#uploadCsvModel').modal('show');
}


function openDropdownLeadStats(type){
  document.getElementById('lead_stats_dropdown_view').style.display = "block";
  var html = "";
  var profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";

  if(type ==1){
    html = `
  <div class="dropdown-body" style="margin-left: 20px;" onclick="event.stopPropagation();">
    <div class="mini-dropdown" id="lead_stats_goal_dropdown_view">`;
      globalData.goal_list.forEach(function(val){
        var checked = "";
        if(globalData.lead_global_stats_goal.find(o => o.id == val.id)){
          checked = "checked";
        }
        html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
          <div class="col-sm-2">
            <label class="custom-check">
              <input type="checkbox" name="goal_dropdown_model" value="`+val.id+`"  id="goal_dropdown_model_`+val.id+`" `+checked+` onchange="checkBoxSectionGlobalStats(1);">
              <span class="checkmark"></span>
            </label>
          </div>
          <div class="col-sm-9">
            <label for="goal_dropdown_model_`+val.id+`">
              <img class="img-circle avatar" width="40" height="40" src="`+val.goal_icon+`">
              <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.goal_title+`</span>
            </lable>
          </div>
        </div>`;
      });
    html += `<div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>`;
  }else if(type == 2){
    html = `
      <div class="dropdown-body" style="margin-left: 206px;" onclick="event.stopPropagation();">
        <div class="mini-dropdown" id="lead_stats_lead_generator_dropdown_view">`;
        globalData.lead_generators.forEach(function(val){
          var checked = "";
          if(globalData.lead_global_stats_generators.find(o => o.lead_generator_id == val.lead_generator_id)){
            checked = "checked";
          }
          var profile_image;
          if(val.profile_image){
            profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
          }else{
            profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
          }
          html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
            <div class="col-sm-2">
              <label class="custom-check">
                <input type="checkbox" name="lead_generator_dropdown_model" `+checked+` value="`+val.lead_generator_id+`" id="lead_generator_dropdown_model_`+val.lead_generator_id+`" onchange="checkBoxSectionGlobalStats(2)">
                <span class="checkmark"></span>
              </label>
            </div>
            <div class="col-sm-9">
              <label for="lead_generator_dropdown_model_`+val.lead_generator_id+`">
                <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
                <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.name+`</span>
              </lable>
            </div>
          </div>`;
        });
      html += `<div class="clearfix"></div>
        </div>
      </div>
      <div class="clearfix"></div>`;
  }else{
    html = `
  <div class="dropdown-body" style="margin-left: 392px;" onclick="event.stopPropagation();">
    <div class="mini-dropdown" id="lead_stats_lead_processers_dropdown_view">`;
    globalData.lead_processers.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      var checked = "";
      if(globalData.lead_global_stats_processers.find(o => o.lead_processer_id == val.lead_processer_id)){
        checked = "checked";
      }
      html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
        <div class="col-sm-2">
          <label class="custom-check">
            <input type="checkbox" name="lead_processers_dropdown_model" `+checked+` value="`+val.lead_processer_id+`" id="lead_processers_dropdown_model_`+val.lead_processer_id+`" onchange="checkBoxSectionGlobalStats(3);">
            <span class="checkmark"></span>
          </label>
        </div>
        <div class="col-sm-9">
          <label for="lead_processers_dropdown_model_`+val.lead_processer_id+`">
            <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
            <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.name+`</span>
          </lable>
        </div>
      </div>`;
    });
    html += ` <div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>`;
  }
  document.getElementById('lead_stats_dropdown_view').innerHTML = html;
}

function checkBoxSectionGlobalStats(type){
  if(type == 1){
    globalData.lead_global_stats_goal= [];
    $.each($("input[name='goal_dropdown_model']:checked"), function(){
      var checkGoalId = $(this).val();
      globalData.goal_list.forEach(function(val){
        if(val.id == checkGoalId){
          globalData.lead_global_stats_goal.push(val);
        }
      });
    });
    console.log(globalData);
  }else if(type == 2){
    globalData.lead_global_stats_generators = [];
    $.each($("input[name='lead_generator_dropdown_model']:checked"), function(){
      var checkGeneratorId = $(this).val();
      globalData.lead_generators.forEach(function(val){
        if(val.lead_generator_id == checkGeneratorId){
          globalData.lead_global_stats_generators.push(val);
        }
      });
    });
  }else if(type == 3){
    globalData.lead_global_stats_processers = [];
    $.each($("input[name='lead_processers_dropdown_model']:checked"), function(){
      var checkProcesserId = $(this).val();
      globalData.lead_processers.forEach(function(val){
        if(val.lead_processer_id == checkProcesserId){
          globalData.lead_global_stats_processers.push(val);
        }
      });
    });
  }else{
    alert("Something wrong please reload the page.");
  }
  leadStatsGoalSelectedGoal();
}

function leadStatsGoalSelectedGoal(){
  var html = ``;
  if(globalData.lead_global_stats_goal.length>0){
    html += `<div class="button-left active2 text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_global_stats_goal.forEach(function(val){
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+val.goal_icon+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-left active2 text-center">Goal</div>`;
  }
  document.getElementById('lead_stats_goal_selected_goal_view').innerHTML = html;

  var html = ``;
  if(globalData.lead_global_stats_generators.length>0){
    html +=`<div class="button-middle text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_global_stats_generators.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+profile_image+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-middle text-center">Lead Generators</div>`;
  }
  document.getElementById('lead_stats_goal_selected_lead_generator_view').innerHTML = html;

  var html = ``;
  if(globalData.lead_global_stats_processers.length>0){
    html +=`<div class="button-right text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_global_stats_processers.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+profile_image+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-right text-center">Lead Processers</div>`;
  }
  document.getElementById('lead_stats_goal_selected_lead_processer_view').innerHTML = html;
}

function statsListChange(name){
  document.getElementById('stats_total_leads_button_list').style.display = 'none';
  document.getElementById('stats_leads_converted_button_list').style.display = 'none';
  document.getElementById('stats_trashed_leads_button_list').style.display = 'none';
  if(name){
    document.getElementById(name+'_list').style.display = 'block';
  }
}

function filterGlobalLeadStats(){
  var data = {};
  if(globalData.lead_global_stats_goal.length>0){
    data.goal_ids = "";
    globalData.lead_global_stats_goal.forEach(function(val){
      if(data.goal_ids){
        data.goal_ids += ','+val.id;
      }else{
        data.goal_ids += val.id;
      }
    });
  }
  if(globalData.lead_global_stats_generators.length>0){
    data.lead_generator_ids = "";
    globalData.lead_global_stats_generators.forEach(function(val){
      if(data.lead_generator_ids){
        data.lead_generator_ids += ','+val.lead_generator_id;
      }else{
        data.lead_generator_ids += val.lead_generator_id;
      }
    });
  }
  if(globalData.lead_global_stats_processers.length>0){
    data.lead_processer_ids = "";
    globalData.lead_global_stats_processers.forEach(function(val){
      if(data.lead_processer_ids){
        data.lead_processer_ids += ','+val.lead_processer_id;
      }else{
        data.lead_processer_ids += val.lead_processer_id;
      }
    });
  }
  data.start_date = document.getElementById('lead_stats_start_date').value;
  data.end_date = document.getElementById('lead_stats_end_date').value;

  console.log(data);
  getStatsListApi(data,function(response){
    globalData.lead_global_stats_list = response.result;
    renderViewLeadGlobalStatsList(globalData.lead_global_stats_list);
  });
  return false;
}

function renderViewLeadGlobalStatsList(data){

  document.getElementById('stats_total_leads_total_count').innerHTML = data.total_leads.length;
  document.getElementById('stats_leads_converted_total_count').innerHTML = data.leads_converted.length;
  document.getElementById('stats_trashed_total_count').innerHTML = data.trashed_leads.length;
  var notFoundHtml = `
  <div class="row text-center" style="padding: 10px;color: green;font-size: 15px;">
    <span>No data found</span>
  </div>
  `;
  html = ``;
  if(data.total_leads.length > 0){
    data.total_leads.forEach(function(val){
      var lead_icon = "";
      if(val.lead_icon){
        lead_icon = globalData.base_url+"uploads/leads/"+val.lead_icon;
      }else{
        lead_icon = globalData.base_url+"uploads/leads/noimage.jpg";
      }
      html += `
        <div style="background-color:#FFFFFF;padding: 6px;border: 1px solid #ccc;">
          <div style="float:left;padding-left:4px;">
            <img src="`+lead_icon+`" width="40" height="40" style="border-radius: 50%;">
          </div>
          <div style="padding-right:10px;padding-left: 55px;">
            <span style="color: #212529; font-weight: 600;">`+val.client_name+`</span><br />
            <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      `;
    });
  }else{
    html = notFoundHtml;
  }
  document.getElementById('stats_total_leads_button_list').innerHTML = html;

  html = ``;
  if(data.leads_converted.length > 0){
    data.leads_converted.forEach(function(val){
      var lead_icon = "";
      if(val.lead_icon){
        lead_icon = globalData.base_url+"uploads/leads/"+val.lead_icon;
      }else{
        lead_icon = globalData.base_url+"uploads/leads/noimage.jpg";
      }
      html += `
        <div style="background-color:#FFFFFF;padding: 6px;border: 1px solid #ccc;">
          <div style="float:left;padding-left:4px;">
            <img src="`+lead_icon+`" width="40" height="40" style="border-radius: 50%;">
          </div>
          <div style="padding-right:10px;padding-left: 55px;">
            <span style="color: #212529; font-weight: 600;">`+val.client_name+`</span><br />
            <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      `;
    });
  }else{
    html = notFoundHtml;
  }
  document.getElementById('stats_leads_converted_button_list').innerHTML = html;

  html = ``;
  if(data.trashed_leads.length > 0){
    data.trashed_leads.forEach(function(val){
      var lead_icon = "";
      if(val.lead_icon){
        lead_icon = globalData.base_url+"uploads/leads/"+val.lead_icon;
      }else{
        lead_icon = globalData.base_url+"uploads/leads/noimage.jpg";
      }
      html += `
        <div style="background-color:#FFFFFF;padding: 6px;border: 1px solid #ccc;">
          <div style="float:left;padding-left:4px;">
            <img src="`+lead_icon+`" width="40" height="40" style="border-radius: 50%;">
          </div>
          <div style="padding-right:10px;padding-left: 55px;">
            <span style="color: #212529; font-weight: 600;">`+val.client_name+`</span><br />
            <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</span>
          </div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      `;
    });
  }else{
    html = notFoundHtml;
  }
  document.getElementById('stats_trashed_leads_button_list').innerHTML = html;
  document.getElementById('lead_stats_list_view').style.display = 'block';
  document.getElementById('lead_stats_list_loader').style.display = 'none';
}

function hideDropdownLeadStats(){
  document.getElementById('lead_stats_dropdown_view').style.display = "none";
}

function viewDeletedLeads(){
  $('#viewDeletedLeadsModel').modal('show');
}

function openStatOfLeadGeneratorProcesser(data){
  // var lead_id;
  // var lead_type;
  // if(val.lead_processer_id){
  //   lead_id = val.lead_processer_id;
  //   lead_type = 1;
  // }else if(val.lead_generator_id){
  //   lead_id = val.lead_generator_id;
  //   lead_type = 2;
  // }else{
  //   lead_id = 0;
  //   lead_type = 0;
  // }
  //console.log(data);
  //$('#leadStatsModel').modal('show');
}

function openGoalViewModel(data){
  changeIsSelectedGoal(data.id);
  var start_time = new Date(data.start_time);
  start_time = start_time.getFullYear()+'-'+(start_time.getMonth()+1)+'-'+start_time.getDate();
  var end_time = new Date(data.end_time);
  end_time = end_time.getFullYear()+'-'+(end_time.getMonth()+1)+'-'+end_time.getDate();
  document.getElementById('goal_id').value = data.id;
  document.getElementById('goal_title').value = data.goal_title;
  document.getElementById('goal_defination').value = data.goal_defination;
  document.getElementById('target_audience').value = data.target_audience;
  document.getElementById('industry').value = data.industry;
  document.getElementById('allocation').value = data.allocation;
  document.getElementById('source_to_consider').value = data.source_to_consider;
  document.getElementById('no_of_target_lead').value = data.no_of_target_lead;
  document.getElementById('goal_start_time').value = start_time;
  document.getElementById('goal_end_time').value = end_time;
  document.getElementById('goal_icon_view').src = data.goal_icon;
  document.getElementById('deleteButtonOfGoalModel').style.display = "block";

  //console.log(data);
  $('#goalAddModal').modal('show');
}

function openGoalAddModal(){
  var goalImage = "no-image.png";
  document.getElementById('goal_id').value = "";
  document.getElementById('goal_title').value = "";
  document.getElementById('goal_defination').value = "";
  document.getElementById('target_audience').value = "";
  document.getElementById('industry').value = "";
  document.getElementById('allocation').value = "";
  document.getElementById('source_to_consider').value = "";
  document.getElementById('no_of_target_lead').value = "";
  document.getElementById('goal_start_time').value ="";
  document.getElementById('goal_end_time').value = "";
  document.getElementById('goal_icon_view').src = globalData.base_url+'uploads/goals/'+goalImage;
  document.getElementById('deleteButtonOfGoalModel').style.display = "none";
  $('#goalAddModal').modal('show');
}

function openDropdownLeadDeleteStats(type){
  document.getElementById('lead_delete_stats_dropdown_view').style.display = "block";
  var html = "";
  var profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";

  if(type ==1){
    html = `
      <div class="dropdown-body" style="" onclick="event.stopPropagation();">
        <div class="mini-dropdown" id="lead_delete_stats_goal_dropdown_view">`;
          globalData.goal_list.forEach(function(val){
            var checked = "";
            if(globalData.lead_delete_global_stats_goal.find(o => o.id == val.id)){
              checked = "checked";
            }
            html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
              <div class="col-sm-2">
                <label class="custom-check">
                  <input type="checkbox" name="delete_goal_dropdown_model" value="`+val.id+`"  id="goal_dropdown_model_`+val.id+`" `+checked+` onchange="checkBoxSectionDeleteLeadStats(1);">
                  <span class="checkmark"></span>
                </label>
              </div>
              <div class="col-sm-9">
                <label for="goal_dropdown_model_`+val.id+`">
                  <img class="img-circle avatar" width="40" height="40" src="`+val.goal_icon+`">
                  <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.goal_title+`</span>
                </lable>
              </div>
            </div>`;
          });
        html += `<div class="clearfix"></div>
        </div>
      </div>
      <div class="clearfix"></div>`;
  }else if(type == 2){
    html = `
      <div class="dropdown-body" style="margin-left: 392px;" onclick="event.stopPropagation();">
        <div class="mini-dropdown" id="lead_delete_stats_lead_generator_dropdown_view">`;
        globalData.lead_generators.forEach(function(val){
          var checked = "";
          if(globalData.lead_delete_global_stats_generators.find(o => o.lead_generator_id == val.lead_generator_id)){
            checked = "checked";
          }
          var profile_image;
          if(val.profile_image){
            profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
          }else{
            profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
          }
          html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
            <div class="col-sm-2">
              <label class="custom-check">
                <input type="checkbox" name="lead_delete_generator_dropdown_model" `+checked+` value="`+val.lead_generator_id+`" id="lead_generator_dropdown_model_`+val.lead_generator_id+`" onchange="checkBoxSectionDeleteLeadStats(2)">
                <span class="checkmark"></span>
              </label>
            </div>
            <div class="col-sm-9">
              <label for="lead_generator_dropdown_model_`+val.lead_generator_id+`">
                <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
                <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.name+`</span>
              </lable>
            </div>
          </div>`;
        });
      html += `<div class="clearfix"></div>
        </div>
      </div>
      <div class="clearfix"></div>`;
  }else{
    html = `
  <div class="dropdown-body" style="right: 70px; position: absolute;" onclick="event.stopPropagation();">
    <div class="mini-dropdown" id="lead_delete_stats_lead_processers_dropdown_view">`;
    globalData.lead_processers.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      var checked = "";
      if(globalData.lead_delete_global_stats_processers.find(o => o.lead_processer_id == val.lead_processer_id)){
        checked = "checked";
      }
      html += `<div class="col-sm-12" style="height:50px; padding-top:14px;">
        <div class="col-sm-2">
          <label class="custom-check">
            <input type="checkbox" name="lead_delete_processers_dropdown_model" `+checked+` value="`+val.lead_processer_id+`" id="lead_processers_dropdown_model_`+val.lead_processer_id+`" onchange="checkBoxSectionDeleteLeadStats(3);">
            <span class="checkmark"></span>
          </label>
        </div>
        <div class="col-sm-9">
          <label for="lead_processers_dropdown_model_`+val.lead_processer_id+`">
            <img class="img-circle avatar" width="40" height="40" src="`+profile_image+`">
            <span style="font-size: 15px;display:inline-block; padding-left:15px;">`+val.name+`</span>
          </lable>
        </div>
      </div>`;
    });
    html += ` <div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>`;
  }
  document.getElementById('lead_delete_stats_dropdown_view').innerHTML = html;
}

function hideDropdownLeadDeleteStats(){
  document.getElementById('lead_delete_stats_dropdown_view').style.display = "none";
}

function checkBoxSectionDeleteLeadStats(type){
  if(type == 1){
    globalData.lead_delete_global_stats_goal= [];
    $.each($("input[name='delete_goal_dropdown_model']:checked"), function(){
      var checkGoalId = $(this).val();
      globalData.goal_list.forEach(function(val){
        if(val.id == checkGoalId){
          globalData.lead_delete_global_stats_goal.push(val);
        }
      });
    });
    console.log(globalData);
  }else if(type == 2){
    globalData.lead_delete_global_stats_generators = [];
    $.each($("input[name='lead_delete_generator_dropdown_model']:checked"), function(){
      var checkGeneratorId = $(this).val();
      globalData.lead_generators.forEach(function(val){
        if(val.lead_generator_id == checkGeneratorId){
          globalData.lead_delete_global_stats_generators.push(val);
        }
      });
    });
  }else if(type == 3){
    globalData.lead_delete_global_stats_processers = [];
    $.each($("input[name='lead_delete_processers_dropdown_model']:checked"), function(){
      var checkProcesserId = $(this).val();
      globalData.lead_processers.forEach(function(val){
        if(val.lead_processer_id == checkProcesserId){
          globalData.lead_delete_global_stats_processers.push(val);
        }
      });
    });
  }else{
    alert("Something wrong please reload the page.");
  }
  leadDeleteStatsGoalSelectedGoal();
}

function leadDeleteStatsGoalSelectedGoal(){
  var html = ``;
  if(globalData.lead_delete_global_stats_goal.length>0){
    html +=`<div class="button-left active2 text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_delete_global_stats_goal.forEach(function(val){
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+val.goal_icon+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-left active2 text-center">Goal</div>`;
  }
  document.getElementById('lead_delete_stats_goal_selected_goal_view').innerHTML = html;

  var html = ``;
  if(globalData.lead_delete_global_stats_generators.length>0){
    html +=`<div class="button-middle text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_delete_global_stats_generators.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+profile_image+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-middle text-center">Lead Generators</div>`;
  }
  document.getElementById('lead_delete_stats_goal_selected_lead_generator_view').innerHTML = html;

  var html = ``;
  if(globalData.lead_delete_global_stats_processers.length>0){
    html +=`<div class="button-right text-center">&nbsp;</div><ul class="select-img">`;
    globalData.lead_delete_global_stats_processers.forEach(function(val){
      var profile_image;
      if(val.profile_image){
        profile_image = globalData.base_url+'uploads/user_images/thumb/'+val.profile_image;
      }else{
        profile_image = globalData.base_url+"assets/admin/images/noimage.jpg";
      }
      html += `<li><img class="img-circle avatar" width="35" height="35" src="`+profile_image+`"></li>`;
    });
    html += `</ul>`;
  }else{
    html = `<div class="button-right text-center">Lead Processers</div>`;
  }
  document.getElementById('lead_delete_stats_goal_selected_lead_processer_view').innerHTML = html;
}


function renderViewLeadDeleteGlobalStatsList(data){
  html = ``;
  if(data.length > 0){
    data.forEach(function(val){
      var lead_icon = "";
      if(val.lead_icon){
        lead_icon = globalData.base_url+"uploads/leads/"+val.lead_icon;
      }else{
        lead_icon = globalData.base_url+"uploads/leads/noimage.jpg";
      }
      var imageLink = globalData.base_url+"uploads/leads/noimage.jpg";
      if(val.lead_icon){
        imageLink = globalData.base_url+"uploads/leads/"+val.lead_icon;
      }
      html += `
          <tr>
            <td>
              <label class="custom-check">
                <input type="checkbox" checked="checked">
              </label>
            </td>
            <td><img src="`+imageLink+`"> `+val.client_name+`</td>
            <td>`+val.notes+`</td>
            <td>
              <ul class="unstyled-list">
                <li>
                  <a href="javascript:void(0);" onclick="window.open('`+val.facebook+`')" target="_blank">
                    <img src="`+globalData.base_url+`assets/img/fb.png">
                  </a>
                </li>
                <li>
                  <a href="javascript:void(0);" onclick="window.open('`+val.linkedin+`')" target="_blank">
                    <img src="`+globalData.base_url+`assets/img/linkindn.png">
                  </a>
                </li>
                <li>
                  <a href="javascript:void(0);" onclick="window.open('`+val.twitter+`')" target="_blank">
                    <img src="`+globalData.base_url+`assets/img/twitter.png">
                  </a>
                </li>
              </ul>
            </td>
            <td>`+val.created+`</td>
            <td><img src="`+globalData.base_url+`assets/img/delect-box.png"></td>
          </tr>
      `;
    });
  }else{
    html = `
    <tr>
      <td colspan="6" class="text-center">
        Not Data Nound
      </td>
    </tr>
    `;
  }
  document.getElementById('stats_lead_delete_button_list').innerHTML = html;
  // <li>
  //   <a href="javascript:void(0);" onclick="window.open('`+val.facebook+`')" target="_blank">
  //     <img src="`+globalData.base_url+`assets/img/google.png">
  //   </a>
  // </li>
}

function filterGlobalLeadDeleteStats(){
  var data = {};
  if(globalData.lead_delete_global_stats_goal.length>0){
    data.goal_ids = "";
    globalData.lead_delete_global_stats_goal.forEach(function(val){
      if(data.goal_ids){
        data.goal_ids += ','+val.id;
      }else{
        data.goal_ids += val.id;
      }
    });
  }
  if(globalData.lead_delete_global_stats_generators.length>0){
    data.lead_generator_ids = "";
    globalData.lead_delete_global_stats_generators.forEach(function(val){
      if(data.lead_generator_ids){
        data.lead_generator_ids += ','+val.lead_generator_id;
      }else{
        data.lead_generator_ids += val.lead_generator_id;
      }
    });
  }
  if(globalData.lead_delete_global_stats_processers.length>0){
    data.lead_processer_ids = "";
    globalData.lead_delete_global_stats_processers.forEach(function(val){
      if(data.lead_processer_ids){
        data.lead_processer_ids += ','+val.lead_processer_id;
      }else{
        data.lead_processer_ids += val.lead_processer_id;
      }
    });
  }
  data.start_date = document.getElementById('lead_delete_stats_start_date').value;
  data.end_date = document.getElementById('lead_delete_stats_end_date').value;

  console.log(data);
  var loader = `
    <tr>
      <td colspan="6" class="text-center">
        <div class="row text-center" id="lead_stats_list_loader">
          <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
        </div>
      </td>
    </tr>
  `;
  document.getElementById('stats_lead_delete_button_list').innerHTML = loader;
  getLeadDeleteStatsListApi(data,function(response){
    globalData.lead_delete_global_stats_list = response.result;
    renderViewLeadDeleteGlobalStatsList(globalData.lead_delete_global_stats_list);
  });
  return false;
}


/*  Auto Load */
leadDeleteStatsGoalSelectedGoal();
renderViewLeadGlobalStatsList(globalData.lead_global_stats_list);
renderViewLeadDeleteGlobalStatsList(globalData.lead_delete_global_stats_list);
leadStatsGoalSelectedGoal();
statsListChange('stats_total_leads_button');
changeMenuOfModal("employees");
addFollowUpLeadScoreFunction(1);
