# StudySplit
##### A system that splits up how you need to review for upcoming tests

![Screenshot](screenshot.png)

## How it works
Simply navigate to your local instance of the software and tell it when the test is and what you need to study. The system will split up the concepts so that you can review a bit every day leading up to the test. Every day that you have a concept to review, you'll get a new card added to Trello.

## Installation
1. Create a new table in your MySQL DB (see `table.txt` for create command)
2. Update the credentials in `config example.ini` (for Trello and the DB)
3. Rename `config example.ini` to `config.ini`
4. Place the code on a server capable of running PHP and Python
5. Create a daily cronjob for `cron.py`
6. Avoid procrastination on your future tests!

## `.ics` Feed
You can now also get an ICS feed of when you will be studying what. Simply try to add `https://YOUR_DOMAIN/PATH/feed.ics` as a remote calendar. The `.htaccess` will rewrite the `.ics` to `.php` and everything should work out nicely.
