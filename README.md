# rqportal
Removalist Quote Portal

Cron Job

vi /etc/crontab

* * * * * root php /home/propates/public_html/rqportal/scripts/distribute-quote.php >> /home/propates/public_html/rqportal/logs/quotes.txt
# Append to log file


Quote Submission API

List of fields

+ moving_type: enum: moving_home, moving_office, storage, moving_interstate
Common fields:
+ customer_name
+ customer_email
+ customer_phone
+ notes

If moving_type == 'storage'
+ pickup: postcode
+ containers
+ period

If moving_type == 'removal'
+ moving_from: postcode
+ moving_to: postcode
+ moving_date: YYYY-MM-DD format
+ bedrooms
+ packing
