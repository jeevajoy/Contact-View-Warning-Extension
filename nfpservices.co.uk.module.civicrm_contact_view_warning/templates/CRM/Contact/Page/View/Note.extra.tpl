

{literal}
<script type="text/javascript">
cj(document).ready( function() {  
  cj('#note').parent().parent().parent().parent().after('<tr><td class="label">{/literal}{$form.is_warning.label}{literal}</td><td>{/literal}{$form.is_warning.html}{literal} <span class="description">Is this message to be displayed as a warning when the contact is viewed? PLEASE NOTE you can set only one note as a warning for a contact. If you have already set a warning note for this contact and setting here again, this will override the previous warning and use this note as the warning message.</span></td></tr>');
  
});
</script>
{/literal}
