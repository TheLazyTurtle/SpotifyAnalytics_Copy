from termcolor import colored
from datetime import datetime
import colorama

colorama.init()

def printc(*args):
    string = colored(datetime.now().strftime("%H:%M:%S") + " -", "white")

    for x in range(0, len(args), 2):
        try:
            string += " " + colored(args[x], args[x + 1])
        except Exception:
            string += " " + colored(args[x], "white")


    print(string)
