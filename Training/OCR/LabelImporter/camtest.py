# Open the cam and show it to the user

import cv2

cap = cv2.VideoCapture(3)
cap.set(3, 1440) #640
cap.set(4, 810) #480

while True:
    success, img = cap.read()
    cv2.imshow("Image", img)
    cv2.waitKey(1)
