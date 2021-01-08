
<h3><?php echo t("Email Logs")?></h3>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="emailLogs">
<input type="hidden" name="tbl" id="tbl" value="email_logs">
<input type="hidden" name="server_side" id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th><?php echo t("ID")?></th>
            <th><?php echo t("To")?></th>
            <th><?php echo t("Sender")?></th>
            <th><?php echo t("Subject")?></th>
            <th><?php echo t("Content")?></th>
            <th><?php echo t("Provider")?></th>
            <th><?php echo t("Status")?></th>
            <th><?php echo t("Date")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>