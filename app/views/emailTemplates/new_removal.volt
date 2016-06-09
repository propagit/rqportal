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
                <td colspan="3"><b>Contact Details</b></td>
            </tr>
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
                <td colspan="3"><br /></td>
            </tr>
            <tr>
                <td colspan="3"><b>Contact Details</b></td>
            </tr>
            <tr>
                <td>Pick Up:</td>
                <td width="20"></td>
                <td>
                {% if removal.is_international == 'yes' %}
                	{{ removal.from_country }}
                {% else %}
                	{{ from.state }} {{ removal.from_postcode }} ({{ from.suburb }})
                {% endif %}
                </td>
            </tr>
            <tr>
                <td>Drop Off:</td>
                <td width="20"></td>
                <td>
                {% if removal.is_international == 'yes' %}
                	{{ removal.to_country }}
                {% else %}
               		{{ to.state}} {{ removal.to_postcode }} ({{ to.suburb }})
                {% endif %}
                </td>
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

            <p><b>Inventory List:</b><br />
            {% if total_cubic > 0 %}
            <a href="{{ publicUrl }}inventory/detail/{{ removal.id }}">View full inventory list</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            TOTAL m3: <b style="font-size: 28px;">{{ total_cubic }}</b>
            {% else %}
            No inventory list provided
            {% endif %}
            </p>

            <p><b>Notes:</b><br>
            {{ removal.notes }}
            </p><br>
            <img src="{{ map_url }}" />
        </td>
        <td width="20" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
</table>


{% include "emailTemplates/footer.volt" %}
