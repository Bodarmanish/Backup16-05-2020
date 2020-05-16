import random
computer = ["stone" , "paper" , "sizzer"]
user = ["stone" , "paper" , "sizzer"]
print (" choose from stone , paper & sizzer")
for user_choice in user:
    user_choice = input(str())
    if user_choice in user:
        computer_choice = random.choice(computer)
        print (computer_choice)
        if computer_choice == "stone" and user_choice == "paper":
            print ("YOU WON !!!")
        elif computer_choice == "paper" and user_choice == "sizzer":
            print ("YOU WON !!!")
        elif computer_choice == "sizzer" and user_choice == "stone":
            print ("YOU WON !!!")
        elif computer_choice == user_choice :
            print ("TIIED !!!")
        else :
            print ("YOU LOOSE !!!")
    else:
        print (" PLEASE CHOOSE AND TYPE YOUR CHOICE AS MENTIONED ABOVE ONLY !")