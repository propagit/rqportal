<html>
    <body>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr valign="top">
                <td width="50%">
                    <img src="{{ baseUrl }}img/logo.png" width="150" alt="Removalist Quote">
                    <address>
                        <br>
                        <strong>Removalist Quote Pty. LTD</strong>
                        <br>
                        ABN: 42 155 562 959<br>
                        <br>
                        Head Office<br>
                        P.O. Box 1172<br>
                        Bentleigh East - VIC 3165<br>
                        Tel: 1300 531 475
                    </address>
                </td>
                <td align="right" valign="top">
                    <h1 class="font-400">tax invoice</h1>
                    {% if invoice['status'] == constant("Invoice::PAID") %}
                    <br><br>
                    <img src="{{ baseUrl }}img/badge-paid.png" />
                    {% endif %}
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <h4 class="semi-bold">{{ invoice['supplier']['business'] }}</h4>
                    <address>
                        <strong>{{ invoice['supplier']['name'] }}</strong>
                        <br>
                        {{ invoice['supplier']['address'] }}
                        <br>
                        {{ invoice['supplier']['suburb'] }} - {{ invoice['supplier']['state'] }} {{ invoice['supplier']['postcode'] }}
                        <br>
                        Tel: {{ invoice['supplier']['phone'] }}
                    </address>
                </td>
                <td align="right">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td><strong>INVOICE NO:</strong></td>
                            <td width="20"></td>
                            <td align="right">#{{ invoice['id'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>INVOICE DATE:</strong></td>
                            <td></td>
                            <td align="right">{{ date('d M Y', invoice['created_on']/1000) }}</td>
                        </tr>
                        <tr>
                            <td><strong>DUE DATE:</strong></td>
                            <td></td>
                            <td align="right">{{ date('d M Y', invoice['due_date']/1000) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:10px;"></td>
                        </tr>
                        <tr style="background:#555;">
                            <td style="color:#fff;padding:8px;">Total Due:</td>
                            <td></td>
                            <td align="right" style="color:#fff;padding:8px;">{{ invoice['amount'] | money_format }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" cellspacing="0" cellpadding="5">
            <thead>
                <tr style="background:#eee;">
                    <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">QTY</th>
                    <th width="30" style="padding-top:10px;border-bottom:2px solid #ccc;"></th>
                    <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">ITEM COST</th>
                    <th width="30" style="padding-top:10px;border-bottom:2px solid #ccc;"></th>
                    <th width="50%" align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">DESCRIPTION</th>
                    <th width="30" style="padding-top:10px;border-bottom:2px solid #ccc;"></th>
                    <th align="right" style="padding-top:10px;border-bottom:2px solid #ccc;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {% if invoice['price_per_quote'] > 0 %}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #ccc;"><strong>{{ invoice['removals']|length  + invoice['storages']|length - invoice['free'] }}</strong></td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">{{ invoice['price_per_quote'] | money_format }}</td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">Quotes Received<br><small>Full breakdown of received quotes below</small></td>

                    <td align="right" style="border-bottom:1px solid #ccc;"><strong>{{ invoice['amount'] | money_format }}</strong></td>
                </tr>
                {% endif %}

                {% if invoice['lines']|length > 0 %}
                    {% for line in invoice['lines'] %}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #ccc;"><strong>{{ line.qty }}</strong></td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">{{ line.cost | money_format }}</td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">{{ line.description }}</td>

                    <td align="right" style="border-bottom:1px solid #ccc;"><strong>{{ (line.qty * line.cost) | money_format }}</strong></td>
                </tr>
                    {% endfor %}
                {% endif %}

                {% if invoice['free'] > 0 %}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #ccc;"><strong>{{ invoice['free'] }}</strong></td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">$0.00</td>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">FREE</td>

                    <td align="right" style="border-bottom:1px solid #ccc;"><strong>$0.00</strong></td>
                </tr>
                {% endif %}
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #ccc;">GST</td>
                    <td colspan="4" style="border-bottom:1px solid #ccc;">10%</td>
                    <td align="right" style="border-bottom:1px solid #ccc;">{{ (invoice['amount']/11) | money_format }}</td>
                </tr>
                <tr>
                    <td colspan="6">SUBTOTAL</td>
                    <td align="right">{{ (invoice['amount'] * 10/11) | money_format }}</td>
                </tr>
                <tr>
                    <td colspan="6"><strong>TOTAL</strong></td>
                    <td align="right">{{ invoice['amount'] | money_format }}</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <h4>Payment Methods</h4>
        <img src="{{ baseUrl }}img/mastercard.png" width="64" height="64" alt="mastercard">
        <img src="{{ baseUrl }}img/visa.png" width="64" height="64" alt="visa">
        <p><small>**To avoid any excess penalty charges, please make payments within 30 days of the due date. There will be a 2% interest charge per month on all late invoices.</small></p>

        {% if invoice['removals']|length > 0 or invoice['storages']|length > 0 %}
        <pagebreak />

        <address>
            <br>
            <strong>Removalist Quote Pty. LTD<br>
            Service Breakdown</strong>
            <br>
            Invoice # {{ invoice['id'] }}
        </address>
        {% if invoice['removals']|length > 0 %}
        <br>
        <h4>Removal Quotes</h4>
        <table width="100%" cellspacing="0" cellpadding="5">
                <thead>
                    <tr style="background:#eee;">
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">CUSTOMER</th>
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">FROM</th>
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">TO</th>
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">MOVING DATE</th>
                        <th align="center" style="padding-top:10px;border-bottom:2px solid #ccc;">ROOMS</th>
                        <th align="center" style="padding-top:10px;border-bottom:2px solid #ccc;">STATUS</th>
                        <th align="right" style="padding-top:10px;border-bottom:2px solid #ccc;">COST</th>
                    </tr>
                </thead>
                <tbody>
                    {% for removal in invoice['removals'] %}
                    <tr>
                        <td style="border-bottom:1px solid #ccc;"><strong>{{ removal['customer_name'] }}</strong></td>
                        <td style="border-bottom:1px solid #ccc;">{{ removal['from_postcode'] }}</td>
                        <td style="border-bottom:1px solid #ccc;">{{ removal['to_postcode'] }}</td>
                        <td style="border-bottom:1px solid #ccc;">{{ removal['moving_date'] }}</td>
                        <td style="border-bottom:1px solid #ccc;" align="center">{{ removal['bedrooms'] }}</td>
                        <td style="border-bottom:1px solid #ccc;" align="center">
                            {% if removal['status'] == constant("Quote::WON") %}
                            Won
                            {% elseif removal['status'] == constant("Quote::LOST") %}
                            Lost
                            {% else %}
                            Open
                            {% endif %}
                        </td>
                        <td style="border-bottom:1px solid #ccc;" align="right">
                            {% if removal['free'] == 1 %}
                            Free
                            {% else %}
                            ${{ invoice['price_per_quote'] }}
                            {% endif %}
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        {% endif %}

        {% if invoice['storages']|length > 0 %}
        <br>
        <h4>Storage Quotes</h4>
        <table width="100%" cellspacing="0" cellpadding="5">
                <thead>
                    <tr style="background:#eee;">
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">CUSTOMER</th>
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">PICKUP</th>
                        <th align="left" style="padding-top:10px;border-bottom:2px solid #ccc;">PERIOD</th>
                        <th align="center" style="padding-top:10px;border-bottom:2px solid #ccc;">CONTAINERS</th>
                        <th align="center" style="padding-top:10px;border-bottom:2px solid #ccc;">STATUS</th>
                        <th align="right" style="padding-top:10px;border-bottom:2px solid #ccc;">COST</th>
                    </tr>
                </thead>
                <tbody>
                    {% for storage in invoice['storages'] %}
                    <tr>
                        <td style="border-bottom:1px solid #ccc;"><strong>{{ storage['customer_name'] }}</strong></td>
                        <td style="border-bottom:1px solid #ccc;">{{ storage['pickup_postcode'] }}</td>
                        <td style="border-bottom:1px solid #ccc;">{{ storage['period'] }}</td>
                        <td style="border-bottom:1px solid #ccc;" align="center">{{ storage['containers'] }}</td>
                        <td style="border-bottom:1px solid #ccc;" align="center">
                            {% if storage['status'] == constant("Quote::WON") %}
                            Won
                            {% elseif storage['status'] == constant("Quote::LOST") %}
                            Lost
                            {% else %}
                            Open
                            {% endif %}
                        </td>
                        <td style="border-bottom:1px solid #ccc;" align="right">
                            {% if storage['free'] == 1 %}
                            Free
                            {% else %}
                            ${{ invoice['price_per_quote'] }}
                            {% endif %}
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        {% endif %}

        {% endif %}
        
        <pagebreak />
    </body>
</html>
