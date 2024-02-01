from ultralytics import YOLO
import cv2
import math
import os
import time
import threading

MinimumConfidence = .76
WebcamSource = 3
SnapshotDebounce = 1

WindowsSizeX = 1440 # Standaard: 640
WindowsSizeY = 810 # Standaard: 480

Debounced = False

PlateDetectionWeight = YOLO("PlateDetection.pt")

PlateDetectionClassnames = ["number_plate"]

Cap = cv2.VideoCapture(WebcamSource)
Cap.set(3, WindowsSizeX)
Cap.set(4, WindowsSizeY)

def Snapshot(X1, Y1, X2, Y2, Img):
    global Debounced

    Debounced = True

    Path, Dirs, Files = next(os.walk("Snapshots"))
    # random name uuid4
    Name = str(time.time()) + ".jpg"
    FullName = "Snapshots/" + Name

    CropImg = Img[Y1:Y2, X1:X2]
    cv2.imwrite(FullName, CropImg)

    time.sleep(SnapshotDebounce)

    print("Snapshot saved: " + FullName)

    Debounced = False

    return FullName

def StartSnapshotProcess(X1, Y1, X2, Y2, Img):
    global Debounced

    if not Debounced:
        SnapshotThread = threading.Thread(target=Snapshot, args=(X1, Y1, X2, Y2, Img))
        SnapshotThread.start()
        return SnapshotThread


while True:
    if Debounced:
        time.sleep(.1)
        continue

    Success, Img = Cap.read()
    Results = PlateDetectionWeight(Img, stream=True, verbose=False)
    PlateList = []

    for Result in Results:
        Boxes = Result.boxes

        for Box in Boxes:
            Confidence = math.ceil((Box.conf[0] * 100)) / 100

            if Confidence >= MinimumConfidence:
                X1, Y1, X2, Y2 = Box.xyxy[0]
                X1, Y1, X2, Y2 = int(X1), int(Y1), int(X2), int(Y2)

                StartSnapshotProcess(X1, Y1, X2, Y2, Img)
                cv2.rectangle(Img, (X1, Y1), (X2, Y2), (0, 0, 0), 2)

                print("Detection, conf=" + str(Confidence))




    cv2.imshow("ctANPR Snapshot Creator", Img)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break


Cap.release()
cv2.destroyAllWindows()