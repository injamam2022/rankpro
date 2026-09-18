function getTimeRemaining(endtime) {
  var t =  Date.parse(new Date()) - Date.parse(endtime);
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);

  return {
    'total': t,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeTotalExamTime(id, endtime,total_time) {
  var hoursSpan = $('#'+id+' .hours');
  var minutesSpan = $('#'+id+' .minutes');
  var secondsSpan = $('#'+id+' .seconds');

  function updateExamClock() {
    var t = getTimeRemaining(endtime);

    hoursSpan.html(('0' + t.hours).slice(-2));
    minutesSpan.html(('0' + t.minutes).slice(-2));
    secondsSpan.html(('0' + t.seconds).slice(-2));

    var currDis = 100/total_time * parseInt(t.total);
    $('#clockdiv .blueTime > div').css('width',currDis+'%');
    if (t.total >= total_time) {
      clearInterval(examTimeInterval);
      examExamTimer();
    }else{
      if(t.total % 5000 == 0){
        updateTime();
      }
    }
  }
  // updateExamClock();
  var examTimeInterval = setInterval(updateExamClock, 1000);
}

function initializeTotalQuestionTime(id, endtime,total_time) {
  var hoursSpan = $('#'+id+' .hours');
  var minutesSpan = $('#'+id+' .minutes');
  var secondsSpan = $('#'+id+' .seconds');

  function updateQuestionClock() {
    var t = getTimeRemaining(endtime);
    globalData.current_question_time = t;
    hoursSpan.html(('0' + t.hours).slice(-2));
    minutesSpan.html(('0' + t.minutes).slice(-2));
    secondsSpan.html(('0' + t.seconds).slice(-2));

    // var scurrDis = 100/total_time * parseInt(t.total);
    // $('#sclockdiv .blueTime > div').css('width',scurrDis+'%');
    // if (t.total >= total_time) {
    //   clearInterval(globalData.questionTimeInterval);
    // }
  }
  updateQuestionClock();
  if(globalData.questionTimeInterval){
    clearInterval(globalData.questionTimeInterval);
    globalData.questionTimeInterval = setInterval(updateQuestionClock, 1000);
  }else{
    globalData.questionTimeInterval = setInterval(updateQuestionClock, 1000);
  }
}
