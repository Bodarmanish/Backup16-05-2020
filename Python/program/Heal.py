import os


def atime():
    import datetime
    a = datetime.datetime.now()
    return a.strftime("%x " + "%X")


time = atime()


def manish():
    print("\n1: Retrieve\n2: Submit")
    rs = int(input("Select a number: "))
    # For writing data
    if rs == 2:
        print("\n1: Cars\n2: Foods")
        cf = int(input("Select a number: "))
        if cf == 1:
            with open("manishCar.txt", "a") as f:
                mc = input()
                f.write("\n" + time + " " + mc)
        elif cf == 2:
            with open("manishFood.txt", "a") as f:
                mf = input()
                f.write("\n" + time + " " + mf)
        else:
            print("\nInvalid input! Try Again")
    # For reading data
    elif rs == 1:
        print("\n1: Cars\n2: Foods")
        cf = int(input("1 or 2 : "))
        if cf == 1:
            if os.path.exists("manishCar.txt"):
                with open("manishCar.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")

        elif cf == 2:
            if os.path.exists("manishFood.txt"):
                with open("manishFood.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")
        else:
            print("Invalid input! Try Again")
    else:
        print("\nInvalid Selection please try again")


def sanjay():
    print("\n1: Retrieve\n2: Submit")
    rs = int(input("Select a number: "))
    # For writing data
    if rs == 2:
        print("\n1: Cars\n2: Foods")
        cf = int(input("Select a number: "))
        if cf == 1:
            with open("sanjayCar.txt", "a") as f:
                mc = input()
                f.write("\n" + time + " " + mc)
        elif cf == 2:
            with open("sanjayFood.txt", "a") as f:
                mf = input()
                f.write("\n" + time + " " + mf)
        else:
            print("\nInvalid input! Try Again")
    # For reading data
    elif rs == 1:
        print("\n1: Cars\n2: Foods")
        cf = int(input("1 or 2 : "))
        if cf == 1:
            if os.path.exists("sanjayCar.txt"):
                with open("sanjayCar.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")

        elif cf == 2:
            if os.path.exists("sanjayFood.txt"):
                with open("sanjayFood.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")
        else:
            print("Invalid input! Try Again")
    else:
        print("\nInvalid Selection please try again")


def ramesh():
    print("\n1: Retrieve\n2: Submit")
    rs = int(input("Select a number: "))
    # For writing data
    if rs == 2:
        print("\n1: Cars\n2: Foods")
        cf = int(input("Select a number: "))
        if cf == 1:
            with open("rameshCar.txt", "a") as f:
                mc = input()
                f.write("\n" + time + " " + mc)
        elif cf == 2:
            with open("rameshFood.txt", "a") as f:
                mf = input()
                f.write("\n" + time + " " + mf)
        else:
            print("\nInvalid input! Try Again")
    # For reading data
    elif rs == 1:
        print("\n1: Cars\n2: Foods")
        cf = int(input("1 or 2 : "))
        if cf == 1:
            if os.path.exists("rameshCar.txt"):
                with open("rameshCar.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")

        elif cf == 2:
            if os.path.exists("rameshFood.txt"):
                with open("rameshFood.txt") as f:
                    a = f.read()
                    print(a)
            else:
                print("The file does not exist")
        else:
            print("Invalid input! Try Again")
    else:
        print("\nInvalid Selection please try again")


q = 2
while q == 2:
    print("\n1: manish\n2: sanjay\n3: ramesh")
    name = input("Select a number: ")
    if name == 1:
        manish()
    elif name == 2:
        sanjay()
    elif name == 3:
        ramesh()
    else:
        print("Invalid")

    print("\n1: Quit the program\n2; Rerun the program")
    q = int(input("Select a number: "))
    if q == 1:
        print("Program quit successfully ")
    else:
        print()
