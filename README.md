crProxy was built to be a fully automated proxy finder/validator service. 


There are 2 scripts provided to make this happen - 

scrape script :: app/console scrape:proxies
The scrape script will scrape 6 online free proxy providers to save lists of ips to database for status check

status check script :: app/console proxy:check
The status check script will check non checked proxies to determine which are online / available and working as expected



Once you run app/console proxy:check you can the query the database for the fastest proxy available for the country you want to make a curl request using a proxy.
