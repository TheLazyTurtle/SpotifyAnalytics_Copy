from termcolor import colored
def printMsg(msg1, color1, msg2="", color2="white", msg3="", color3="white"):
    print(colored(msg1, color1), colored(msg2, color2), colored(msg3, color3))
