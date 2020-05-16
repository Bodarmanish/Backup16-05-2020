items = [int,"manish",17,1,45,66,78,100, "tes"]

for item in items:
    if str(item).isnumeric() and item >= 7:
        print(item)
