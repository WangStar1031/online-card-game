<link rel="stylesheet" type="text/css" href="assets/card_game.css?<?= time(); ?>">
<script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
<script src="assets/jquery.min.js"></script>
<script type="text/javascript">
  var arrCards = [];
  var IsBegin = false;
  var arrSelUsers = [];
  var gmTime;
  var timeSpend = 0;
  var clickCount = 0;
  var remainCount = 18;
  var onlineGameId = 0;
  var onlineGameEnd = false;
  var isMyturn = false;
  var onlineMasterName = "";
  var onlineClientName = "";
  var onlineMyMatchCount = 0; 
  var onlineOtherMatchCount = 0;
  var onlineOtherName = "";
  var topicName;
  var editTopicName;
  var userName = "";
  function setCardImgs(arrCardContents){
    console.log(arrCardContents);
    document.getElementById("animationTag").style.visibility = "hidden";
    clearInterval(gmTime);
    remainCount = 18;
    // document.getElementById("timeSpend").innerHTML = 0;
    // document.getElementById("clickCount").innerHTML = 0;
    IsBegin = false;
    var currentDate = new Date();
    for( i = 0; i < arrCardContents.length; i++){
      var cardId = "cardContainer" + i;
      document.getElementById(cardId).classList.remove("hover");
      document.getElementById(cardId).style.visibility = "visible";
      var imgId = "img" + i;
      document.getElementById(imgId).src = "./images/" + topicName + "/" + topicName + "-" + arrCardContents[i] + ".png?" + currentDate.getMinutes() + currentDate.getSeconds();
    }

  }
  function dealCards(){
    onlineGameId = 0;
    onlineMyMatchCount = 0;
    onlineOtherMatchCount = 0;
    document.getElementById("animationTag").innerHTML = "";
    document.getElementById("animationTag").style.visibility = "hidden";
    topicName = $(".topics.selected").html();
    jQuery.ajax({
      type: 'POST',
      url: 'card_deal.php',
      dataType: 'json',
      data: {topic: topicName},
      success: function(obj, textstatus){
        arrCards = obj;
        setCardImgs(arrCards);
      }
    });
  }
  
</script>
<?php
  function UserVerify( $name, $pass){
     $DBservername = 'localhost';
     $DBusername = 'earthso6_test';
     $DBpassword = 'Hotdog12345)))))';
     $DBname = 'earthso6_cardgame_pair';
     /*$DBusername = 'id2248842_cardgame';
     $DBpassword = '1qaz2wsx3edc';
     $DBname = 'id2248842_cardgame';*/
    $conn = new mysqli($DBservername, $DBusername, $DBpassword, $DBname);
    if( $conn->connect_error){
      echo("Connection failed: " . $conn->connect_error);
      return false;
    }
    $sql = "SELECT userName FROM users WHERE userName='".$name."' AND password ='" . $pass . "';";
    $result = $conn->query($sql);
    if( $result->num_rows > 0){
      $row = $result->fetch_assoc();
      $userName = $row["userName"];
    }
    else{
      echo "<h4 style='color:red;'>Invalid User name or Password!</h4>";
      return '';
    }
    $conn->close();
    return $userName;
  }
  if(isset($_POST['userName']) && !isset($userName)){
      $userName = $_POST['userName'];
      $password = $_POST['PassWord'];
      if( $userName != ''){
        if( !($userName = UserVerify($userName, $password))){
          $userName = '';
          $password = '';
        }
      }
  }
  if (isset($userName) && $userName != '') {
?>
<body>
  
</body>
<div class="centerAlign" style="display: none;"> 
  <div style="position: absolute;top: 50px; left: 90px;">
    <div>
      <p class="stepContent" style="float: left;">Step1</p>
      <div class="userInfo" style="border:2px solid black;float: left;">
        <h2>Enter your name</h2>
        <input type="text" name="myName" id="myName">
      </div>
      <div style="clear: both;"></div>
    </div>
    <div style="margin-top: 10px;padding: 0px;">
      <p class="stepContent">Step2<span class="joinContent">press to JOIN</span></p>
    </div>
      <!-- <h2>Welcome </h2>
    <h2 style="color: red;"><?= $userName ?></h2> -->
  </div>
  <div id="gameTitle">
    <img src="./assets/img/esl memory game.png">
  </div>
  <div class="GameBoard">
    <div style="float: left;">
      <h3>Current Users</h3>
      <select id="curUsers" name="users" multiple>
      </select>
      <div id="cmdSendInvite"></div>
      <div id="cmdRetry" onclick="dealCards()"></div>
      <?php
        if($userName == "admin"){
          ?>
          <div id="cmdImgEditing" onclick="editCards()"></div>
          <?php
        }
      ?>
    </div>
    <div style="float: left;">
      <p id="animationTag"></p>
      <table id="cardTable">
        <?php
          for( $row = 0; $row < 3; $row++){
            echo "<tr>";
            for($col = 0; $col < 6; $col++){
              echo "<td>";
              ?>
                <div id="cardContainer<?= $row * 6 + $col ?>" cardid="<?= $row * 6 + $col ?>" class="flip-container">
                  <div class="flipper">
                    <div class="front"><img src="./assets/img/card_back.png?<?= time(); ?>" width="100%" height="100%"></div>
                    <div class="back">
                      <img id="img<?= $row*6+$col ?>" src="./images/images-01.png" width="100%" height="100%">
                    </div>
                  </div>
                </div>
              <?php
              echo "</td>";
            }
            echo "</tr>";
          }
        ?>
      </table>
      <p id="yourTurnTag" class="yourturnHidden">It's your turn.</p>
    </div>
    <div class="FullScore" style=" float: left;">
      <div>
        <h1>Topic</h1>
        <div id="TopicPan">
          <div id="topicContainer">
            <?php
              $dir = './images/';
              $files = scandir($dir);
              $activeNumber = 0;
              for( $i = 0; $i < count($files); $i++){
                $dirName = $files[$i];
                if( strpos($dirName, '.') === false){
                  if($activeNumber == 0){
                    $activeNumber++;
                    echo "<p class='topics selected'>". $dirName . "</p>";
                  }else{
                    echo "<p class='topics'>". $dirName . "</p>";
                  }
                }
              }
            ?>
          </div>
        </div>
      </div>
      <div id="ScorePan">
        <p style="padding-top: 10px;">Score</p>
      </div>
      <br>
      <div class="onlineGameScorePan">
        <table>
          <tr>
            <td id="masterName"></td>
            <td>:</td>
            <td id="masterScore"></td>
          </tr>
        </table>
      </div>
      <br>
      <div class="onlineGameScorePan">
        <table>
          <tr>
            <td id="clientName"></td>
            <td>:</td>
            <td id="clientScore"></td>
          </tr>
        </table>
      </div>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
<script type="text/javascript">dealCards();</script>
<?php   //Login
  }
  else{
    ?>
    <h1>Log In</h1>
    <form action="" method="post">
      <div class="UserLogin" style="top:100px;position: relative;">
        <table style="font-size: 100%;">
          <tr>
            <td>UserName:</td>
            <td><input type="text" name="userName" placeholder="Enter UserName" value=""></td>
          </tr>
          <tr>
            <td>PassWord:</td>
            <td><input type="password" name="PassWord" placeholder="Enter PassWord" value=""></td>
          </tr>
          <tr>
            <td></td>
            <td ><input type="submit" name="submit" value="LogIn"></td>
          </tr>
        </table>
        
      </div>
    </form>
    <?php
  }
  if (isset($userName) && $userName != '') {
?>

<div class="ImageEditing">
<!--   <div id="closeImageEdit" onclick="closeEdit()">CLOSE</div> -->
  <div>
    <div style="cursor: pointer;text-shadow: 2px 2px 2px blue;" class="checkAll" onclick="checkAll()">
      <img class="checkImg0" style="visibility: hidden;" id="checkImg0" src="./assets/img/check.png?<?= time(); ?>">Check All
    </div>
  </div>
  <div class="imageGallery">
    <?php
      $dir = './images/';
      $files1 = scandir($dir);
      $topicsName = $files1[2];
      $dir .= $topicsName."/";
      $files1 = scandir($dir);
      $cardCount = count($files1) - 2;
    ?>
    <script type="text/javascript">
      editTopicName = "<?= $topicsName ?>";
      console.log(editTopicName);
      function arrangeCards(cardCount){
        var currentDate = new Date();
        var strHtml = "";
        for( i = 1; i <= cardCount; i++ ){
          var strFix = (i < 10) ? '0' + i.toString() : i.toString();
          var pathName = "./images/"+editTopicName+"/"+editTopicName +"-" + strFix + ".png";
          var strDiv = '<div class="imgGalleryTags" onclick="ImgClicked(' + i + ')">';
          strDiv += '<img class="imgTags" id="imgTags' + i + '" src = "' + pathName + '?' + currentDate.getMinutes() + currentDate.getSeconds() + '">';
          strDiv += '<img class="checkImg" style="visibility:hidden;" id="checkImg' + i + '" src="./assets/img/check.png?<?= time(); ?>">';
          strDiv += '</div>';
          strHtml += strDiv;
        }
        document.getElementsByClassName("imageGallery")[0].innerHTML = strHtml;
      }
      var cardCount = parseInt("<?= $cardCount ?>");
      arrangeCards(cardCount);
    </script>
  </div>
  <div class="cardOperation">
    <div>
      <h1>Topic</h1>
      <div id="TopicPan">
        <div id="editTopicContainer">
          <?php
            $dir = './images/';
            $files = scandir($dir);
            $activeNumber = 0;
            for( $i = 0; $i < count($files); $i++){
              $dirName = $files[$i];
              if( strpos($dirName, '.') === false){
                if($activeNumber == 0){
                  $activeNumber++;
                  echo "<p class='editTopics selected'>". $dirName . "</p>";
                }else{
                  echo "<p class='editTopics'>". $dirName . "</p>";
                }
              }
            }
          ?>
        </div>
        <p class="addTopic topicControl">+</p>
        <p class="delTopic topicControl">-</p>
      </div>
    </div>

    <button onclick="deleteImages()">Delete</button><br>
    <iframe src="" style="display: none;" id="iframeTag" name="iframeTag"></iframe>
    <form id="uploadImageForm" target="iframeTag" action="card_editing.php" enctype="multipart/form-data" method="post">
      <div id="uploadFilePicker">
          <input type="hidden" id="editFormTopic" name="editFormTopic" value=""></input>
          <label for='upload'>Add ImageFiles:</label><br>
          <input id='upload' name="upload[]" accept="image/png" type="file" multiple="multiple" />
      </div>
      <p><input type="submit" name="submit" value="Upload" onclick="refreshImages()"></p>
    </form>
  </div>
  <div style="clear: both;"></div>
</div>

<script type="text/javascript">
  $("#editFormTopic").attr("value", editTopicName);
function gameTimer(){
  if( onlineGameId != 0) return;
  timeSpend ++;
//  document.getElementById("timeSpend").innerHTML = timeSpend;
}
var dump_Buff;
var socket;
var arrUsers_total;
$(function () {
  arrUsers_total = [];
  socket = io.connect( 'http://stctravel.herokuapp.com:80' );
  function __insert_user(__user){
    var __d = new Date();
    var __n = __d.getTime();
    __user_index = -1;
     for(i=0; i<arrUsers_total.length; i++){
      if(arrUsers_total[i].name == __user){
        __user_index = i;
        arrUsers_total[i].live_time = __n;
        break;
      }
     }
     if(__user_index == -1){
      __new_user = {};
      __new_user.name = __user;
      __new_user.live_time = __n;
      arrUsers_total[arrUsers_total.length] = __new_user;
     }
  }
  socket.on('sentence message', function(msg){
    msgObj = JSON.parse(msg);
    if(msgObj.type == "init"){
        __insert_user(msgObj.name);
        __refresh_users();
    }
    if(msgObj.type == "invite_deny") {
      if(msgObj.otherName == '<?=$userName?>'){
        onlineGameId = 0;
        onlineOtherName = "";
      }
    }
    if(msgObj.type == "invite_accept") {
      if(msgObj.otherName == '<?=$userName?>'){
        isMyturn = true;
        $("#masterName").html('<?=$userName?>');
        $("#clientName").html(msgObj.name);
      }
    }
    if(msgObj.type == "invite_send"){
      if(msgObj.otherName == '<?=$userName?>'){
        if(confirm('Someone invited you online game. Are you sure accept?')){
          onlineGameId = msgObj.gameId;
          onlineOtherName = msgObj.name;
          strBuff = msgObj.gameCards;
          arrCards = strBuff.split(",");          
          topicName = msgObj.topic;
          var topics = document.getElementsByClassName("topics");
          for( i = 0; i < topics.length; i++){
            topics[i].className = "topics";
            if(topics[i].innerHTML == topicName){
              topics[i].className += " selected";
            }
          }
          setCardImgs(arrCards);
          onlineGameEnd = false;
          isMyturn = false;
          onlineMyMatchCount = 0;
          onlineOtherMatchCount = 0;
          $("#masterName").html('<?=$userName?>');
          $("#clientName").html(msgObj.name);

          __user_obj = {"type": "invite_accept", "name": "<?=$userName?>", "otherName": msgObj.name};
          socket.emit('sentence message', JSON.stringify(__user_obj));
        } else {
          onlineGameId = 0;
          onlineOtherName = "";
          __user_obj = {"type": "invite_deny", "name": "<?=$userName?>", "otherName": msgObj.name};
          socket.emit('sentence message', JSON.stringify(__user_obj));
        }
      }
    }
    if(msgObj.type == "card_click") {
      if(msgObj.otherName == '<?=$userName?>'){
        var idCard = msgObj.cardId;
        var elem = document.getElementsByClassName("flip-container");
        setTimeout( function(){
          cardClickEvent(elem[idCard]);
        }, 150);
      }
    }
    if(msgObj.type == "change_owber") {
      if(msgObj.otherName == '<?=$userName?>'){
        setTimeout(function(){
          isMyturn = true;
          $("#yourTurnTag").removeClass("yourturnHidden");
          $("#yourTurnTag").addClass("yourturnShow");
          setTimeout(function(){
            $("#yourTurnTag").removeClass("yourturnShow");
            $("#yourTurnTag").addClass("yourturnHidden");
          }, 1000);
        }, 1000);
      }
    }
  });
  function __refresh_users(){
      var __d = new Date();
      var __n = __d.getTime();
      arrUsers = [];
      for(i=0; i<arrUsers_total.length; i++){
        if((__n - arrUsers_total[i].live_time < 2000) && (arrUsers_total[i].name != "<?=$userName?>"))
          arrUsers[arrUsers.length] = arrUsers_total[i].name;
      }
      var el = document.getElementById("curUsers");
      arrSelUsers = [];
      var options = el && el.options;
      var opt;
      for( var i = 0, iLen = options.length; i<iLen; i++){
        opt = options[i];
        if( opt.selected){
          arrSelUsers.push( opt.text);
          break;
        }
      }
      var strHtml = "";
      for( i = 0; i < arrUsers.length; i++){
        if( arrSelUsers.indexOf(arrUsers[i]) != -1){
          strHtml += '<option value="' + arrUsers[i] + '" selected="selected">' + arrUsers[i] + '</option>';
        }else{
          strHtml += '<option value="' + arrUsers[i] + '">' + arrUsers[i] + '</option>';
        }
      }
      el.innerHTML = strHtml;
  }

  setInterval(function(){
    __user_obj = {"type": "init", "name": "<?=$userName?>"};
    socket.emit('sentence message', JSON.stringify(__user_obj));
  }, 1000);

  function dealCards_2(){
    console.log("dealCards_2");
    onlineMyMatchCount = 0;
    onlineOtherMatchCount = 0;
    onlineGameId = 0;
    document.getElementById("animationTag").innerHTML = "";
    document.getElementById("animationTag").style.visibility = "hidden";
    jQuery.ajax({
      type: 'POST',
      url: 'card_deal.php',
      dataType: 'json',
      data: {topic: topicName},
      success: function(obj, textstatus){
        console.log("dealCards");
        arrCards = obj;
        setCardImgs(arrCards);
        if( onlineGameId != 0){
          alert("already sent invitation.");
          return;
        }
        var el = document.getElementById("curUsers");
        arrSelUsers = [];
        var options = el && el.options;
        var opt;
        for( var i = 0, iLen = options.length; i<iLen; i++){
          opt = options[i];
          if( opt.selected){
            arrSelUsers.push( opt.text);
          }
        }
        if( arrSelUsers.length == 0){
          return;
        }
        if ( arrSelUsers.length > 1) {
          alert("Select one user!");
          return;
        }
        onlineGameId = "123";
        onlineOtherName = arrSelUsers[0];
        __user_obj = {"type": "invite_send", "name": "<?=$userName?>", "otherName": onlineOtherName,"topic":topicName, "gameCards": arrCards.join(","), gameId: onlineGameId};
        socket.emit('sentence message', JSON.stringify(__user_obj));
        console.log(__user_obj);
      }
    });
  }

  $("#cmdSendInvite").click(function(){
    dealCards_2();
  });
});

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
$(".addTopic").on("click", function(){
  var topicTitle = window.prompt("Please type the Topic title!","");
  if( topicTitle){
    var curTitles = document.getElementsByClassName("editTopics");
    for(i = 0; i < curTitles.length; i++){
      if (curTitles[i].innerHTML == topicTitle) {
        alert("Exist Topic!");
        return;
      }
    }
    jQuery.ajax({
      type: 'POST',
      url: 'card_editing.php',
      data: {addTopic: topicTitle},
      success: function(obj, textstatus){
        var strHtml = "<p class='editTopics'>" + obj + "</p>";
        console.log(strHtml);
        $("#editTopicContainer").append(strHtml);
        editTopicsFunction();
      }
    });
  } else{
  }
});
$(".delTopic").on("click", function(){
  var result = confirm("Are you sure delete current Topic?");
  if(result){
    var topics = document.getElementsByClassName("editTopics");
    var selectedTopic;
    for( i = 0; i < topics.length; i++){
      var topicClass = topics[i].className;
      if(topicClass.indexOf("selected") != -1){
        selectedTopic =topics[i].innerHTML;
      }
    }

    jQuery.ajax({
      type: 'POST',
      url: 'card_editing.php',
      data: {delTopic: selectedTopic},
      success: function(obj, textstatus){
        $(".editTopics:contains('"+obj+"')").remove();
      }
    });
  } else{

  }
});
$(".topics").on("click", function(){
  $(".topics").removeClass("selected");
  $(this).addClass("selected");
  dealCards();
});
function editTopicsFunction(){
  $(".editTopics").on("click", function(){
    $(".editTopics").removeClass("selected");
    $(this).addClass("selected");
    editTopicName = $(this).html();
    $("#editFormTopic").attr("value",editTopicName);
    jQuery.ajax({
      type: 'POST',
      url: 'card_editing.php',
      data: {topicChange: editTopicName},
      success: function(obj, textstatus){
        cardCount = parseInt(obj)
        arrangeCards( cardCount);
      }
    });
  });
}
editTopicsFunction();
var arrSelCards = [];
$(document).on("click", ".flip-container", function () {
  if( !isMyturn && onlineGameId != 0)
    return;
  cardClickEvent(this);
});
function cardClickEvent(clickedElem){
  if(IsBegin == false){
    IsBegin = true;
    clearInterval(gmTime);
    gmTime = setInterval(gameTimer, 1000);
    timeSpend = 0;
    clickCount = 0;
  }
  clickCount ++;
  if( onlineGameId != 0){   //if online Game, update db
    if( isMyturn){
      __user_obj = {"type": "card_click", "name": "<?=$userName?>", "otherName": onlineOtherName, "cardId": clickedElem.getAttribute("cardid"), gameId: "123"};
      socket.emit('sentence message', JSON.stringify(__user_obj));
    }
  }
  if( onlineGameId == 0){
  }
  $(clickedElem).toggleClass('hover');
  arrSelCards.push(clickedElem.getAttribute("cardid"));
  if( arrSelCards.length >= 2){
    setTimeout( function(){
      checkCards();
    }, 200);
  }
}
function checkCards(){
  var Id1 = arrSelCards[0];
  var Id2 = arrSelCards[1];
  arrSelCards = [];
  if( Id1 == Id2){
    return;
  }
  var firstId = parseInt(Id1);
  var secondId = parseInt(Id2);
  if( arrCards[firstId] == arrCards[secondId]){
    if( isMyturn){
      onlineMyMatchCount ++;
    } else{
      onlineOtherMatchCount ++
    }
    document.getElementById("masterScore").innerHTML = onlineMyMatchCount;
    document.getElementById("clientScore").innerHTML = onlineOtherMatchCount;
    document.getElementById("cardContainer"+firstId).style.visibility = "hidden";
    document.getElementById("cardContainer"+secondId).style.visibility = "hidden";
    remainCount -= 2;
    if( remainCount == 0){    // Game Ended
      if( onlineGameId ){ //onlineGame Ended
        onlineGameEnd = true;
        if( onlineMyMatchCount > onlineOtherMatchCount ){
          document.getElementById("animationTag").innerHTML = "You Win!";
        } else{
          document.getElementById("animationTag").innerHTML = "You lose";
        }
        document.getElementById("animationTag").style.visibility = "visible";
        onlineGameId = 0;
      }else{
        document.getElementById("animationTag").innerHTML = "THE END";
        document.getElementById("animationTag").style.visibility = "visible";
      }
      clearInterval(gmTime);
    } 
  } else{
    setTimeout(function(){
      for( i = 0; i < 18; i++){
        document.getElementById("cardContainer"+i).classList.remove("hover");
      }
    }, 500);
    if( onlineGameId != 0){
      if( isMyturn){
        console.log("No matched!");
        __user_obj = {"type": "change_owber", "name": "<?=$userName?>", "otherName": onlineOtherName, gameId: "123"};
        socket.emit('sentence message', JSON.stringify(__user_obj));
        isMyturn = false;
      } else{

      }
    }
  }
} 
function editCards(){
  document.getElementsByClassName("centerAlign")[0].style.display ="none";
  document.getElementsByClassName("ImageEditing")[0].style.display = "block";
}
function closeEdit(){
  document.getElementsByClassName("centerAlign")[0].style.display ="block";
  document.getElementsByClassName("ImageEditing")[0].style.display = "none";
}
function ImgClicked( i ){
  var ele = document.getElementById('checkImg' + i);
  if( ele.style.visibility == 'visible'){
    ele.style.visibility = 'hidden';
  } else{
    ele.style.visibility = 'visible';
  }
}
function checkAll( ){
  var ele = document.getElementById('checkImg0');
  if( ele.style.visibility == 'visible'){
    ele.style.visibility = 'hidden';
  }else{
    ele.style.visibility = 'visible';
  }
  var vis = ele.style.visibility;
  var elems = document.getElementsByClassName("checkImg");
  for( i = 0; i < elems.length; i++){
    elems[i].style.visibility = vis;
  }
}
function deleteImages(){
  var elems = document.getElementsByClassName("checkImg");
  var arrSelElems = [];
  for( i = 0; i < elems.length; i ++){
    if( elems[i].style.visibility == "visible"){
      var strId = elems[i].id;
      var strIndex = strId.substring(8);
      arrSelElems.push(strIndex);
    }
  }
  if( arrSelElems.length == 0){
    alert("No images selected!");
    return;
  }
  var r = confirm('Are you sure DELETE selected images?');
  if( r == true){   // confirm Delete Images
    var strImageIds = arrSelElems.join(",");
    jQuery.ajax({
      type: 'POST',
      url: 'card_editing.php',
      data: {deleteImages: strImageIds, topicName:editTopicName},
      success: function(obj, textstatus){
        console.log(obj);
        cardCount = parseInt(obj)
        arrangeCards( cardCount);
      }
    });
  }
}
function refreshImages(){
  setTimeout( function(){
    jQuery.ajax({
      type: 'POST',
      url: 'card_editing.php',
      data: {getImgFileCount: 'getImgFileCount', topicName: editTopicName},
      success: function(obj, textstatus){
        //console.log(obj);
        cardCount = parseInt(obj)
        arrangeCards( cardCount);
        var el = $("#upload");
        el.wrap('<form>').closest('form').get(0).reset();
        el.unwrap();
      }
    });
  }, 1000);
}
</script>
<?php
}
?>