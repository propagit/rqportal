<p>&nbsp;</p>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0"
style="font-family:Helvetica, Arial, sans-serif; color:#4c4f53; font-size:16px; line-height: 23px">
  <tr>
    <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="710" bgcolor="#FFFFFF"><p><br>
      <b>A new member has signed up!</b></p>
      <p>The details of the new applicant are:</p>
      <p>
        <table border="0" cellpadding="0" cellspacing="0"
style="font-family:Helvetica, Arial, sans-serif; color:#4c4f53; font-size:16px; line-height: 23px">
            <tr>
                <td>Contact Name:</td>
                <td width="20"></td>
                <td>{{ name }}</td>
            </tr>
            <tr>
                <td>Business Name:</td>
                <td width="20"></td>
                <td>{{ business }}</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td width="20"></td>
                <td>{{ address }}</td>
            </tr>
            <tr>
                <td>Suburb:</td>
                <td width="20"></td>
                <td>{{ suburb }}</td>
            </tr>
            <tr>
                <td>State:</td>
                <td width="20"></td>
                <td>{{ state }}</td>
            </tr>
            <tr>
                <td>Postcode:</td>
                <td width="20"></td>
                <td>{{ postcode }}</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td width="20"></td>
                <td>{{ phone }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td width="20"></td>
                <td>{{ email }}</td>
            </tr>
            <tr>
                <td>Website:</td>
                <td width="20"></td>
                <td>{{ website }}</td>
            </tr>
        </table>
      </p>
      <p>
        About My Business:<br />
        {{ about }}
      </p>
      </td>
    <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>


{% include "emailTemplates/footer.volt" %}
