import mysql.connector
import requests
import configparser

config = configparser.ConfigParser()
config.read('config.ini')

mydb = mysql.connector.connect(
  host="localhost",
  user="ec2-user",
  passwd="ec2-user",
  database=config['ss']['DB_DB'].strip('\"'),
  charset="ascii",
  auth_plugin='mysql_native_password'
)

mycursor = mydb.cursor()

mycursor.execute('SELECT class, topics FROM ReadingTopics WHERE date = DATE(NOW())')

myresult = mycursor.fetchall()

key=config['ss']['TRELLO_KEY'].strip('\"')
token=config['ss']['TRELLO_TOKEN'].strip('\"')

for x in myresult:
    url = "https://api.trello.com/1/cards"
    data = {
        "name": "Reading for " + x[0],
        "desc": x[1],
        "idList":config['ss']['TRELLO_LIST'].strip('\"'),
        "idLabels":"5b65f7740e84690feeab06f8",
        "key" : key,
        "token" : token
    }
    requests.post(url, data = data)
