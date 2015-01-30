<p>&nbsp;</p>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0"
style="font-family:Helvetica, Arial, sans-serif; color:#4c4f53; font-size:16px; line-height: 23px">
    <tr>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="710" bgcolor="#FFFFFF">
            <p><br>
            <b>You have a new removals quote.</b></p>
            <p>The details of the quote are:</p>
            <p>
            <table border="0" cellpadding="0" cellspacing="0"
style="font-family:Helvetica, Arial, sans-serif; color:#4c4f53; font-size:16px; line-height: 23px">
            <tr>
                <td>Contact Name:</td>
                <td width="20"></td>
                <td>{{ storage.customer_name }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td width="20"></td>
                <td>{{ storage.customer_phone }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td width="20"></td>
                <td>{{ storage.customer_email }}</td>
            </tr>
            <tr>
                <td>Pick Up:</td>
                <td width="20"></td>
                <td>{{ pickup.state }} {{ storage.pickup_postcode }} ({{ pickup.suburb }})</td>
            </tr>
            <tr>
                <td>Period:</td>
                <td width="20"></td>
                <td>{{ storage.period }}</td>
            </tr>
            <tr>
                <td>Containers:</td>
                <td width="20"></td>
                <td>{{ storage.containers }}</td>
            </tr>
            </table>
            </p>

            <p>Notes:<br>
            {{ storage.notes }}
            </p><br>
        </td>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>


{% include "emailTemplates/footer.volt" %}
