from termcolor import colored
from datetime import datetime
import colorama

colorama.init()

def printc(m1, c1, m2="", c2="white", m3="", c3="white"):
    print(colored(datetime.now().strftime("%H:%M:%S") + " -", "white"),
          colored(m1, c1), colored(m2, c2), colored(m3, c3))
