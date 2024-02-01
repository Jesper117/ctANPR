from ultralytics import YOLO
import cv2
import math

minimum_confidence = .1

# start webcam
cap = cv2.VideoCapture(3)
cap.set(3, 1440) #640
cap.set(4, 810) #480

# model
weight = YOLO("best.pt")


# object classes
classNames = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0"]

#def GetSnapshotCount():
#    import os
#    path, dirs, files = next(os.walk("Snapshots"))
#    file_count = len(files)
#    return file_count

while True:
    success, img = cap.read()
    # Get results, make sure debugging is off and output will not be shown
    results = weight(img, stream=True, verbose=False)

    # coordinates
    for r in results:
        boxes = r.boxes

        for box in boxes:
            # Ensure the class index is within a valid range
            class_index = int(box.cls[0])
            if 0 <= class_index < len(classNames):
                labelname = classNames[class_index]
            else:
                labelname = "Unknown"  # Handle the case where the class index is out of range

            # confidence
            confidence = math.ceil((box.conf[0] * 100)) / 100
            boxtext = labelname + " " + str(confidence)

            if confidence >= minimum_confidence:  # Add confidence threshold check
                # bounding box
                x1, y1, x2, y2 = box.xyxy[0]
                x1, y1, x2, y2 = int(x1), int(y1), int(x2), int(y2)  # convert to int values

                # We need to save a snapshot of the bounding box, so we can send it to the OCR analysis
                # Save the Snapshots in the 'Snapshots' folder

                if x2 - x1 >= 65:
                    #FileCount = GetSnapshotCount()
                    #Name = str(FileCount + 1) + ".jpg"

                    #crop_img = img[y1:y2, x1:x2]
                    #cv2.imwrite("Snapshots/" + Name, crop_img)

                    # put box in cam
                    cv2.rectangle(img, (x1, y1), (x2, y2), (255, 0, 255), 3)

                    # class name
                    cls = int(box.cls[0])

                    # object details
                    org = [x1, y1]
                    font = cv2.FONT_HERSHEY_SIMPLEX
                    fontScale = 1
                    color = (255, 0, 0)
                    thickness = 2

                    cv2.putText(img, str(boxtext), (x1, y1 - 10), font, fontScale, color, thickness)

    cv2.imshow('Webcam', img)
    if cv2.waitKey(1) == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()