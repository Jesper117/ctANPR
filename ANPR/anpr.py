#   ______ .___________.    ___      .__   __. .______   .______
#  /      ||           |   /   \     |  \ |  | |   _  \  |   _  \
# |  ,----'`---|  |----`  /  ^  \    |   \|  | |  |_)  | |  |_)  |
# |  |         |  |      /  /_\  \   |  . `  | |   ___/  |      /
# |  `----.    |  |     /  _____  \  |  |\   | |  |      |  |\  \----.
#  \______|    |__|    /__/     \__\ |__| \__| | _|      | _| `._____|
#
# Auteur: Jesper Minks
# Keuzedeel Software Developer 2023


from ultralytics import YOLO
import cv2
import math
import os
import time
import threading
import requests

MinimumConfidence = .5
MinimumPlateX = 60
WebcamSource = 3
PlateCooldownInMinutes = 5

Location_ID = 1

ctANPR_API_KEY = "55gYShdGkDvJMmfPvNBVtntVjckQ9v"
ctANPR_PLATE_API_BASE = "http://localhost/ctANPR/Web/api/traffic.php?api_key=" + ctANPR_API_KEY + "&location_id=" + str(Location_ID)

WindowsSizeX = 1440 # Standaard: 640
WindowsSizeY = 810 # Standaard: 480

PlateDetectionWeight = YOLO("Weights/PlateDetection.pt")
OCRWeight = YOLO("Weights/OCR.pt")

PlateDetectionClassnames = ["number_plate"]
OCRClassnames = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"]

Cap = cv2.VideoCapture(WebcamSource)
Cap.set(3, WindowsSizeX)
Cap.set(4, WindowsSizeY)

PlateCooldowns = []
def PlateCooldownLoop():
    while True:
        for PlateCooldown in PlateCooldowns:
            if time.time() - PlateCooldown[1] > PlateCooldownInMinutes * 60:
                PlateCooldowns.remove(PlateCooldown)

        time.sleep(1)

def IsPlateOnCooldown(Plate):
    for PlateCooldown in PlateCooldowns:
        if PlateCooldown[0] == Plate:
            return True

    return False

def AddPlateCooldown(Plate):
    if not IsPlateOnCooldown(Plate):
        PlateCooldowns.append([Plate, time.time()])

def Snapshot(X1, Y1, X2, Y2, Img):
    Path, Dirs, Files = next(os.walk("Temp/Snapshots"))
    FileCount = len(Files)
    Name = str(FileCount + 1) + ".jpg"
    FullName = "Temp/Snapshots/" + Name

    CropImg = Img[Y1:Y2, X1:X2]
    cv2.imwrite(FullName, CropImg)

    return FullName

def RemoveSnapshot(Path):
    try:
        os.remove(Path)
    except:
        pass

def boxes_overlap(box1, box2):
    x1a, y1a, x2a, y2a = box1
    x1b, y1b, x2b, y2b = box2

    if x1a >= x2b or x2a <= x1b or y1a >= y2b or y2a <= y1b:
        return False
    else:
        return True

def SendPlate(Plate):
    res = requests.get(ctANPR_PLATE_API_BASE + "&kenteken=" + Plate)
def OCR(Path, PlateConfidence):
    Results = OCRWeight(Path, stream=True, verbose=False)
    Table = []

    LowestConfidence = PlateConfidence

    for Result in Results:
        Boxes = Result.boxes

        for Box in Boxes:
            Confidence = math.ceil((Box.conf[0] * 100)) / 100

            X1, Y1, X2, Y2 = Box.xyxy[0]
            X1, Y1, X2, Y2 = int(X1), int(Y1), int(X2), int(Y2)

            if Confidence >= LowestConfidence:
                LowestConfidence = Confidence

            Table.append([X1, Y1, X2, Y2, Box.cls[0]])

    Table.sort(key=lambda x: x[0])

    Text = ""
    for Row in Table:
        Text += OCRClassnames[int(Row[4])]

    RemoveSnapshot(Path)

    return Text, LowestConfidence

PlateCooldownThread = threading.Thread(target=PlateCooldownLoop)
PlateCooldownThread.start()

while True:
    Success, Img = Cap.read()
    Results = PlateDetectionWeight(Img, stream=True, verbose=False)
    PlateList = []

    for Result in Results:
        Boxes = Result.boxes

        for Box in Boxes:
            Confidence = math.ceil((Box.conf[0] * 100)) / 100
            PlateSizeX = Box.xyxy[0][2] - Box.xyxy[0][0]

            if Confidence >= MinimumConfidence and PlateSizeX >= MinimumPlateX:
                X1, Y1, X2, Y2 = Box.xyxy[0]
                X1, Y1, X2, Y2 = int(X1), int(Y1), int(X2), int(Y2)

                overlapping = False
                for existing_plate in PlateList:
                    if boxes_overlap((X1, Y1, X2, Y2), existing_plate[0]) and Confidence < existing_plate[1]:
                        overlapping = True
                        break
                if not overlapping:
                    PlateList.append(((X1, Y1, X2, Y2), Confidence))
                    Path = Snapshot(X1, Y1, X2, Y2, Img)
                    Text, LowestConfidence = OCR(Path, Confidence)

                    DisplayText = Text + " " + str(LowestConfidence)

                    cv2.rectangle(Img, (X1, Y1), (X2, Y2), (0, 0, 255), 2)
                    cv2.putText(Img, DisplayText, (X1, Y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 2, (0, 0, 255), 2)

                    if not IsPlateOnCooldown(Text):
                        AddPlateCooldown(Text)

                        SendPlateThread = threading.Thread(target=SendPlate, args=(Text,))
                        SendPlateThread.start()




    cv2.imshow("ctANPR", Img)
    if cv2.waitKey(1) & 0xFF == ord("q"):
        break


Cap.release()
cv2.destroyAllWindows()