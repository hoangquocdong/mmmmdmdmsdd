var time_out = 1;
function runMiniClock()
{
    time_out++;
    //get date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'/'+mm+'/'+yyyy;
    //get time
    var time = new Date();
    var hours = time.getHours();
    var minutes = time.getMinutes();
    var seconds = time.getSeconds();
    minutes=((minutes < 10) ? "0" : "") + minutes;
    seconds=((seconds < 10) ? "0" : "") + seconds;
    ampm = (hours >= 12) ? "PM" : "AM";
    hours=(hours > 12) ? hours-12 : hours;
    hours=(hours == 0) ? 12 : hours;
    var clock = hours + ":" + minutes + " "+ seconds + " " + ampm;
    if(clock != document.getElementById('miniclock').innerHTML) document.getElementById('miniclock').innerHTML = today+' - '+clock;
    timer = setTimeout("runMiniClock()",1000);
    //logout
    var count_down = 900 -  time_out;
    //document.getElementById("demo1").innerHTML='Logout in '+count_down+' seconds';
    if(time_out == 2000) {
      window.location.href = 'index.php?wpage=login'; 
    }
}
runMiniClock();
//lost focus - logout after xxx seconds   
    function myFunction(e){time_out = 1;}