<!-- Global Javascript and PHP variables -->
<?php
  $u = new User();
  if ($u && $u->isLoggedIn ()) {
    $ui           = UserInfo::getByID($u->getUserID());
    $page         = Page::getCurrentPage();
    $p            = new Permissions($page);
    $G_CAN_WRITE  = $p->canWrite();
    $G_NAME       = $ui->getAttribute('FirstName') ." ". $ui->getAttribute('LastName');
    $G_USERNAME   = $ui->getUserName();
    $G_PAGE_ID    = $page->getCollectionID();
    $G_LOGGED_IN  = 1; ?>
    <script>
      var G_NAME       = '<?=$G_NAME?>';
      var G_USERNAME   = '<?=$G_USERNAME?>';
      var G_PAGE_ID    = <?=$G_PAGE_ID?>;
      var G_LOGGED_IN  =  <?=$G_LOGGED_IN?>;
      var G_CAN_WRITE  =  <?=$G_CAN_WRITE?>;
    </script> <?php
  }
  else {
    $G_LOGGED_IN  = 0; ?>
    <script>
      var G_LOGGED_IN  =  <?=$G_LOGGED_IN?>;
    </script> <?php
  }
?>


<!-- Html Tooltips -->
<script type="text/javascript" src="<?=DIR_REL?>/single_pages/includes/tooltip/wz_tooltip.js"></script> 



<div id='header_div'>
  <h1>Nyheter</h1>
</div>

<!-- If user is logged in, show a button for adding news -->
<?php
if ($G_LOGGED_IN) {  ?>
  <div id='new_button_div'>
    <input id='new_button' type='button' class='button' value='Lägg till' onclick="new_f();" />
  </div> <?php
} ?>


<!-- Main list -->
<div id='news'>
  <!-- Content added by ajax -->
</div>



<!-- If user is logged in, add form for add/dele news -->
<?php
if ($G_LOGGED_IN == true) {  ?>

  <!-- Grey background when popup is visible -->
  <div id='overlay' class='overlay'></div>
  
  <!-- Details popup -->
  <div id='new_popup' class='popup'>
  
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#new_popup, #overlay').popHide();"></span>
    </div>
  
    <div id='new_form'>
      <ul class='clean_list'>
        <li>
          <p class='popup_h1'>Lägg till nyhet</p>
        </li>
        <li>
          <label class='c1'>Postat av:</label>
          <input class='c2' type='text' id='new_form_name' tabindex='1' value='<?=$G_NAME?>'/>
        </li>
        <li>
          <label class='c1'>Rubrik:</label>
          <input class='c2' type='text' id='new_form_heading' tabindex='2'/>
        </li>
        <li>
          <label    class='c1'>Text:</label>
          <textarea class='c2' id='new_form_text' tabindex='3'></textarea>
        </li>
        <li class='file_li' id='file_0'>
          <label class='c1'>Bilaga:</label>
          <div class='file_div'>
            <input class='new_attachment_val' type='text'   name='new_attachment_val' id=''                 value=''/>
            <input class='button browse'      type='button' name='browse'             id=''    tabindex='4' value='Välj...' onclick="$(this).parent().find('.fileToUpload').trigger('click');" />
            <input class='fileToUpload'       type='file'   name='file_to_upload_0'   id='file_to_upload_0' value=''        autocomplete="off" />
            <span class='icon-remove icon-st-red       icon-st-shadow icon-st-click' onmouseover="Tip(tip_less_files)" onmouseout="UnTip()" onClick="remove_f($(this).closest('li').attr('id'));"></span> 
            <span class='icon-plus   icon-st-lightblue icon-st-shadow icon-st-click' onmouseover="Tip(tip_more_files)" onmouseout="UnTip()" onClick="duplicate_f($(this).closest('li').attr('id'));"></span> 
          </div>
        </li>
        <li>
          <label class='c1'>Publik:</label>
          <div class='c2'>
            <input type='radio' class='' name='new_public' id='new_public_1' tabindex='5' value='1'/>
            <span> Ja</span>
          </div>
        </li>
        <li>
          <label class='c1'>&nbsp;</label>
          <div class='c2'>
            <input type='radio' class='' name='new_public' id='new_public_0' tabindex='6' value='0'/>
            <span> Nej</span>
          </div>
        </li>
      </ul>
    </div>
  
    <div class='popup_submit'>
      <input id='new_submit' type='button' class='button' style='width: 100px;' tabindex='7' value='Lägg till'  onclick="new_submit_f();" />
      <input id='new_abort'  type='button' class='button' style='width: 100px;' tabindex='8' value='Avbryt'     onclick="$('#new_popup, #overlay').popHide();" />
    </div>
  
    <div id='error'>
      <!-- Populated by ajax -->
    </div>
  
    <div class='info'>
      <p>Alla som har en användare på hemsidan kan lägga in nyheter.Innan nyheten börjar visas på hemsidan måste den godkännas av en moderator. Därför kan det dröja några dagar innan din nyhet visas.<br><br>Om du anger nyheten som publik kommer den att visas för alla, annars visas den bara för inloggade medlemmar.</p>
    </div>
  
  </div>



  <!-- Delete popup -->
  <div id='delete_popup' class='popup'>
  
    <div class='close'>
      <span class='icon-remove-sign icon-large icon-st-lightblue icon-st-shadow icon-st-click' onclick="$('#delete_popup, #overlay').popHide();"></span>
    </div>
  
    <div>
      <ul class='clean_list'>
        <li>
          <p class='popup_h2'>Är du säker på att du vill radera:</p>
        </li>
        <li>
          <label class='c1'>Rubrik:</label>
          <span  class='c2' id='delete_heading'></span>
        </li>
        <li>
          <label class='c1'>Postad av:</label>
          <span  class='c2' id='delete_name'></span>
        </li>
        <li>
          <label class='c1'>Skapad:</label>
          <span  class='c2' id='delete_created'></span>
        </li>
      </ul>
      <input type='hidden' id='delete_id'/>
    </div>
  
    <div class='popup_submit'>
      <input id='delete_submit' type='button' class='button' style='width: 100px;' value='Radera'  onclick="delete_f($('#delete_id').val());" />
      <input id='delete_abort'  type='button' class='button' style='width: 100px;' value='Avbryt'  onclick="$('#delete_popup, #overlay').popHide();" />
    </div>
  
  </div> <?php
} ?>
