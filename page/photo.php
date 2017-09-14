<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/database.php');


try
{
  $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e)
  {
    print "Error!: transparent db" . $e->getMessage() . " <br/>";
    die();
  }

try {
  $sql = $pdo->prepare("SELECT img_id , img_name , data_url FROM transparent");
  $sql->execute();
  $obj = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  print "Error!:transparent recup " . $e->getMessage() . " <br/>";
  die();
}


include_once($_SERVER['DOCUMENT_ROOT'].'/camagru/config/verifactual.php');
?>
<div id="videoarea">
  <div id="main" class="videobox">
    <div class="videobox">
      <video id="video" alt='Video off'></video>
      <img src="" id="picked" ondragend="drop(event)" ondragstart="drag(event)"
       <?php
       if (!stristr($_SERVER['HTTP_USER_AGENT'], 'Linux') && !stristr($_SERVER['HTTP_USER_AGENT'], 'Windows') && !stristr($_SERVER['HTTP_USER_AGENT'], 'Macintosh'))
        {
          echo 'onTouchMove="move(event)"';
        }
       ?>
       >
      <form id="formload">
        <input type="file" id="imgload" name="imgload" accept="image/*" />
        <button type="button" id="butload" disabled>Load</button>
      </form>
      <button id="startbutton" type="button" disabled >Snap</button>
    </div>
    <canvas id="canvas"></canvas>
    <img id="current" style="display: none;">
    <img id="shadow" style="display: none;">
    <button id="savebutton" type="button"  disabled>Save</button>
    <div class="videobox transparentbox">
      <?php
        foreach($obj as $elem)
        {
          echo '<img src="./' . $elem['data_url'] . $elem['img_name'] . '.png" id="transparent' . $elem['img_id'] . '" class="transparent" onclick="mybit(this)">' ;
        }
       ?>
    </div>
    <span style="color : lightblue; font-size: 22px;"> Choose on of the above to put over your pict !</span>
    <script type="text/javascript" src='/camagru/config/java1.js'></script>
  </div>
  <div id="side" class="videobox">
    <div id="mini" style="margin-top: 50px;">

    </div>
  </div>
</div>
