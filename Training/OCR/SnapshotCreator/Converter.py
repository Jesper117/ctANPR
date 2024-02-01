from PIL import Image
import os

input_folder = "Snapshots"
output_folder = "SnapshotsConverted"

if not os.path.exists(output_folder):
    os.makedirs(output_folder)

for filename in os.listdir(input_folder):
    if filename.endswith(".png"):
        img = Image.open(os.path.join(input_folder, filename))

        # Convert the image to RGB mode
        img = img.convert("RGB")

        new_filename = os.path.splitext(filename)[0] + ".jpg"

        img.save(os.path.join(output_folder, new_filename))

print("Conversion completed.")
