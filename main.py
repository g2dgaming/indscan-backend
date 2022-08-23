import json
import random
import string
import time
from pprint import pprint

from faker import Faker

Faker.seed(0)
fake = Faker()

import random as r

mult = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 0, 6, 7, 8, 9, 5], [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7], [4, 0, 1, 2, 3, 9, 5, 6, 7, 8], [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2], [7, 6, 5, 9, 8, 2, 1, 0, 4, 3], [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]]
perm = [[0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 5, 7, 6, 2, 8, 3, 0, 9, 4], [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
        [8, 9, 1, 6, 0, 4, 3, 5, 2, 7], [9, 4, 5, 3, 1, 2, 6, 8, 7, 0], [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
        [2, 7, 9, 3, 8, 0, 6, 4, 1, 5], [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]]
inv = [0, 4, 3, 2, 1, 5, 6, 7, 8, 9]


def Digit(msg):
    try:
        i = len(msg)
        j = 0
        x = 0

        while i > 0:
            i -= 1
            j += 1
            x = mult[x][perm[(j % 8)][int(msg[i])]]

        return inv[x]
    except ValueError:
        return None
    except IndexError:
        return None


def Generate(msg):
    d = Digit(msg)
    if d:
        return msg + str(d)
    else:
        return None


def generateAadhar():
    defNum = str(r.randint(1111, 9999)) + str(r.randint(1111, 9999)) + str(r.randint(111, 999))
    aNum = Generate(defNum)
    check = str(type(aNum))
    while check == "<class 'NoneType'>":
        defNum = str(r.randint(1111, 9999)) + str(r.randint(1111, 9999)) + str(r.randint(111, 999))
        aNum = Generate(defNum)
        check = str(type(aNum))
    return aNum


# generate pan card number
def pan_card_generator():
    # 5 random letters from a to z
    def random_letter():
        return chr(random.randint(97, 122))

    s = ""
    for i in range(5):
        s += random_letter()

    # 4 random digits from 0 to 9
    def random_digit():
        return random.randint(0, 9)

    for i in range(4):
        s += str(random_digit())
    s += random_letter()
    # uppercase whole string
    s = s.upper()
    return s


def random_bool():
    return random.choice([True, False])


def doc_data():
    res = {}
    res[
        'image'] = '''iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=='''
    res['thumb'] = res['image']
    res["is_aadhar_card"] = random_bool()
    if res["is_aadhar_card"]:
        res["aadhar_number"] = generateAadhar()
    else:
        res["aadhar_number"] = ""
    res["is_pan_card"] = random_bool() and not res["is_aadhar_card"]
    if res["is_pan_card"]:
        res["pan_number"] = pan_card_generator()
    else:
        res["pan_number"] = ""

    entities = {}
    '''
    "address"
    "email"
    "phone"
    "url"
    "date"
    "money"
    "tracking" 
    '''
    entities["address"] = []
    entities["email"] = []
    entities["phone"] = []
    entities["url"] = []
    entities["date"] = []
    entities["money"] = []
    entities["tracking"] = []

    # random boolean generator

    # random number generator from range
    def random_number(min, max):
        return random.randint(min, max)

    for _ in range(random_number(0, 5)):
        entities["email"].append(fake.free_email())
    for _ in range(random_number(0, 5)):
        entities["phone"].append(fake.phone_number())
    for _ in range(random_number(0, 5)):
        entities["url"].append(fake.url())
    for _ in range(random_number(0, 5)):
        entities["date"].append(fake.date())
    for _ in range(random_number(0, 5)):
        # currency symbols
        entities["money"].append(
            fake.currency_symbol() + str(fake.pydecimal(left_digits=2, right_digits=2, positive=True)))
    for _ in range(random_number(0, 5)):
        entities["tracking"].append(fake.pystr(min_chars=10, max_chars=10))
    for _ in range(random_number(0, 5)):
        entities["address"].append(fake.address())
    res["entities"] = entities

    res["englishText"] = " " + str(fake.text(max_nb_chars=20)) + " " + res["aadhar_number"] + " " + res[
        "pan_number"] + " "
    # english text should contain all the entities
    for entity in entities:
        for value in entities[entity]:
            if value not in res["englishText"]:
                res["englishText"] += " " + value + " "
                res["englishText"] += str(fake.text(max_nb_chars=20))
    # fake hindi text
    res["hindiText"] = res["englishText"]
    # hindi text should contain all the entities

    return res


# start countime time
pprint(doc_data())
doc_category = []
for i in range(100):
    doc_category.append(fake.name()+ " card")
import requests

headers = {
    'Authorization': 'Bearer 1|Xf1e2vqGbanMMRF0M78BsAN0l1Fbp6ftCE6whOjj',
    'Content-Type': 'application/json'
}
start = time.time()
for w in range(100000):
    try:
        url = "http://localhost:8000/api/documents"

        payload = json.dumps({
            "doc_name": str(fake.job() + " " + fake.first_name()) + " " + fake.last_name() + "'s " + str(random.randint(1, 5000)),
            "notes": fake.bs(),
            "doc_category": doc_category[random.randint(0,99)]
        })
        response = requests.request("POST", url, headers=headers, data=payload)

        url = "http://localhost:8000/api/documents/"+str(response.json()['document_id'])
        #print(response.json()['document_id'])

        for i in range(3):
            response = requests.post(url, headers=headers, json=doc_data())
    except:
        pass

# end countime time
end = time.time()

# print time in minutes
print("Time taken: ", (end - start) / 60)

# print("Status Code", response.status_code)
# print("JSON Response ", response.json())

with open('data.json', 'w') as outfile:
    json.dump(doc_data(), outfile)
    outfile.close()
'''start = time.time()

# Opening JSON file
for k in range(25):
    try:
        with open('data.json', 'r') as openfile:
            # Reading from json file
            data = json.load(openfile)
    except FileNotFoundError:
        data = {
            "docs": []
        }
        # writing to json file
        with open('data.json', 'w') as outfile:
            json.dump(data, outfile)
            outfile.close()
            

    # pretty print json
    for i in range(10000):
        # pprint(doc_data())
        print(i)
        data['docs'].append(doc_data())
        # print(doc_data()["englishText"])
    # Writing to json file
    with open('data.json', 'w') as outfile:
        json.dump(data, outfile)
        outfile.close()
        print("done")

# end countime time
end = time.time()

# print time in minutes
print("Time taken: ", (end - start) / 60)
'''
