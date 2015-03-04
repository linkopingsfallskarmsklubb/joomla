<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="page-header">
	<h1>Skapa konto</h1>
  <br>
</div>

<?php if ($_GET['success'] == 'lfk_ok') { ?>
<div id="lfk_complete" style="float: left;">
  <p>Du är nu registrerad och inloggad.</p>
</div>
<?php } else if ($_GET['success'] == 'non_lfk_ok') { ?>
<div id="non_lfk_complete" style="float: left;">
  <p>Din förfrågan har nu skickats till en administrator för godkännande.</p>
</div>
<?php } else { ?>



<div id='form'>

  <div style='clear: both;'>
    <div class='rb_label_div'>
      <label for='rb_lfk'>Jag har löst licens i LFK</label>
    </div>
    <div class='rb_input_div'>
      <input type="checkbox" name='cb_lfk' id='cb_lfk' value='lfk' />
    </div>
  </div>
  
  <div style='clear: both;'>
    <div class='rb_label_div'>
      <label for='rb_pilot'>Jag är pilot i LFK</label>
    </div>
    <div class='rb_input_div'>
      <input type="checkbox" name='cb_pilot' id='cb_pilot' value='pilot'/>
    </div>
  </div>

  <div style='clear: both;'>
    <div class='rb_label_div'>
      <label for='rb_none'>Inget av ovanstående</label>
    </div>
    <div class='rb_input_div'>
      <input type="checkbox" name='cb_none' id='cb_none' value='none'/>
    </div>
  </div>


  <br>
  <hr>


  <div id="main_form">
    <div class='clearboth'>
      <div class='label_div'>
        <label for='uLicenseNumber'>Licensnummer</label>
      </div>
      <div class='input_div_small'>
        <input type='text' id='uLicenseNumber' />
        <p id='opt_licensnummer'>...om du har något</p>
      </div>
    </div>

    <div class='clearboth'>
      <div class='label_div'>
        <label for='uPID'>Personnummer</label>
      </div>
      <div class='input_div_small'>
        <input type='text' id='uPID' />
        <p>ååååmmdd-nnnn</p>
      </div>
    </div>
    

    <div class='clearboth' id='fname_div'>
      <div class='label_div'>
        <label for="uFirstName">Förnamn</label>
      </div>
      <div class='input_div'>
        <input type='text' id='uFirstName' />
      </div>
    </div>

    <div class='clearboth' id='lname_div'>
      <div class='label_div'>
        <label for="uLastName">Efternamn</label>
      </div>
      <div class='input_div'>
        <input type='text' id='uLastName' />
      </div>
    </div>

    <div class='clearboth'>
      <div class='label_div'>
        <label for="uName">Användarnamn</label>
      </div>
      <div class='input_div'>
        <input type='text' id='uName' />
      </div>
    </div>

    <div class='clearboth'>
      <div class='label_div'>
        <label for="uEmail">Email</label>
      </div>
      <div class='input_div'>
        <input type='text' id='uEmail' value='' />
      </div>
    </div>
        
    <div class='clearboth'>
      <div class='label_div'>
        <label for="uPassword">Lösenord</label>
      </div>
      <div class='input_div'>
        <input type='password' id='uPassword' value='' />
      </div>
    </div>
        
    <div class='clearboth'>
      <div class='label_div'>
        <label for="uPasswordConfirm">Lösenord igen</label>
      </div>
      <div class='input_div'>
        <input type='password' id='uPasswordConfirm' value='' />
      </div>
    </div>

    <div class='clearboth' id='comment_div'>
      <div class='label_div'>
        <label for="uComment">Kommentar</label>
      </div>
      <div class='input_div'>
        <textarea rows='5' id='uComment' ></textarea>
      </div>
    </div>

    <div class="actions clearboth">
      <div class='label_div'>
        <label>&nbsp;</label>
      </div>
      <div class='button_div'>
        <input type='submit' class='button' value='Registrera' onclick="submit_f();" />
      </div>
    </div>

  </div> <!-- lfk -->

</div> <!-- form -->



<div id="info">
  <p>Om du inte har löst licens i LFK, eller om <br> 
     du inte är hoppare kan du ändå ansöka om <br>
     ett konto. <br>
     <br>
     Ansökan måste godkännas av en administrator. <br>
     När detta är klart kommer du få en bekräftelse <br>
     via email.
  </p>
</div>

<div id="error">
  <p id="error_msg"></p>
</div>



<?php } ?>
