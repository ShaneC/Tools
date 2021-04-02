import requests
import csv

# OBTAIN AN OAUTH 2.0 FITBIT ACCESS TOKEN (You can use a tool like Postman)
fitbitAccessToken = ""

def authHeader(accessToken):
    return {"Authorization" : "Bearer " + accessToken }

entries = {}

# FITBIT
dateRanges = [
    { "start" : "2013-02-01", "end" : "2014-12-31" }, 
    { "start" : "2015-01-01", "end" : "2016-12-31" },
    { "start" : "2017-01-01", "end" : "2018-12-31" },
    { "start" : "2019-01-01", "end" : "2020-12-31" },
    { "start" : "2021-01-01", "end" : "2021-03-27" }
]

for dateRange in dateRanges:

    ## FITBIT -- Get Body Weight
    getBodyWeightUri = "https://api.fitbit.com/1/user/-/body/weight/date/" + dateRange["start"] + "/" + dateRange["end"] + ".json"
    req = requests.get(getBodyWeightUri, headers=authHeader(fitbitAccessToken))

    if req.status_code != 200:
        print("[" + str(req.status_code) + "]: " + req.text)
    else:

        for item in req.json()["body-weight"]:
            
            if item["dateTime"] in entries:
                print("Duplicate key found\n")
                exit(1)

            entries[item["dateTime"]] = {}
            entries[item["dateTime"]]["weight"] = item["value"]

    ## FITBIT -- Get Body Fat
    getBodyFatUri = "https://api.fitbit.com/1/user/-/body/fat/date/" + dateRange["start"] + "/" + dateRange["end"] + ".json"
    req = requests.get(getBodyFatUri, headers=authHeader(fitbitAccessToken))

    if req.status_code != 200:
        print("[" + str(req.status_code) + "]: " + req.text)
    else:

        for item in req.json()["body-fat"]:
            
            if item["dateTime"] not in entries:
                print("Entry not found\n")
                continue

            entries[item["dateTime"]]["fat"] = item["value"]



if __name__ == '__main__':

    fileCount = 1
    while len(entries) > 0:
        with open("WithingsImportCSVs/record_" + str(fileCount) + ".csv", "w", newline='') as csvfile:
            
            writer = csv.writer(csvfile)
            writer.writerow(["Date","Weight","Fat mass"])
            lineCount = 1

            for dateTime, value in dict(entries).items():
                if(lineCount < 300):
                    row = entries[dateTime]
                    fatMass = str(float(row["weight"]) * (float(row["fat"]) / 100))
                    writer.writerow([dateTime + " 08:30:00", row["weight"], fatMass])
                    entries.pop(dateTime)
                    lineCount += 1
                else:
                    break
            
            fileCount += 1
            continue

exit(0)