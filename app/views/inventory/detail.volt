<div style="margin:20px">
<img src="/img/logo.png" width="110" alt="Removalist Quote" style="float:left;">
<h2 style="float:left; margin:10px 0 0 20px;">Inventory List</h2>
<table class="table">
{% for category in categories %}
<thead>
    <tr>
        <th colspan="3">{{ category['name'] }}</th>
    </tr>
</thead>
<tbody>
    {% for item in category['items'] %}
    <tr>
        <td>{{ item.name }}</td>
        <td>{{ item.cubic }} <sup>m3</sup></td>
        <td>{{ item.quantity }}</td>
    </tr>
    {% endfor %} 
</tbody>
{% endfor %}
</table>
</div>