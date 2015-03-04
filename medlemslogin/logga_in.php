<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="page-header">
	<h1>Logga in</h1>
  <br />
</div>

<div id='form'>
  <form method='POST' action="<?php echo(DIR_REL . '/single_pages/medlemslogin/logga_in_blank.html') ?>" target="loginTarget">

    <div class='clearboth'>
      <div class='label_div'>
        <label for="uName">Användarnamn</label>
      </div>
      <div class='input_div'>
        <input type="text" name="uName" id="uName" />
      </div>
    </div>

    <div class='clearboth'>
      <div class='label_div'>
        <label for="uPassword">Lösenord</label>
      </div>
      <div class='input_div'>
        <input type="password" name="uPassword" id="uPassword" />
      </div>
    </div>

    <div class='clearboth'>
      <div class='label_div'>
        <label>&nbsp;</label>
      </div>
      <div class='button_div'>
        <input type='hidden' name='rdURL' id='rdURL' value='<?php echo $_GET['url'] ?>' /> 
        <input type='submit' class='button' value='Logga in' onclick="submit_f(); return false;" />
      </div>
    </div>

  </form>
</div>


<div id="error">
  <p id="error_msg"></p>
</div>


<!-- Trick to trigger browser to prompt for save password -->
<iframe src="<?php echo(DIR_REL . '/single_pages/medlemslogin/logga_in_blank.html') ?>" id="loginTarget" name="loginTarget" style="display:none">
</iframe>

