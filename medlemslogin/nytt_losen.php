<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="page-header">
	<h1>Nytt lösenord</h1>
  <br>
</div>


<div id='form'>
  
	<p>Skriv nytt lösenord nedan</p>

  <input type="hidden" name="uHash" id="uHash" value="<?php echo $_GET['uhash']?>" />

  <div class='clearboth control-group'>
    <div class='label_div'>
      <label for='uPassword' class='control-label'>Nytt lösenord</label>
    </div>
    <div class="input_div controls">
      <input type='password' name='uPassword' id='uPassword' class='ccm-input-text' />
    </div>
  </div>
  
  <div class='clearboth control-group'>
    <div class='label_div'>
		  <label for='uPasswordConfirm'  class='control-label'>Upprepa lösenord</label>
    </div>
    <div class="input_div controls">
      <input type='password' name='uPasswordConfirm' id='uPasswordConfirm' class='ccm-input-text' />
    </div>
  </div>
  
  <div class='clearboth'>
    <div class='label_div'>
      <label>&nbsp;</label>
    </div>
    <div class='button_div'>
      <input type='submit' class='button' value='Skicka' onclick="submit_f();" />
    </div>
  </div>

</div>


<div id="success">
  <p>Lösenordet är ändrat</p>
  <p><a href='<?=DIR_REL?>/medlemslogin/logga_in/'>Logga in</a></p>
</div>

<div id="error">
  <p id="error_msg"></p>
</div>
