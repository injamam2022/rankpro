 $(".persondata").click(function(){

  var first_name = $("#first_name").val();
  var last_name = $("#last_name").val();
  var parentname = $("#parentName").val();
  var student_number = $("#sPNumber").val();
  var parent_number = $("#pPNumber").val();
  var email = $("#email1").val();
  

  if(first_name == ''){
    //alert("sfsdf");
    $("#first_name").css('border-color','red');
    return false;
  }

  if(last_name == ''){
    //alert("sfsdf");
    $("#last_name").css('border-color','red');
    return false;
  }

  if(parentname == ''){
    //alert("sfsdf");
    $("#parentName").css('border-color','red');
    return false;
  }

  if(student_number == ''){
    //alert("sfsdf");
    $("#sPNumber").css('border-color','red');
    return false;
  }

  if(parent_number == ''){
    //alert("sfsdf");
    $("#pPNumber").css('border-color','red');
    return false;
  }

  if(email == ''){
    //alert("sfsdf");
    $("#email1").css('border-color','red');
    return false;
  }


});


 $("#first_name").keyup(function(){
            if($(this).val().length !=""){
                $("#first_name").css('border-color','#dfdfdf');
            }
        });



 $("#last_name").keyup(function(){
            if($(this).val().length !=""){
                $("#last_name").css('border-color','#dfdfdf');
            }
        });


 $("#parentName").keyup(function(){
            if($(this).val().length !=""){
                $("#parentName").css('border-color','#dfdfdf');
            }
        });



 $("#sPNumber").keyup(function(){
            if($(this).val().length !=""){
                $("#sPNumber").css('border-color','#dfdfdf');
            }
        });

  $("#pPNumber").keyup(function(){
            if($(this).val().length !=""){
                $("#pPNumber").css('border-color','#dfdfdf');
            }
        });

  $("#email1").keyup(function(){
            if($(this).val().length !=""){
                $("#email1").css('border-color','#dfdfdf');
            }
        });



  $(".passdetail").click(function(){

  	var pass  = $("#password").val();
  	var cpass  = $("#cpassword").val();

  	 if(pass == ''){
    //alert("sfsdf");
    $("#password").css('border-color','red');
    return false;
  }
  	 if(cpass == ''){
    //alert("sfsdf");
    $("#cpassword").css('border-color','red');
    return false;
  }

  if(cpass !== pass){
    //alert("sfsdf");
    $("#cp_error_message").html("Confirm password is not same as password");
    return false;
  }

  });




  $("#password").keyup(function(){
            if($(this).val().length !=""){
                $("#password").css('border-color','#dfdfdf');
            }
        });

  $("#cpassword").keyup(function(){
            if($(this).val().length !=""){
                $("#cpassword").css('border-color','#dfdfdf');
            }
        });




  $("#login_number").keyup(function(){
            if($(this).val().length !=""){
                $("#login_number").css('border-color','#dfdfdf');
            }
        });

  $("#pswd").keyup(function(){
            if($(this).val().length !=""){
                $("#pswd").css('border-color','#dfdfdf');
            }
        });