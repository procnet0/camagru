function cbsupr(data)
{
  data = data.split("=");
  if(data[0] == 'ok')
  {
  var box = document.getElementById(data[1]).parentNode.parentNode;
  box.remove();
 }
}

function suprim(ev,action)
{
  if(action == 'yes')
  {
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
        cbsupr(xhr.responseText);
      }
    };

    xhr.open("POST", "config/del_pict.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id="+ev.target.id+"&action=suprim");
  }
  else if(action == 'no')
  {
    var box = ev.target.parentNode;
    var but_id = ev.target.id.replace("no_", "");
    var button = document.getElementById(but_id);
    button.disabled=false;
    box.remove();
  }
}

function confirm(ev)
{
  var p = ev.target.parentNode.parentNode;
  var confirmator = document.createElement('p');
  confirmator.innerHTML = 'Are you sure ?<button type="button" class="confirmator" id="yes_' + ev.target.id + '" onclick="suprim(event,\'yes\')">Yes</button><button type="button" class="confirmator" id="no_' + ev.target.id + '" onclick="suprim(event,\'no\')">No</button>';
  p.appendChild(confirmator);
  ev.target.disabled=true;
}
