
<h1>Matrikel</h1>

<!-- Member list -->
<div id='member_list'>
<span id='nr_hits'></span>
<table id='table' class='table_list nowrap tablesorter'>
  <thead>
    <tr>
      <th>Mer         </th>
      <th>SFF Nr      </th>
      <th>Förnamn     </th>
      <th>Efternamn   </th>
      <th>Email       </th>
    </tr>
  </thead>
  <tbody>
    <!-- Content added by ajax -->
  </tbody>
</table>
</div>

<!-- Filter list -->
<div id='filter'>
  <fieldset id='filter_field'>
    <legend>Filtrera:</legend>
    <ul class='clean_list'>
      <li>
        <div class='c1'>
          <span>Visa år:</span>
        </div>
        <div class='c2'>
          <select name='filter_year' id='filter_year' onchange="ajax_f('get_all', '&year='+$('#filter_year').val());">
            <!-- Content added by Ajax -->
          </select>
        </div>
      </li>
      <li>
        <div class='c1'>
          <span>Sök:</span>
        </div>
        <div class='c2'>
          <input type='text' name='filter_inp' id='filter_inp' value=''/>
        </div>
      </li>
    </ul>
  </fieldset>
</div>
