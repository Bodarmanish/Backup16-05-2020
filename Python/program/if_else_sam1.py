age = int(input("Enter your age: "))
if age > 18 and age < 101:
    print("you can drive")
elif age > 7 and age < 18:
    print("you can't drive")
elif age == 18:
    print("come for physical test")
else:
    print("not a valid age")

