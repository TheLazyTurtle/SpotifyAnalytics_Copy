from termcolor import colored
from datetime import datetime
import colorama

colorama.init()

def printc(*args):
    sectionLength = 30
    
    time = datetime.now().strftime("%H:%M:%S")

    string = dividers(-1)
    string += colored(time, "white")

    for x in range(0, len(args), 2):
        string += dividers(0) 
        
        try:
            string += colored(str(args[x]).ljust(sectionLength)[:sectionLength], args[x + 1])
        except Exception:
            string += colored(str(args[x]).ljust(sectionLength)[:sectionLength], "white")

    string += dividers(1) 
    print(string)

def dividers(place):
    # -1 is start, 0 is normal, 1 is end
    if (place == -1):
        return colored("| ", "red")
    elif (place == 0):
        return colored(" | ", "red")
    else:
        return colored(" |", "red")
