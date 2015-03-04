<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php  $form = Loader::helper('form'); ?>

<script type="text/javascript">
$(function() {
	$("input[name=uName]").focus();
});
</script>

<?php  if (isset($intro_msg)) { ?>
<div class="alert-message block-message success"><p><?php echo $intro_msg?></p></div>
<?php  } ?>

<div class="page-header">
	<h1>Glömt lösenordet?</h1>
  <br>
</div>


<div id='form'>

  <p>Ange din emailadress nedan så skickas ett nytt lösenord.</p>

  <input type="hidden" name="rcID" value="<?php echo $rcID?>" />
  
  <div class='clearboth'>
    <div class='label_div'>
      <label class="form" for="uName">Email</label>
    </div>
    <div class='input_div'>
      <input type="text" name="uEmail" id='uEmail' value="" />
    </div>
  </div>
  
  <div class='clearboth'>
    <div class='label_div'>
      <label class="form">&nbsp;</label>
    </div>
    <div class='button_div'>
      <input type='submit' class='button' value='Skicka' onclick="submit_f();" />
    </div>
  </div>

</div>


<div id="success">
  <p>Ett email med instruktioner om hur du ändrar ditt lösenord har skickats til din emailadress.</p>
</div>

<div id="error">
  <p id="error_msg"></p>
</div>
