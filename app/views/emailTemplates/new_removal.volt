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
                <td>{{ removal.customer_name }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td width="20"></td>
                <td>{{ removal.customer_phone }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td width="20"></td>
                <td>{{ removal.customer_email }}</td>
            </tr>
            <tr>
                <td>Pick Up:</td>
                <td width="20"></td>
                <td>{{ from.state }} {{ removal.from_postcode }} ({{ from.suburb }})</td>
            </tr>
            <tr>
                <td>Drop Off:</td>
                <td width="20"></td>
                <td>{{ to.state}} {{ removal.to_postcode }} ({{ to.suburb }})</td>
            </tr>
            <tr>
                <td>Date of Job:</td>
                <td width="20"></td>
                <td>{{ removal.moving_date }}</td>
            </tr>
            <tr>
                <td>Rooms:</td>
                <td width="20"></td>
                <td>{{ removal.bedrooms }}</td>
            </tr>
            <tr>
                <td>Packing:</td>
                <td width="20"></td>
                <td>{{ removal.packing }}</td>
            </tr>
            </table>
            </p>

            <p>Notes:<br>
            {{ removal.notes }}
            </p><br>
        </td>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>


{% include "emailTemplates/footer.volt" %}
