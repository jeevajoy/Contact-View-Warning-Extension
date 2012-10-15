<input type="hidden" name="warning" id="warning" value="{$warning}">
<div id="dialog-message">
</div>
{literal}
<script type="text/javascript">
cj(document).ready( function() {
   var content = cj('#warning').val();
   if (content) {  
   cj( '#dialog-message' ).show( ).html( content ).dialog({
    	title: "Warning",
   		modal: true,
   		width: 500, 
   		overlay: { 
   			opacity: 0.5, 
   			background: "black" 
   		},
    	buttons: {
			Ok: function() {
				cj( this ).dialog( "close" );
			}
		}
   });
   }
});
</script>
{/literal}

{if $imageURL }
    <div>
        {include file="CRM/Contact/Page/ContactImage.tpl"}
    </div>
{/if}