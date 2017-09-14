
//** com **//
function sender(e, log)
{
  var butid = e.target.id;
  var com = document.getElementById(butid.replace("but","com"));

  if(com.value.length != 0 && com.value.length <= 250)
  {
    var xhr = new XMLHttpRequest();
    var msg = com.value;
    xhr.onreadystatechange = function() {
  	if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
      {	msgcallback(xhr.responseText, msg);}
  	};
    xhr.open("POST", "config/save_com.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id=" +  encodeURIComponent(com.id) + "&msg=" + encodeURIComponent(com.value) + "&log=" + encodeURIComponent(log));
  }
  com.value = "";
}

function msgcallback(data, comr)
{
  if (data.lenght != 0)
  {
    var tab = data.split(' ; ');
    var combox = document.getElementById('chat_'+tab[1]);
    var newcom = document.createElement('DIV');
    var com = document.createElement('SPAN');
    com.textContent = comr;
    newcom.setAttribute('class', 'com');
    newcom.innerHTML = '<span>' +  tab[0]+ "[" + tab[2] + "]</span> : ";
    newcom.append(com);
    combox.appendChild(newcom);
    combox.scrollTop = combox.scrollHeight;
  }
}

function updatelike(data,id, status, obj)
{
  var like = document.getElementById('like_' + id);
  var dislike = document.getElementById('dislike_' + id);

  if(status == 'like')
  {
    if(data.indexOf('1')!= -1)
    {
      like.textContent = parseInt(like.textContent) - 1;
      obj.textContent = 'Like';
    }
    if(data.indexOf('2') != -1)
    {
      dislike.textContent = parseInt(dislike.textContent) - 1;
      obj.parentNode.getElementsByTagName('button')[1].textContent = 'Dislike';
    }
    if(data.indexOf('3')!= -1)
    {
      like.textContent = parseInt(like.textContent) + 1;
      obj.textContent = 'UnLike';
    }
  }

  if(status == 'dislike')
  {
    if(data.indexOf('1')!= -1)
    {
      dislike.textContent = parseInt(dislike.textContent) - 1;
      obj.textContent = 'Dislike';
    }
    if(data.indexOf('2')!= -1)
    {
      like.textContent = parseInt(like.textContent) - 1;
      obj.parentNode.getElementsByTagName('button')[0].textContent = 'Like';
    }
    if(data.indexOf('3')!= -1)
    {
      dislike.textContent = parseInt(dislike.textContent) + 1;
      obj.textContent = 'UnDislike';
    }
  }
}

function managelike(status, log, id, obj)
{
 if((status == 'like'  || status == 'dislike') && id && id.length != 0 && log && log.length != 0)
 {
   var xhr = new XMLHttpRequest();
   xhr.onreadystatechange = function() {
   if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
     {updatelike(xhr.responseText,id, status,obj);}
   };
   xhr.open("POST", "config/manage_like.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.send("status=" + encodeURIComponent(status) + "&log=" + encodeURIComponent(log) + "&img_id=" + id);
 }
}




 var tampon = 0;

 window.onload = function(){
   var gal = document.getElementById('gallery');
   if(gal.addEventListener)
        gal.addEventListener('scroll', InfiniteHandler, false);
    else if (gal.attachEvent)
        gal.attachEvent('onscroll', InfiniteHandler);
   get_item(gal);
 };

function put_item(data, prev , targ)
{
  if(data)
  {
    var page = document.createElement('DIV');
    tampon++;
    page.id = 'page_' + (tampon);
    page.innerHTML = data;
    targ.appendChild(page);
  }
  else {
    tampon = -1;
  }
}

function get_item(targ)
{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
      {put_item(xhr.responseText,tampon, targ);}
    };
    xhr.open("POST", "config/get_item.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("offset=" + (tampon * 5));

}

function InfiniteHandler()
{
  if(this.scrollTop >= this.scrollHeight - this.clientHeight && tampon >= 0)
  {
    get_item(this);
  }
}
