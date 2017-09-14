//** mise en place du DOM et capture video **//
var truewidth = 320;
(function vid() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 320,
      height = 0;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    { video: true, audio: false },
    function(stream)
    {
      if (navigator.mozGetUserMedia)
      {
        video.mozSrcObject = stream;
      }
      else
      {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err)
    {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }

  }, false);


  function detectmob() {
   if( navigator.userAgent.match(/Android/i)
   || navigator.userAgent.match(/webOS/i)
   || navigator.userAgent.match(/iPhone/i)
   || navigator.userAgent.match(/iPad/i)
   || navigator.userAgent.match(/iPod/i)
   || navigator.userAgent.match(/BlackBerry/i)
   || navigator.userAgent.match(/Windows Phone/i)
   ){
      return true;
    }
   else {
      return false;
    }
  }

  function takepicture() {
    var mob = detectmob();
    var vid = document.getElementById('video');
    if (vid.height > 0 && vid.width > 0)
    {
    if(vid.tagName != 'VIDEO')
    {
      height = 240;
    }
    canvas.width = width;
    canvas.height = height;
    var context = canvas.getContext('2d');
    context.save();
    if(vid.tagName =='VIDEO')
    {
      if (mob == true)
      {
        context.scale(-1 , -1);
        context.drawImage(vid, 0, 0, -width , -height);
      }
      else
      {
        context.scale(-1 , 1);
        context.drawImage(vid, 0, 0, -width , height);
      }
    }
    else
    {
      context.drawImage(vid, 0, 0, width , height);
    }
    context.restore();
    var img =  document.getElementById('current');
    var selected = document.getElementById('picked');
    var shad = document.getElementById('shadow');
    shad.src = selected.src;
    shad.style.width = selected.offsetWidth + 'px';
    shad.style.height = selected.offsetHeight+ 'px';
    img.src = canvas.toDataURL('image/png');
    img.style.top = selected.style.top;
    img.style.left = selected.style.left;
    context.drawImage(selected, selected.offsetLeft, selected.offsetTop - 50, selected.offsetWidth, selected.offsetHeight);
    }
  }


  startbutton.addEventListener('click', function(ev){
      takepicture();
        document.getElementById('savebutton').disabled = false;
    ev.preventDefault();
  }, false);

  savebutton.addEventListener('click', function(ev){
      savepicture(callback);
      document.getElementById('savebutton').disabled = true;
    ev.preventDefault();
  }, false);



  imgload.addEventListener('change',function(ev){

    var file = document.getElementById('imgload').files[0];

    if(file && file.type.match("image/.*"))
    {
      var prev  = document.createElement('img');
      prev.onload = function(){
        if(prev.width < 100 || prev.height < 100){
            pasGlop(prev.width - 100 , prev.height - 100);
        }else{
            glop(prev.width, prev.height);
        }
    }
      var reader = new FileReader();
      reader.addEventListener("loadend", function()
        {
              prev.src = reader.result;
              prev.style = "width: 320px; height:240px; margin-top: 50px; position: relative;";
        }, false);

        reader.readAsDataURL(file);

        var button = document.getElementById('butload');
        button.addEventListener('click',function()
        {
          var vid = document.getElementById('video');

          prev.setAttribute('id', 'video');
          if(prev.width > 0 && prev.height > 0)
          {
            document.getElementById('video').parentNode.replaceChild(prev, vid);
          }
          else
          {
          document.getElementById('startbutton').disabled = true;
          }
          button.disabled= true;

        },false);
    }
    function glop(width, height){
      button.disabled= false;
   }

   function pasGlop(width, height){
       button.disabled= true;
   }
  },false);
}
)();

//***  TRANSPARENT ***//
function mybit(elem){
  picked = document.getElementById('picked');
  if(truewidth > 0)
  {
      document.getElementById('startbutton').disabled = false;
  }
  picked.style.display = 'block';
  picked.src = elem.src;
  picked.style.width = '120px';
  picked.style.height= '90px';
  picked.style.position = 'absolute';
  picked.style.left = '0px';
  picked.style.top = '50px';
  picked.style.zIndex = '1';
  }


  function drag(ev)
  {
    ev.dataTransfer.setData('text', this.id);
  	ev.dataTransfer.setDragImage(ev.target, (ev.target.offsetWidth / 2), (ev.target.offsetHeight / 2));
  }


  function drop(ev)
  {
    var data = ev.dataTransfer.getData("text");
  	var vid = document.getElementById("video");
    var rpos = FindPos(vid);
  	var posx = (ev.clientX - parseInt(rpos['X']) + document.body.scrollLeft - ev.target.offsetWidth / 2);
  	var posy = (ev.clientY  - parseInt(rpos['Y']) + 50 + document.body.scrollTop  - ev.target.offsetHeight / 2);
  	var width = parseInt(vid.offsetWidth);
  	var height = parseInt(vid.offsetHeight);
  	if (posx < width && posx > -ev.target.offsetWidth && posy < height + 50 && posy  > 50 - ev.target.offsetHeight)
  	{
  		ev.target.style.left = posx + "px";
  		ev.target.style.top = posy + "px";
  	}
    ev.preventDefault();
  }


  function move(ev)
  {

  	var vid = document.getElementById("video");
    var rpos = FindPos(vid);
  	var posx = (ev.targetTouches[0].pageX - parseInt(rpos['X']) + document.body.scrollLeft -ev.target.offsetWidth / 2);
  	var posy = (ev.targetTouches[0].pageY - parseInt(rpos['Y']) + 50 + document.body.scrollTop - ev.target.offsetHeight / 2);
  	var width = parseInt(vid.offsetWidth);
  	var height = parseInt(vid.offsetHeight);
  	if (posx < width && posx > -ev.target.offsetWidth && posy < height + 50 && posy  > 50 - ev.target.offsetHeight)
  	{
  		ev.target.style.left = posx + "px";
  		ev.target.style.top = posy + "px";
  	}
  }

  function FindPos(AObject)
  {

      var posX = 0, posY = 0;

      do
      {
          posX += AObject.offsetLeft;
          posY += AObject.offsetTop;
          AObject = AObject.offsetParent;

      }
      while( AObject != null );

      var pos = [];
      pos['X'] = posX;
      pos['Y'] = posY;
      return pos;

  }


//*** SAVING ***//

  function savepicture(callback)
{
  var obj = document.getElementById('current');
  var pick = document.getElementById('shadow');
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			callback(xhr.responseText);
		}
	};
  var url1 = obj.src;
  var url2 = pick.src;
  var info = 'top=' + (obj.style.top) + '&left=' + obj.style.left + '&height=' + parseInt(pick.style.height) + '&width=' + parseInt(pick.style.width);
  xhr.open("POST", "config/save_pict.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("img1=" +  encodeURIComponent(url1) + "&img2=" + encodeURIComponent(url2) + "&" + info);
}

function callback(data)
{
  if (data == "OK")
  {
    var img = document.createElement('img');
    var canvas = document.getElementById('canvas');
    img.src = canvas.toDataURL('image/png');
    img.style.height = 120 + 'px';
    img.style.width = 160 + 'px';
    document.getElementById('mini').appendChild(img);
  }
  else
  {
    alert(data);
  }
}
