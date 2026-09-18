var reg_input = $('.regvalid .form-control');
var pass_input = $('.passvalid .form-control');
$(".persondata").click(function(){
  var check = true;
  for(var i=0; i<reg_input.length; i++) {
    if(($(reg_input[i])).attr("required")){
      if(validate(reg_input[i]) == false){
        showValidate(reg_input[i]);
        check=false;
      }
    }
  }
  if(check){
    tabValue.option2 = true;
    tabValue.option3 = true;
  }
  return check;
});


$(".passdetail").click(function(){
  var pass  = $("#password").val();
  var cpass  = $("#cpassword").val();
  var pswd  = $("#password");
  var cpswd  = $("#cpassword");
  if(pass == '' && cpass == ''){
    //alert("sfsdf");
    var check = true;
    for(var i=0; i<pass_input.length; i++) {
      if(validate(pass_input[i]) == false){
        showPassValidate(pass_input[i]);
        check=false;
      }
    }
    return check;
  }
  if(pass == ''){
    //alert("sfsdf");
    showPassValidate(pswd);
    return false;
  }
  if(cpass == ''){
    //alert("sfsdf");
    showPassValidate(cpswd);
    return false;
  }

  if(cpass !== pass){
    //alert("sfsdf");
    $("#cp_error_message").html("Confirm password is not same as password");
    return false;
  }

  tabValue.option3 = true;
  tabValue.option4 = true;
});

function validate (input) {
  if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
    if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
      return false;
    }
  }
  else {
    if($(input).val().trim() == ''){
      return false;
    }
  }
}



$('.regvalid .form-control').each(function(){
  $(this).focus(function(){
    hideValidate(this);
  });
});

$('.passvalid .form-control').each(function(){
  $(this).focus(function(){
    hideValidate(this);
  });
});


function showValidate(input) {
  var thisAlert = $(input).parent();
  $(thisAlert).addClass('alert-validate');
}

function hideValidate(input) {
  var thisAlert = $(input).parent();
  $(thisAlert).removeClass('alert-validate');
}

function showPassValidate(input) {
  var thisAlert = $(input).parent();
  $(thisAlert).addClass('alert-validate');
}

function hidepassValidate(input) {
  var thisAlert = $(input).parent();
  $(thisAlert).removeClass('alert-validate');
}


$("#cpassword").keyup(function(){
  if($(this).val().length !=""){
    $("#cp_error_message").html("");
  }
});
