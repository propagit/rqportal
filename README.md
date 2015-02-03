# rqportal
Removalist Quote Portal

Cron Job

vi /etc/crontab

* * * * * root php /home/propates/public_html/rqportal/scripts/distribute-quote.php >> /home/propates/public_html/rqportal/logs/quotes.txt
# Append to log file
