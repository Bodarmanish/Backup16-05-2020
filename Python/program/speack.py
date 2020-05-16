import os
import datetime

def tts(text):
      return os.system("espeak  -s 155 -a 200 "+text+" " )

m = datetime.datetime.now().strftime("%I %M %S")
tts("'Sir the time is"+str(int(m[0:2]))+" "+str(int(m[3:5]))+" : ' ")