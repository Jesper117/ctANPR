import cv2
import requests
import json
from PIL import Image
from io import BytesIO

# Replace 'your_json_file.json' with the actual path to your JSON data file
with open('batch3.ndjson', 'r') as json_file:
    json_lines = json_file.read().strip().split('\n')
    json_data = [json.loads(line) for line in json_lines]

# Create a counter for naming the image and label files
counter = 1

# Iterate through the JSON data
for entry in json_data:
    url = entry["data_row"]["row_data"]
    response = requests.get(url)

    if response.status_code == 200:
        image_bytes = BytesIO(response.content)
        img = Image.open(image_bytes)
        img.save(f"images/{counter}.png")  # Save the image in the "images" folder

        # Open the label file for writing
        with open(f"labels/{counter}.txt", "w") as label_file:
            for label_info in entry["projects"]["clnj8em1u1ix907wf3frshbac"]["labels"][0]["annotations"]["objects"]:
                label = label_info["name"]
                left = float(label_info["bounding_box"]["left"])
                top = float(label_info["bounding_box"]["top"])
                width = float(label_info["bounding_box"]["width"])
                height = float(label_info["bounding_box"]["height"])

                # Calculate YOLOv8 format coordinates
                x_center = left + width / 2
                y_center = top + height / 2
                x_center /= img.width
                y_center /= img.height
                width /= img.width
                height /= img.height

                # Write the YOLOv8 label to the label file
                label_line = f"{label} {x_center:.6f} {y_center:.6f} {width:.6f} {height:.6f}\n"
                label_file.write(label_line)

        counter += 1
        print(f"Successfully completed image {counter} / {len(json_data)}")
    else:
        print(f"Failed to download image from URL: {url}")
