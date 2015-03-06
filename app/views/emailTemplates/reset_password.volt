<p>&nbsp;</p>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0"
style="font-family:Helvetica, Arial, sans-serif; color:#4c4f53; font-size:16px; line-height: 23px">
    <tr>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="710" bgcolor="#FFFFFF">
            <p><br>
            <b>Dear {{ name }}</b></p>
            <p>Someone (hopefully you) has requested a password reset for your account with Removalist Quote. Follow the link below to set a new password:</p>

            <p>{{ publicUrl }}{{ resetUrl }}</p>

            <p>If you don't wish to reset your password, disregard this email and no action will be taken.</p>
            <p>Kind regards</p>
            <p>Removalist Quote</p>
        </td>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>


{% include "emailTemplates/footer.volt" %}
